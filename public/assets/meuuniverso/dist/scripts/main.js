"use strict";var App=function(){var e=function(){$("body").on("click",".portlet > .portlet-title > .tools > a.remove",function(e){e.preventDefault();var t=$(this).closest(".portlet");$("body").hasClass("page-portlet-fullscreen")&&$("body").removeClass("page-portlet-fullscreen"),t.find(".portlet-title .fullscreen").tooltip("destroy"),t.find(".portlet-title > .tools > .reload").tooltip("destroy"),t.find(".portlet-title > .tools > .remove").tooltip("destroy"),t.find(".portlet-title > .tools > .config").tooltip("destroy"),t.find(".portlet-title > .tools > .collapse, .portlet > .portlet-title > .tools > .expand").tooltip("destroy"),t.remove()}),$("body").on("click",".portlet > .portlet-title .fullscreen",function(e){e.preventDefault();var t=$(this).closest(".portlet");if(t.hasClass("portlet-fullscreen"))$(this).removeClass("on"),t.removeClass("portlet-fullscreen"),$("body").removeClass("page-portlet-fullscreen"),t.children(".portlet-body").css("height","auto");else{var a=App.getViewPort().height-t.children(".portlet-title").outerHeight()-parseInt(t.children(".portlet-body").css("padding-top"))-parseInt(t.children(".portlet-body").css("padding-bottom"));$(this).addClass("on"),t.addClass("portlet-fullscreen"),$("body").addClass("page-portlet-fullscreen"),t.children(".portlet-body").css("height",a)}}),$("body").on("click",".portlet > .portlet-title > .tools > .collapse, .portlet .portlet-title > .tools > .expand",function(e){e.preventDefault();var t=$(this).closest(".portlet").children(".portlet-body");$(this).hasClass("collapse")?($(this).removeClass("collapse").addClass("expand"),t.slideUp(200)):($(this).removeClass("expand").addClass("collapse"),t.slideDown(200))})};return{init:function(){e()},scrollTo:function(e,t){var a=e&&e.size()>0?e.offset().top:0;e&&($("body").hasClass("page-header-fixed")?a-=$(".page-header").height():$("body").hasClass("page-header-top-fixed")?a-=$(".page-header-top").height():$("body").hasClass("page-header-menu-fixed")&&(a-=$(".page-header-menu").height()),a+=t?t:-1*e.height()),$("html,body").animate({scrollTop:a},"slow")},blockUI:function(e){e=$.extend(!0,{},e);var t="";if(t=e.animate?'<div class="loading-message '+(e.boxed?"loading-message-boxed":"")+'"><div class="block-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>':e.iconOnly?'<div class="loading-message '+(e.boxed?"loading-message-boxed":"")+'"><img src="'+this.getGlobalImgPath()+'loading-spinner-grey.gif" align=""></div>':e.textOnly?'<div class="loading-message '+(e.boxed?"loading-message-boxed":"")+'"><span>&nbsp;&nbsp;'+(e.message?e.message:"LOADING...")+"</span></div>":'<div class="loading-message '+(e.boxed?"loading-message-boxed":"")+'"><img src="'+this.getGlobalImgPath()+'loading-spinner-grey.gif" align=""><span>&nbsp;&nbsp;'+(e.message?e.message:"LOADING...")+"</span></div>",e.target){var a=$(e.target);a.height()<=$(window).height()&&(e.cenrerY=!0),a.block({message:t,baseZ:e.zIndex?e.zIndex:1e3,centerY:void 0!==e.cenrerY&&e.cenrerY,css:{top:"10%",border:"0",padding:"0",backgroundColor:"none"},overlayCSS:{backgroundColor:e.overlayColor?e.overlayColor:"#555",opacity:e.boxed?.05:.1,cursor:"wait"}})}else $.blockUI({message:t,baseZ:e.zIndex?e.zIndex:1e3,css:{border:"0",padding:"0",backgroundColor:"none"},overlayCSS:{backgroundColor:e.overlayColor?e.overlayColor:"#555",opacity:e.boxed?.05:.1,cursor:"wait"}})},unblockUI:function(e){e?$(e).unblock({onUnblock:function(){$(e).css("position",""),$(e).css("zoom","")}}):$.unblockUI()},getViewPort:function(){var e=window,t="inner";return"innerWidth"in window||(t="client",e=document.documentElement||document.body),{width:e[t+"Width"],height:e[t+"Height"]}}}}();jQuery(document).ready(function(){App.init()});var FormWizard=function(){return{init:function(){if(jQuery().bootstrapWizard){var e=$("#submit_form"),t=$(".alert-danger",e),a=$(".alert-success",e);e.validate({doNotHideMessage:!0,errorElement:"span",errorClass:"help-block help-block-error",focusInvalid:!1,rules:{media_file_1:{required:!0,extension:"jpg,jpeg,png",filesize:5},accept_regulation:{required:!0},"events[]":{required:!0,minlength:1},movie_link:{required:!0}},errorPlacement:function(e,t){"gender"==t.attr("name")?e.insertAfter("#form_gender_error"):"payment[]"==t.attr("name")?e.insertAfter("#form_payment_error"):e.insertAfter(t)},invalidHandler:function(e,o){a.hide(),t.show(),App.scrollTo(t,-200)},highlight:function(e){$(e).closest(".form-group").removeClass("has-success").addClass("has-error")},unhighlight:function(e){$(e).closest(".form-group").removeClass("has-error")},success:function(e){"gender"==e.attr("for")||"payment[]"==e.attr("for")?(e.closest(".form-group").removeClass("has-error"),e.remove()):e.addClass("valid").closest(".form-group").removeClass("has-error")},submitHandler:function(e){a.show(),t.hide()}});var o=function(){$("#tab4 .form-control-static",e).each(function(){var t=$('[name="'+$(this).attr("data-display")+'"]',e);if(t.is(":radio")&&(t=$('[name="'+$(this).attr("data-display")+'"]:checked',e)),t.is(":text")||t.is("textarea"))$(this).html(t.val());else if(t.is("select"))$(this).html(t.find("option:selected").text());else if(t.is(":radio")&&t.is(":checked"))$(this).html(t.attr("data-title"));else if("payment[]"==$(this).attr("data-display")){var a=[];$('[name="payment[]"]:checked',e).each(function(){a.push($(this).attr("data-title"))}),$(this).html(a.join("<br>"))}})},r=function(e,t,a){var r=t.find("li").length,s=a+1;$(".step-title",$("#form_wizard_1")).text("Step "+(a+1)+" of "+r),jQuery("li",$("#form_wizard_1")).removeClass("done");for(var i=t.find("li"),n=0;n<a;n++)jQuery(i[n]).addClass("done");1==s?$("#form_wizard_1").find(".button-previous").hide():$("#form_wizard_1").find(".button-previous").show(),s>=r?($("#form_wizard_1").find(".button-next").hide(),$("#form_wizard_1").find(".button-submit").show(),o()):($("#form_wizard_1").find(".button-next").show(),$("#form_wizard_1").find(".button-submit").hide()),App.scrollTo($(".page-title"))};$("#form_wizard_1").bootstrapWizard({nextSelector:".button-next",previousSelector:".button-previous",onTabClick:function(e,t,a,o){return!1},onNext:function(o,s,i){return a.hide(),t.hide(),0!=e.valid()&&void r(o,s,i)},onPrevious:function(e,o,s){a.hide(),t.hide(),r(e,o,s)},onTabShow:function(e,t,a){var o=t.find("li").length,r=a+1,s=r/o*100;$("#form_wizard_1").find(".progress-bar").css({width:s+"%"})}}),$("#form_wizard_1").find(".button-previous").hide(),$("#form_wizard_1 .button-submit").click(function(){return 0!=e.valid()&&void document.getElementById("submit_form").submit()}).hide()}}}}();jQuery(document).ready(function(){FormWizard.init()}),$(document).ready(function(){$('.movie-form select[name="has_cpb"]').on("change",function(e){var t=$(this).children("option:selected").val(),a=$("#has_cpb"),o=$('.movie-form input[name="cpb"]');1==t?(a.show(),o.attr("required","required")):(a.hide(),o.removeAttr("required"),o.val(""))}),$('.movie-form select[name="options[classification]"]').on("change",function(e){var t=$(this).children("option:selected").text(),a=$("#content_scenes"),o=$('.movie-form textarea[name="content_scenes"]'),r=$('.movie-form select[name="has_official_classification"] option:selected').val();"livre"!=t.toLowerCase()&&"selecione"!=t.toLowerCase()&&"0"===r?(a.show(),o.attr("required","required")):(a.hide(),o.removeAttr("required"),o.val(""))}),$('.movie-form select[name="has_participated_other_festivals"]').on("change",function(e){var t=$(this).children("option:selected").val(),a=$("#other_festivals"),o=$('.movie-form textarea[name="other_festivals"]');1==t?a.show():(a.hide(),o.val(""))}),$('.movie-form select[name="has_conversations_languages"]').on("change",function(e){var t=$(this).children("option:selected").val(),a=$("#conversations_languages"),o=$('.movie-form textarea[name="conversations_languages"]');1==t?a.show():(a.hide(),o.val(""))}),$('.movie-form select[name="has_subtitles_languages"]').on("change",function(e){var t=$(this).children("option:selected").val(),a=$("#subtitles_languages"),o=$('.movie-form textarea[name="subtitles_languages"]');1==t?a.show():(a.hide(),o.val(""))}),$('.movie-form select[name="has_conversations_list_languages"]').on("change",function(e){var t=$(this).children("option:selected").val(),a=$("#conversations_list_languages"),o=$('.movie-form textarea[name="conversations_list_languages"]');1==t?a.show():(a.hide(),o.val(""))}),$('.movie-form select[name="has_official_classification"]').on("change",function(e){var t=$(this).children("option:selected").val(),a=$("#classification"),o=$("#option_classification"),r=$('label[for="option_classification"]'),s=a.find(".help-block");"1"===t?(a.show(),r.text(o.data("oficial-classification")).append('<span class="required" aria-required="true"> * </span>'),o.val(""),o.trigger("change"),s.hide()):"0"===t?(a.show(),r.text(o.data("suggest-classification")).append('<span class="required" aria-required="true"> * </span>'),o.val(""),o.trigger("change"),s.show()):(o.trigger("change"),a.hide(),o.val(""))})}),$(document).ready(function(){var e={errorClass:"has-error",classHandler:function(e){return e.$element.closest(".form-group")},errorsWrapper:'<span class="help-block"></span>',errorTemplate:"<span></span>"};$("form[data-js-validate]").parsley(e),jQuery().datepicker&&$(".date-picker").datepicker({todayBtn:!0,autoclose:!0,language:"pt-BR",todayHighlight:!0}),$(":input").inputmask(),$(".cep").cep(),$(".state-cities").cities(),$(".admin-phone").adminPhone(),$("#user-dependents").adminDependents(),jQuery().bootstrapWizard&&$(".scroller").each(function(){if(!$(this).attr("data-initialized")){var e;e=$(this).attr("data-height")?$(this).attr("data-height"):$(this).css("height"),$(this).slimScroll({allowPageScroll:!0,size:"7px",color:$(this).attr("data-handle-color")?$(this).attr("data-handle-color"):"#bbb",wrapperClass:$(this).attr("data-wrapper-class")?$(this).attr("data-wrapper-class"):"slimScrollDiv",railColor:$(this).attr("data-rail-color")?$(this).attr("data-rail-color"):"#eaeaea",position:"right",height:e,alwaysVisible:"1"==$(this).attr("data-always-visible"),railVisible:"1"==$(this).attr("data-rail-visible"),disableFadeOut:!0}),$(this).attr("data-initialized","1")}})}),window.Parsley.addValidator("uppercase",{requirementType:"number",validateString:function(e,t){var a=e.match(/[A-Z]/g)||[];return a.length>=t},messages:{en:"Your password must contain at least (%s) uppercase letter.","pt-br":"É necessário conter ao menos %s letra maiúscula"}}),window.Parsley.addValidator("lowercase",{requirementType:"number",validateString:function(e,t){var a=e.match(/[a-z]/g)||[];return a.length>=t},messages:{en:"Your password must contain at least (%s) lowercase letter.","pt-br":"É necessário conter ao menos %s letra minúscula."}}),window.Parsley.addValidator("number",{requirementType:"number",validateString:function(e,t){var a=e.match(/[0-9]/g)||[];return a.length>=t},messages:{en:"Your password must contain at least (%s) number.","pt-br":"É ncessário conter ao menos %s número."}}),window.Parsley.addValidator("special",{requirementType:"string",validateString:function(e,t){var a=e.match(/[a-zA-Z]/g)||[];return a.length>=t},messages:{en:"Your password must contain at least %s characters.","pt-br":"É ncessário conter ao menos %s letra."}}),window.Parsley.addValidator("char",{requirementType:"string",validateString:function(e,t){var a=e.match(/[^a-zA-Z0-9]/g)||[];return a.length>=t},messages:{en:"Your password must contain at least (%s) special characters.","pt-br":"É ncessário conter ao menos %s caracter especial."}});