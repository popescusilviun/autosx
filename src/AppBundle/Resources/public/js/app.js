var marca, model, caroserie, an, combustibil, cmc, cp, tip = false;
var loadContentParams = {
    id_category : id_categorie,
    page : 1,
    rows_per_page : 1
};

var content_loaded = false;

$(function () {
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        //console.info('mobile');
    } else {
        //reload page on backward
        $(window).on('popstate', function () {
            location.reload();
        });
    }

    var $marci_select = $("#marci").select2({
        placeholder: "Marca",
        //width: '150px',
        padding: '0 10px'
    });
    var $model_select = $("#model").select2({
        placeholder: "Model",
        //allowClear: true,
        //width: '150px',
        padding: '0 10px'
    });
    var $caroserie_select = $("#caroserie").select2({
        placeholder: "Caroserie",
        //allowClear: true,
        //width: '100px',
        padding: '0 10px'
    });
    var $an_select = $("#an").select2({
        placeholder: "An fabricatie",
        //allowClear: true,
        //width: '100px',
        padding: '0 10px'
    });
    var $combustibil_select = $("#combustibil").select2({
        placeholder: "Combustibil",
        //allowClear: true,
        //width: '150px',
        padding: '0 10px'
    });
    var $cmc_select = $("#cmc").select2({
        placeholder: "Capacitate cilindrica",
        //allowClear: true,
        //width: '150px',
        padding: '0 10px'
    });
    var $cp_select = $("#cp").select2({
        placeholder: "Putere kw/cp",
        //allowClear: true,
        //width: '150px',
        padding: '0 10px'
    });
    var $tip_select = $("#tip").select2({
        placeholder: "Tip auto",
        //allowClear: true,
        width: '300px',
        margin: '0 10px'
    });

    initMarci($marci_select);
    initModel($model_select);
    initCaroserie($caroserie_select);
    initAn($an_select);
    initCombustibil($combustibil_select);
    initCmc($cmc_select);
    initCp($cp_select);
    loadContent();

    function initMarci($marci_select) {
        //$marci_select.on("select2:open", function (e) { getModele("select2:open", e); });
        $marci_select.on("select2:select", function (e) { e.preventDefault(); getModele("select2:select", e); });
        //$marci_select.on("change", function (e) { getModele("change"); });
        function getModele (name, evt) {
            if (evt && !model) {
                model = true;
                var args = JSON.stringify(evt.params, function (key, value) {
                    if (value && value.nodeName) return "[DOM node]";
                    if (value instanceof $.Event) return "[$.Event]";
                    return value;
                });
                if (args && name == 'select2:select') {
                    var args_json = $.parseJSON(args);
                    if (args_json.data != undefined) {
                        marca_id = args_json.data.id;
                        var dataString = {marca_id: marca_id, model_id: model_id};
                        ajaxRequest(dataString, $model_select, 'modele', 'model');
                    }

                }

            }

        }
    }

    function initModel($model_select) {
        //$model_select.on("select2:open", function (e) { getCaroserii("select2:open", e); });
        $model_select.on("select2:select", function (e) { e.preventDefault(); getCaroserii("select2:select", e); });
        //$model_select.on("change", function (e) { getCaroserii("change"); });
        function getCaroserii (name, evt) {
            if (evt && !caroserie) {
                caroserie = true;
                var args = JSON.stringify(evt.params, function (key, value) {
                    if (value && value.nodeName) return "[DOM node]";
                    if (value instanceof $.Event) return "[$.Event]";
                    return value;
                });
                if(args && name == 'select2:select') {
                    var args_json = $.parseJSON(args);
                    if(args_json.data != undefined) {
                        model_id = args_json.data.id;
                        var dataString = {marca_id: marca_id, model_id: model_id};
                        ajaxRequest(dataString, $caroserie_select, 'caroserii', 'caroserie');
                    }
                }
            }
        }
    }

    function initCaroserie($caroserie_select) {
        //$caroserie_select.on("select2:open", function (e) { getAni("select2:open", e); });
        $caroserie_select.on("select2:select", function (e) { e.preventDefault(); getAni("select2:select", e); });
        //$caroserie_select.on("change", function (e) { getAni("change"); });
        function getAni (name, evt) {
            if (evt && !an) {
                an = true;
                var args = JSON.stringify(evt.params, function (key, value) {
                    if (value && value.nodeName) return "[DOM node]";
                    if (value instanceof $.Event) return "[$.Event]";
                    return value;
                });
                if(args && name == 'select2:select') {
                    var args_json = $.parseJSON(args);
                    if(args_json.data != undefined) {
                        caroserie_selected = args_json.data.id;
                        var dataString = {marca_id: marca_id, model_id: model_id, caroserie_selected: caroserie_selected};
                        ajaxRequest(dataString, $an_select, 'ani_motorizari', 'an');
                    }
                }
            }
        }
    }

    function initAn($an_select) {
        //$an_select.on("select2:open", function (e) { getCombustibili("select2:open", e); });
        $an_select.on("select2:select", function (e) { e.preventDefault(); getCombustibili("select2:select", e); });
        //$an_select.on("change", function (e) { e.preventDefault(); getCombustibili("change"); });
        function getCombustibili (name, evt) {
            if (evt && !combustibil) {
                combustibil = true;
                var args = JSON.stringify(evt.params, function (key, value) {
                    if (value && value.nodeName) return "[DOM node]";
                    if (value instanceof $.Event) return "[$.Event]";
                    return value;
                });
                if(args && name == 'select2:select') {
                    var args_json = $.parseJSON(args);
                    if(args_json.data != undefined) {
                        an_selected = args_json.data.id;
                        var dataString = {marca_id: marca_id, model_id: model_id, caroserie_selected: caroserie_selected, an_selected: an_selected};
                        ajaxRequest(dataString, $combustibil_select, 'combustibili', 'combustibil');
                    }
                }
            }
        }
    }

    function initCombustibil($combustibil_select) {
        //$combustibil_select.on("select2:open", function (e) { getCmcs("select2:open", e); });
        $combustibil_select.on("select2:select", function (e) { e.preventDefault(); getCmcs("select2:select", e); });
        //$combustibil_select.on("change", function (e) { e.preventDefault(); getCmcs("change"); });
        function getCmcs (name, evt) {
            if (evt && !cmc) {
                cmc = true;
                var args = JSON.stringify(evt.params, function (key, value) {
                    if (value && value.nodeName) return "[DOM node]";
                    if (value instanceof $.Event) return "[$.Event]";
                    return value;
                });
                if(args && name == 'select2:select') {
                    var args_json = $.parseJSON(args);
                    if(args_json.data != undefined) {
                        combustibil_selected = args_json.data.id;
                        var dataString = {
                            marca_id: marca_id,
                            model_id: model_id,
                            caroserie_selected: caroserie_selected,
                            an_selected: an_selected,
                            combustibil_selected: combustibil_selected
                        };
                        ajaxRequest(dataString, $cmc_select, 'cmcs', 'cmc');
                    }
                }
            }
        }
    }

    function initCmc($cmc_select) {
        //$cmc_select.on("select2:open", function (e) { getCp("select2:open", e); });
        $cmc_select.on("select2:select", function (e) { e.preventDefault(); getCp("select2:select", e); });
        //$cmc_select.on("change", function (e) { e.preventDefault(); getCp("change"); });
        function getCp (name, evt) {
            if (evt && !cp) {
                cp = true;
                var args = JSON.stringify(evt.params, function (key, value) {
                    if (value && value.nodeName) return "[DOM node]";
                    if (value instanceof $.Event) return "[$.Event]";
                    return value;
                });
                if(args && name == 'select2:select') {
                    var args_json = $.parseJSON(args);
                    if(args_json.data != undefined) {
                        cmc_selected = args_json.data.id;
                        var dataString = {
                            marca_id: marca_id,
                            model_id: model_id,
                            caroserie_selected: caroserie_selected,
                            an_selected: an_selected,
                            combustibil_selected: combustibil_selected,
                            cmc_selected: cmc_selected
                        };
                        ajaxRequest(dataString, $cp_select, 'puteri', 'cp');
                    }
                }
            }
        }
    }

    function initCp($cp_select) {
        //$cp_select.on("select2:open", function (e) { getTipuri("select2:open", e); });
        $cp_select.on("select2:select", function (e) { e.preventDefault(); getTipuri("select2:select", e); });
        //$cp_select.on("change", function (e) { e.preventDefault(); getTipuri("change"); });
        function getTipuri (name, evt) {
            if (evt && !tip) {
                tip = true;
                var args = JSON.stringify(evt.params, function (key, value) {
                    if (value && value.nodeName) return "[DOM node]";
                    if (value instanceof $.Event) return "[$.Event]";
                    return value;
                });
                if(args) {
                    var args_json = $.parseJSON(args);
                    if(args_json.data != undefined) {
                        putere_selected = args_json.data.id;
                        var dataString = {
                            marca_id: marca_id,
                            model_id: model_id,
                            caroserie_selected: caroserie_selected,
                            an_selected: an_selected,
                            combustibil_selected: combustibil_selected,
                            cmc_selected: cmc_selected,
                            putere_selected: putere_selected
                        };
                        ajaxRequest(dataString, $tip_select, 'motorizari', 'tip');
                    }
                }
            }
        }
    }

    function ajaxRequest(dataString, object_to_change, response_obj, options_to_remove) {
        $.ajax({
            type: "POST",
            url: car_selection_request_url,
            data: dataString,
            cache: false,
            success: function(response)
            {
                if(response[response_obj]) {
                    if(typeof options_to_remove != 'null') {
                        $('#' + options_to_remove + ' option').remove();
                    }

                    var options = '<option value=""></option>';
                    $.each(response[response_obj], function(id, value) {
                        options += '<option value="' + value.id + '">' + value.nume + '</option>';
                    });



                    if(options_to_remove == 'model') {
                        model = false;
                        //initModel($model_select);
                        $('#model option').remove(); $('#caroserie option').remove(); $('#an option').remove(); $('#comustibil option').remove(); $('#cmc option').remove(); $('#cp option').remove(); $('#tip option').remove();
                        $("#model").empty().trigger('change'); $("#caroserie").empty().trigger('change'); $("#an").empty().trigger('change'); $("#combustibil").empty().trigger('change'); $("#cmc").empty().trigger('change'); $("#cp").empty().trigger('change'); $("#tip").empty().trigger('change');
                        $("#caroserie").prop("disabled", true);
                        $("#an").prop("disabled", true);
                        $("#combustibil").prop("disabled", true);
                        $("#cmc").prop("disabled", true);
                        $("#cp").prop("disabled", true);
                        $("#tip").prop("disabled", true);
                    }
                    if(options_to_remove == 'caroserie') {
                        caroserie = false;
                        //initCaroserie($caroserie_select);
                        $('#caroserie option').remove(); $('#an option').remove(); $('#comustibil option').remove(); $('#cmc option').remove(); $('#cp option').remove(); $('#tip option').remove();
                        $("#caroserie").empty().trigger('change'); $("#an").empty().trigger('change'); $("#combustibil").empty().trigger('change'); $("#cmc").empty().trigger('change'); $("#cp").empty().trigger('change'); $("#tip").empty().trigger('change');
                        $("#caroserie").prop("disabled", true);
                        $("#an").prop("disabled", true);
                        $("#combustibil").prop("disabled", true);
                        $("#cmc").prop("disabled", true);
                        $("#cp").prop("disabled", true);
                        $("#tip").prop("disabled", true);
                    }
                    if(options_to_remove == 'an') {
                        an = false;
                        //initAn($an_select);
                        $('#an option').remove(); $('#comustibil option').remove(); $('#cmc option').remove(); $('#cp option').remove(); $('#tip option').remove();
                        $("#an").empty().trigger('change'); $("#combustibil").empty().trigger('change'); $("#cmc").empty().trigger('change'); $("#cp").empty().trigger('change'); $("#tip").empty().trigger('change');

                        $("#an").prop("disabled", true);
                        $("#combustibil").prop("disabled", true);
                        $("#cmc").prop("disabled", true);
                        $("#cp").prop("disabled", true);
                        $("#tip").prop("disabled", true);
                    }
                    if(options_to_remove == 'combustibil') {
                        combustibil = false;
                        //initCombustibil($combustibil_select);
                        $('#comustibil option').remove(); $('#cmc option').remove(); $('#cp option').remove(); $('#tip option').remove();
                        $("#combustibil").empty().trigger('change'); $("#cmc").empty().trigger('change'); $("#cp").empty().trigger('change'); $("#tip").empty().trigger('change');

                        $("#combustibil").prop("disabled", true);
                        $("#cmc").prop("disabled", true);
                        $("#cp").prop("disabled", true);
                        $("#tip").prop("disabled", true);
                    }
                    if(options_to_remove == 'cmc') {
                        cmc = false;
                        //initCmc($cmc_select);
                        $('#cmc option').remove(); $('#cp option').remove(); $('#tip option').remove();
                        $("#cmc").empty().trigger('change'); $("#cp").empty().trigger('change'); $("#tip").empty().trigger('change');

                        $("#cmc").prop("disabled", true);
                        $("#cp").prop("disabled", true);
                        $("#tip").prop("disabled", true);
                    }
                    if(options_to_remove == 'cp') {
                        cp = false;
                        //initCp($cp_select);
                        $('#cp option').remove(); $('#tip option').remove();
                        $("#cp").empty().trigger('change'); $("#tip").empty().trigger('change');

                        $("#cp").prop("disabled", true);
                        $("#tip").prop("disabled", true);
                    }
                    if(options_to_remove == 'tip') {
                        tip = false;
                        $('#tip option').remove();
                        $("#tip").empty().trigger('change');
                        $("#tip").prop("disabled", true);
                    }
                    $(options).appendTo(object_to_change);
                    object_to_change.prop("disabled", false);
                    object_to_change.select2("open");
                }
            }
        });
    }

    $(".level-0, .level-1, .level-2").on("click", function(e) {
        var next_level = '';
        if(e.currentTarget.classList.contains('level-0')) {
            next_level = 'level-1';
        } else if(e.currentTarget.classList.contains('level-1')) {
            next_level = 'level-2';
        }

        var lvl = $(this).data('cat');

        if(next_level != '') {
            $("." + next_level).hide('blind', {}, 'fast');
            $('.open-' + lvl).show('blind', {}, 'normal');
        }
        id_categorie = loadContentParams.id_category = lvl;
        content_loaded = false;

        var path = window.location.pathname;
        var new_path = path.split('/');
        var count_path = new_path.length;
        if($.isNumeric(Math.floor(new_path[count_path-1]))) {
            path = path.replace('/'+new_path[count_path-1], '/'+lvl);
            window.history.pushState(path, "Piese auto ", path);
        } else {
            path += '/'+id_categorie;
            window.history.pushState(path, "Piese auto ", path);
        }

        loadContent();
    });


    function loadContent() {
        if(!content_loaded) {
            function loading_show(){
                $('.main-content').css("opacity", '.5').fadeIn('fast');
            }
            function loading_hide(){
                $('.main-content').css("opacity", '1').fadeOut('fast');
            }
            loading_hide();
            function loadData(){
                loading_show();
                if(id_categorie != false) {
                    $('.open-' + id_categorie).show('blind', {}, 'normal');
                }
                if (parent_category != false) {
                    $('.open-' + parent_category).show('blind', {}, 'normal');
                }
                $('.main-content').load('/app_dev.php/load-products', loadContentParams, function () {
                    content_loaded = true;
                });
            }
            loadData();  // For first time page load default results
            $('#container .pagination li.active').on('click',function(){
                var page = $(this).attr('p');
                loadContentParams.page = page;
                loadData();

            });
            $('#go_btn').on('click',function() {
                var page = parseInt($('.goto').val());
                var no_of_pages = parseInt($('.total').attr('a'));
                if (page != 0 && page <= no_of_pages) {
                    loadContentParams.page = page;
                    loadData();
                } else {
                    alert('Enter a PAGE between 1 and ' + no_of_pages);
                    $('.goto').val("").focus();
                    return false;
                }

            });


        }

    }

});



