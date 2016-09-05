//main.js
jQuery(document).ready(function() {
        tinymce.init({
            selector: '.tinymce',
            height: 350,
            directionality : 'ltr',
            plugins: [
                "autolink link image lists hr anchor",
                "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                "table contextmenu directionality paste, responsivefilemanager, imagetools, fullscreen"
            ],
            toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
            toolbar2: "| responsivefilemanager | link unlink anchor | image media | fullscreen",
            image_advtab: true ,
            imagetools_cors_hosts: ['localhost', 'codepen.io'],
            //imagetools_toolbar: "rotateleft rotateright | flipv fliph | editimage imageoptions",
            image_caption: true,
            style_formats: [
                {title: 'Image Left', selector: 'img', styles: {
                    'float' : 'left',
                    'margin': '0 10px 0 10px'
                }},
                {title: 'Image Right', selector: 'img', styles: {
                    'float' : 'right',
                    'margin': '0 10px 0 10px'
                }}
            ],
            content_css: [
                '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
                '//www.tinymce.com/css/codepen.min.css'
            ],
            relative_urls: false,
            external_filemanager_path:"/filemanager/",
            filemanager_title:"Responsive Filemanager" ,
            external_plugins: {"filemanager" : "/filemanager/plugin.min.js"}
        });

	//inicia o plugin de opções de página
	$(".post-sidebar-options").postStatus();

    //Inicia o plugin que controla o slug
    $(".slug-container").slug();

    $(".post-list").postListStatus();

    $('.post-sites').postSites();

    $("form.default-form-actions").formSave();

    //Inicia o plugin que controla a busca de cep
    $(".cep").cep();

    //Inicia o plugin que controla a busca de cidades
    $(".state-cities").cities();

    //gerador de password
    $(".password-generator").passwordGenerator();

	if (jQuery().datepicker) {
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            todayBtn: true,
            autoclose: true,
            language: "pt-BR",
            todayHighlight: true
        });
    }

    if (jQuery().timepicker) {
        $('.time-picker').timepicker({
            defaultTime: false,
            showMeridian: false
        });
    }

    $("form.enable-validators").formValidation();

    $('.dd').nestable();

    $( "#sortable_banner" ).sortable({
        items: ".banner-item", 
        opacity: 0.8,
        handle : '.portlet-title',
        coneHelperSize: true,
        //placeholder: 'portlet-sortable-placeholder col-md-3',
        //forcePlaceholderSize: true,
        
    });

    $( ".gallery-items" ).sortable({
        items: ".gallery-item", 
        opacity: 0.8,
        coneHelperSize: true,
        handle : '.gallery-item-move',
        //placeholder: 'portlet-sortable-placeholder col-md-3',
        //forcePlaceholderSize: true,
        
    });

    $('select.multi-select').multiSelect({
        selectableOptgroup: true
    });
    
    $.fn.select2.defaults.set("theme", "bootstrap");
    $('select.select2').select2({
        placeholder: 'Selecione'
    });
    $(".select2, .select2-multiple, .select2-allow-clear, .js-data-example-ajax").on("select2:open", function() {
        if ($(this).parents("[class*='has-']").length) {
            var classNames = $(this).parents("[class*='has-']")[0].className.split(/\s+/);

            for (var i = 0; i < classNames.length; ++i) {
                if (classNames[i].match("has-")) {
                    $("body > .select2-container").addClass(classNames[i]);
                }
            }
        }
    });

    $.fn.postThumb();

    $.fn.selectImage();

    $('.responsivefilemanager').modalResponsiveFileManager();

    $('.page-gallery').banner();

    $('div#admin-menu').menu();

    $('.fileinput').fileInput();
});