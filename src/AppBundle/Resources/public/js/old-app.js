an_val = comb_val = false;
$(function() {
    $('.marci').slimScroll({ height: $(window).height() - '105'+'px', step: 10 });
    $('.motorizari-data').slimScroll({ height: $(window).height() - '185'+'px', step: 10 });

    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        //console.info('mobile');
    } else {
        $(window).on('popstate', function () {
            location.reload();
        });
    }

    if(marca_id != null || marca_id != undefined) { initializeNextDivItems('side-bar-model-item', 'side-bar-motorizari'); }
    if(motorizari_exists) { motorizari_select(); }
    if(an_selected) { combustibili_select(); }

    if($('.side-bar-marca-item') != 'undefined') {
        $(".side-bar-marca-item").on("click", function(e) {
            e.preventDefault();
            marca_id = $(this).data('item');
            if(typeof marca_id != 'undefined' && typeof marca_id != 'null') {
                $("#side-bar-motorizari").hide('slide', {}, 'normal', ajaxLeftSideRequest(/*next_div*/'side-bar-modele', marca_id)).html('');
            }
            $.each($('ul.marci').find('li'), function(i, v) {
                $(v).removeClass('li-active');
            });
            $(this).addClass('li-active');
        });
    }

    function ajaxLeftSideRequest(next_div, marca_id, model_id) {
        var dataString = {marca_id: marca_id, model_id: model_id};
        var timeOut = setTimeout(function() {
            $("#" + next_div).hide('slide', {}, 'normal').html('');

            $.ajax({
                type: "POST",
                url: left_side_request_url,
                data: dataString,
                cache: false,
                success: function(response)
                {
                    if(next_div == 'side-bar-modele' && response.modele) {
                        var modele_ul = '<ul class="modele">'
                        $.each(response.modele, function(model_id, model) {
                            modele_ul += '<li class="side-bar-model-item" data-item="' + model.id + '">' + model.nume + '</li>'
                        });
                        modele_ul += '</ul>';
                        $("#" + next_div).show('slide', {}, 'slow', scrollTop).html(modele_ul);
                        initializeNextDivItems('side-bar-model-item', 'side-bar-motorizari');
                        window.history.pushState(response.modele, "Piese auto " + response.marca.nume, "/app_dev.php/piese-auto/" + response.marca.slug);
                        document.title = "Piese auto " + response.marca.nume;
                    }


                    if(next_div == 'side-bar-motorizari' && response.motorizari) {
                        //var modele_ul = '<ul class="motorizari-data">';
                        if(response.ani_motorizari != undefined) {
                            $(".ani-motorizari ").remove();
                            $(".combustibili-motorizari ").remove();
                            initAniMotorizari(response.ani_motorizari, next_div);
                        } else {

                            $(".motorizari-data").html('');
                            var modele_ul = '';
                            $.each(response.motorizari, function (motorizare_id, motorizare) {
                                modele_ul += '<li class="side-bar-motorizare-item" data-item="' + motorizare.id + '">' + motorizare.nume + '' +
                                '<div class="info">' +
                                'an: ' + motorizare.an_start + ' - ' + motorizare.an_final +
                                ' cmc: ' + motorizare.cmc + ' ' + motorizare.kw + 'kw (' + motorizare.cp + 'cp)' +
                                ' cod motor: ' + motorizare.cod_motor + ' - ' + motorizare.caroserie +
                                '</div></li>';
                            });
                            //modele_ul += '</ul>';
                            $("#" + next_div).show('slide', {}, 'slow', scrollTop);//.html(modele_ul);
                            $(modele_ul).appendTo(".motorizari-data");

                            $('.motorizari').slimScroll({
                                height: $(window).height() - '105' + 'px',
                                step: 10
                            });
                        }
                        window.history.pushState(response.motorizari, "Piese auto " + response.marca.nume + " " + response.model.nume, "/app_dev.php/piese-auto/" + response.marca.slug + "-" + response.model.slug);
                        document.title = "Piese auto " + response.marca.nume + " " + response.model.nume;
                    }
                    clearTimeout(timeOut);
                }
            });
        }, 500);
    }

    function scrollTop() {
        $('html').animate({
            scrollTop: 0
        }, 'slow');
    }

    function initializeNextDivItems(div_items_name, next_div) {
        $('.modele').slimScroll({ height: $(window).height() - '105'+'px',  step: 10 });

        if($('.' + div_items_name) != 'undefined') {
            $("." + div_items_name).on("click", function(e) {
                e.preventDefault();
                model_id = $(this).data('item');
                if(typeof model_id != 'undefined' && typeof model_id != 'null') {
                    ajaxLeftSideRequest(next_div, marca_id, model_id);
                }
                $.each($('ul.modele').find('li'), function(i, v) {
                    $(v).removeClass('li-active');
                });
                $(this).addClass('li-active');
            });
        }
    }





    function initAniMotorizari(ani_motorizari, next_div) {
        var anim = '<div class="motorizari"><select class="ani-motorizari"><option value="">-- alege an --</option> ';
        for(var i = ani_motorizari.an_min; i<= ani_motorizari.an_max; i++) {
            anim += '<option value="'+i+'">'+i+'</option>';
        }
        anim += '</select></div>'
        //$(ani_motorizari).appendTo($("#side-bar-motorizari"));

        console.info(anim);
        $("#" + next_div).show('slide', {}, 'slow', scrollTop).html(anim);
        $(".ani-motorizari").select2({
            width: '150px',
            margin: '0 auto'
        });
        motorizari_select();
    }
});

function combustibili_select() {
    var $combustibiliSelect = $(".combustibili-motorizari").select2({
        width: '150px',
        margin: '0 auto'
    });

    $combustibiliSelect.on("select2:open", function (e) { getMotorizari("select2:open", e); });
    $combustibiliSelect.on("select2:close", function (e) { getMotorizari("select2:close", e); });
    $combustibiliSelect.on("select2:select", function (e) { getMotorizari("select2:select", e); });
    $combustibiliSelect.on("select2:unselect", function (e) { getMotorizari("select2:unselect", e); });
    $combustibiliSelect.on("change", function (e) { getMotorizari("change"); });

    function getMotorizari (name, evt) {
        if (evt) {
            var args = JSON.stringify(evt.params, function (key, value) {
                if (value && value.nodeName) return "[DOM node]";
                if (value instanceof $.Event) return "[$.Event]";
                return value;
            });
            if(args) {
                var args_json = $.parseJSON(args);
                if(args_json.data != undefined) {
                    comb_val = args_json.data.id;
                    var dataString = {marca_id: marca_id, model_id: model_id, an_selected: an_selected, combustibil_selected: comb_val};
                    $.ajax({
                        type: "POST",
                        url: left_side_request_url,
                        data: dataString,
                        cache: false,
                        success: function(response)
                        {
                            if(response.motorizari) {
                                removeDiv('motorizari-data');
                                $("#side-bar-motorizari .slimScrollDiv").remove();
                                
                                var modele_ul = '<ul class="motorizari-data">'
                                $.each(response.motorizari, function(motorizare_id, motorizare) {
                                    modele_ul += '<li class="side-bar-motorizare-item" data-item="' + motorizare.id + '">' + motorizare.nume + '' +
                                    '<div class="info">' +
                                    ' cmc: ' + motorizare.cmc + ' ' + motorizare.kw + 'kw ('+motorizare.cp+'cp)' +
                                    ' cod motor: ' + motorizare.cod_motor + ' - ' + motorizare.caroserie +
                                    '</div></li>';
                                });
                                modele_ul += '</ul>';
                                //$("#" + next_div).show('slide', {}, 'slow', scrollTop).html(modele_ul);
                                $(modele_ul).appendTo($('#side-bar-motorizari'));
                                $('.motorizari-data').slimScroll({ height: $(window).height() - '185'+'px', step: 10 });
                            }
                        }
                    });
                }

            }

        }
    }
}

function motorizari_select() {
    var $aniSelect = $(".ani-motorizari").select2({
        width: '150px',
        margin: '0 auto'
    });

    $aniSelect.on("select2:open", function (e) { getMotorizareCombustibil("select2:open", e); });
    $aniSelect.on("select2:close", function (e) { getMotorizareCombustibil("select2:close", e); });
    $aniSelect.on("select2:select", function (e) { getMotorizareCombustibil("select2:select", e); });
    $aniSelect.on("select2:unselect", function (e) { getMotorizareCombustibil("select2:unselect", e); });
    $aniSelect.on("change", function (e) { getMotorizareCombustibil("change"); });

    function getMotorizareCombustibil (name, evt) {
        if (evt) {
            var args = JSON.stringify(evt.params, function (key, value) {
                if (value && value.nodeName) return "[DOM node]";
                if (value instanceof $.Event) return "[$.Event]";
                return value;
            });
            if(args) {
                var args_json = $.parseJSON(args);
                if(args_json.data != undefined) {
                    an_val = an_selected = args_json.data.id;
                    var dataString = {marca_id: marca_id, model_id: model_id, an_selected: an_val};
                    $.ajax({
                        type: "POST",
                        url: left_side_request_url,
                        data: dataString,
                        cache: false,
                        success: function(response)
                        {
                            if(response.combustibili) {
                                $('.combustibili-motorizari').select2("destroy");
                                removeDiv('combustibili-motorizari');
                                removeDiv('motorizari-data');
                                $("#side-bar-motorizari .slimScrollDiv").remove();


                                var comb = '<select class="combustibili-motorizari"><option value=""> -- alege carburant --</option>';
                                //$('<option value=""> -- alege carburant --</option>').appendTo($('.combustibili-motorizari'));
                                $.each(response.combustibili, function(comb_id, comb_name) {
                                    //$('<option value="'+comb_name.carburant+'">'+comb_name.carburant+'</option>').appendTo($('.combustibili-motorizari'));
                                    comb += '<option value="'+comb_name.carburant+'">'+comb_name.carburant+'</option>';
                                });
                                comb += '</select>';
                                $(comb).appendTo('.motorizari');
                                $('.combustibili-motorizari').select2({
                                    width: '150px'
                                }).removeClass('display-none');
                                combustibili_select();
                            }
                        }
                    });
                }
            }

        }
    }
}


function removeDiv(what) {
    $('.' + what).remove();
}