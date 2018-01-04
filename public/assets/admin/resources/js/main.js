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
                '//www.tinymce.com/css/codepen.min.css'
            ],
            relative_urls: false,
            remove_script_host : false,
            external_filemanager_path:"/filemanager/",
            filemanager_title:"Responsive Filemanager" ,
            external_plugins: {"filemanager" : "/filemanager/plugin.min.js"}
        });

        tinymce.init({
            selector: '.tinymce_minimal',
            height: 250,
            directionality : 'ltr',
            plugins: [
                "autolink link lists hr anchor",
                "searchreplace wordcount visualblocks visualchars insertdatetime nonbreaking",
                "table contextmenu directionality paste, fullscreen"
            ],
            toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist",
            toolbar2: "link unlink anchor | fullscreen",
            content_css: [
                '//www.tinymce.com/css/codepen.min.css'
            ],
            relative_urls: false,
            remove_script_host : false
        });

    $(":input").inputmask();

	//inicia o plugin de opções de página
	$(".post-sidebar-options").postStatus();

    //Inicia o plugin que controla o slug
    $(".slug-container").slug();

    $(".post-list").postListStatus();

    $('.post-sites').postSites();

    $('.report-link').report();

    $("form.default-form-actions").formSave();

    //Inicia o plugin que controla a busca de cep
    $(".cep").cep();

    //Inicia o plugin que controla a busca de cidades
    $(".state-cities").cities();

    //gerador de password
    $(".password-generator").passwordGenerator();

    $(".programing-table").programing();

    $(".workshop-pontuation").workshopPontuation();

    //$(".nav-tabs li a").tabSelection();

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
            autoclose: true,
            minuteStep: 5,
            showSeconds: false,
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
    $('select.select2, .select2-multiple').select2({
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

    $('.fileinput2').fileInput();

    $('.list-actions').listActions();

    $('.admin-phone').adminPhone();

    $('.user-modal').user();

    $('.image-collection').imageCollection();

    $('#user-dependents').adminDependents();

    $('#post-url-btn').on('click', function(e) {
        e.preventDefault();
        if(copyToClipboard(document.getElementById("post-url"))) {

        }
    });
    new Clipboard('.data-copy');

    $('.registration-form select[name=type]').on('change', function(e) {
        var selected = $(this).find('option:selected').val();
        var form  = $('#registration-form');

        form.append($('<input type="hidden" name="no-validate" value="no-validate">'));
        form.submit();

        App.blockUI({
            cenrerY: true,
            animate: true
        });
    });

    $('.movie-form #registration').on('change', function() {
        var form = $('.movie-form'),
            validate = form.validate();

        validate.destroy();

        form.append($('<input type="hidden" name="no-validate" value="no-validate">'));
        form.submit();

        App.blockUI({
            cenrerY: true,
            animate: true
        });
    });
});

function copyToClipboard(elem) {
      // create hidden text element, if it doesn't already exist
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        // can just use the original source element for the selection and copy
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        // must use a temporary form element for the selection and copy
        target = document.getElementById(targetId);
        if (!target) {
            var target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }
    // select the content
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    
    // copy the selection
    var succeed;
    try {
          succeed = document.execCommand("copy");
    } catch(e) {
        succeed = false;
    }
    // restore original focus
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }
    
    if (isInput) {
        // restore prior selection
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        // clear temporary content
        target.textContent = "";
    }
    return succeed;
}