an_val = comb_val = false;
$(function() {
    $('.marci').slimScroll({height: $(window).height() - '105' + 'px', step: 10});
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        //console.info('mobile');
    } else {
        //reload page on backward
        $(window).on('popstate', function () {
            location.reload();
        });
    }

    if(marca_id != null || marca_id != undefined) { initializeNextDivItems('side-bar-model-item', 'side-bar-motorizari'); }

    //initializare modele la click pe marca
    if($('.side-bar-marca-item-click') != 'undefined') {
        $(".side-bar-marca-item-click").on("click", function(e) {
            e.preventDefault();
            marca_id = $(this).parent().data('item');
            if(typeof marca_id != 'undefined' && typeof marca_id != 'null') {
                $("#side-bar-motorizari").hide('slide', {}, 'normal', ajaxLeftSideRequest(/*next_div*/'side-bar-model-item-'+marca_id, marca_id, null, 'modele')).html('');
            }
            $.each($('ul.marci').find('.side-bar-marca-item-click'), function(i, v) {
                $(v).removeClass('li-active');
            });
            $(this).addClass('li-active');
        });
    }

    function ajaxLeftSideRequest(next_div, marca_id, model_id, div_identifier) {
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
                    if(div_identifier !== null && div_identifier == 'modele') {
                        $("ul.side-bar-modele-items").empty('blind', {}, 'slow', scrollTop);
                        var modele_ul = '';

                        $.each(response.modele, function(model_id, model) {
                            modele_ul += '<li class="side-bar-model-item" data-item="' + model.id + '">' + model.nume + '</li>';
                        });

                        $("#" + next_div).show('blind', {}, 'slow', scrollTop).html(modele_ul);
                        initializeNextDivItems('side-bar-model-item', 'side-bar-motorizari');
                        window.history.pushState(response.modele, "Piese auto " + response.marca.nume, "/app_dev.php/piese-auto/" + response.marca.slug);
                        document.title = "Piese auto " + response.marca.nume;
                        $('.marci').animate({
                            scrollTop: 0
                        }, 'slow');
                    }


                    if(next_div == 'side-bar-motorizari' && response.motorizari) {
                        if(typeof response.motorizari !== 'undefined') {
                            console.info(response.motorizari);
                            /*$(".motorizari-data").html('');
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
                             $(modele_ul).appendTo(".motorizari-data");*/
                            $('.motorizari').slimScroll({
                                height: $(window).height() - '105' + 'px',
                                step: 10
                            });
                        }


                        //var modele_ul = '<ul class="motorizari-data">';
                        /*if(response.ani_motorizari != undefined) {
                            $(".ani-motorizari ").remove();
                            $(".combustibili-motorizari ").remove();
                            initAniMotorizari(response.ani_motorizari, next_div);
                        } else {*/



                        //}
                        window.history.pushState(response.motorizari, "Piese auto " + response.marca.nume + " " + response.model.nume, "/app_dev.php/piese-auto/" + response.marca.slug + "-" + response.model.slug);
                        document.title = "Piese auto " + response.marca.nume + " " + response.model.nume;
                    }
                    clearTimeout(timeOut);
                }
            });
        }, 500);
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

    function scrollTop() {
        $('html').animate({
            scrollTop: 0
        }, 'slow');
    }
});