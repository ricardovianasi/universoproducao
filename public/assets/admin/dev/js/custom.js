(function(window, $) {
    var Cep = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    Cep.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            _that.$element.find("#searchcep").on("click", function(e) {
                e.preventDefault();
                var cepValue = _that.$element.find('input[name="cep"]').val();
                if (!cepValue) {
                    return;
                }
                App.blockUI({
                    cenrerY: true,
                    animate: true
                });
                $.ajax({
                    type: "POST",
                    url: _that.config.urlCep,
                    data: "cep=" + cepValue,
                    success: function(data) {
                        if (data.error) {
                            _that.erroMessage(data.error);
                        } else {
                            $(_that.config.form + ' select[name="state"]').val(data.cep.state);
                            $(_that.config.form + ' select[name="city"]').empty().append(data.cep.cities).val(data.cep.city);
                            $(_that.config.form + ' input[name="address"]').val(data.cep.address);
                            $(_that.config.form + ' input[name="district"]').val(data.cep.district);
                            App.unblockUI();
                        }
                    },
                    error: function() {
                        _that.erroMessage("Não foi possível localizar o cep informado. Por favor, tente novamente ou informe o endereço manualmente.");
                    }
                });
            });
        },
        erroMessage: function(msg) {
            bootbox.dialog({
                message: msg,
                title: "Atenção",
                buttons: {
                    success: {
                        label: "OK",
                        className: "blue",
                        callback: function() {
                            App.unblockUI();
                        }
                    }
                }
            });
        }
    };
    Cep.defaults = Cep.prototype.defaults;
    $.fn.cep = function(options) {
        return this.each(function() {
            new Cep(this, options).init();
        });
    };
    window.Cep = Plugin;
})(window, jQuery);

(function(window, $) {
    var Banner = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    Banner.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            _that.$element.find(_that.config.input).on("change", function(e) {
                var val = $(this).val();
                console.log(val);
                if (!val) {
                    return;
                }
                App.blockUI({
                    cenrerY: true,
                    animate: true
                });
                $.ajax({
                    type: "POST",
                    url: _that.config.url,
                    data: "media=" + val,
                    success: function(data) {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            _that.$element.find(".gallery-items").append(data.item);
                            $(".icheck").iCheck({
                                checkboxClass: "icheckbox_minimal-grey"
                            });
                            App.unblockUI();
                        }
                    },
                    error: function() {
                        alert("Não foi inserir um novo item. Por favor, tente novamente.");
                    }
                });
            });
            console.log(_that.element);
            $(document).on("click", ".gallery-item-action-remove", function(e) {
                e.preventDefault();
                var target = $(this).closest("tr");
                target.css({
                    "border-color": "red",
                    background: "#FFB5B5"
                });
                target.fadeOut("slow", function() {
                    target.remove();
                });
            });
        }
    };
    Banner.defaults = Banner.prototype.defaults;
    $.fn.banner = function(options) {
        return this.each(function() {
            new Banner(this, options).init();
        });
    };
    window.Banner = Plugin;
})(window, jQuery);

(function(window, $) {
    var ICheckControl = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    ICheckControl.prototype = {
        defaults: {
            disableAllBtn: ".icheckcontrol-disable-all",
            enableAllBtn: ".icheckcontrol-enable-all",
            toggleAllBtn: ".icheckcontrol-toggle-all",
            target: "input.icheck"
        },
        init: function() {
            this.config = $.extend({}, this.defaults, this.options);
            var _that = this;
            _that.$element.on("click", function(e) {
                e.preventDefault;
                var target = $(_that.config.target);
                if ($(this).hasClass(_that.config.disableAllBtn)) {
                    target.iCheck("uncheck");
                } else if ($(this).hasClass(_that.config.enableAllBtn)) {
                    target.iCheck("check");
                } else {
                    if (target.not(":checked").length > 0) {
                        target.iCheck("check");
                    } else {
                        target.iCheck("uncheck");
                    }
                }
            });
        }
    };
    ICheckControl.defaults = ICheckControl.prototype.defaults;
    $.fn.iCheckControl = function(options) {
        new ICheckControl(this, options).init();
    };
    window.ICheckControl = Plugin;
})(window, jQuery);

jQuery(document).ready(function() {
    $(".icheckcontrol").iCheckControl();
});

(function(window, $) {
    var Cities = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    Cities.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            _that.$element.find(_that.config.stateElement).on("change", function(e) {
                e.preventDefault();
                var stateValue = $(this).val();
                if (!stateValue) {
                    return;
                }
                App.blockUI({
                    cenrerY: true,
                    animate: true
                });
                $.ajax({
                    type: "POST",
                    url: _that.config.url,
                    data: "state=" + stateValue,
                    success: function(data) {
                        if (data.error) {
                            _that.erroMessage(data.error);
                        } else {
                            _that.$element.find(_that.config.cityElement).empty().append(data.cities);
                            App.unblockUI();
                        }
                    },
                    error: function() {
                        _that.erroMessage("Não foi possível localizar o cep informado. Por favor, tente novamente ou informe o endereço manualmente.");
                    }
                });
            });
        },
        erroMessage: function(msg) {
            bootbox.dialog({
                message: msg,
                title: "Atenção",
                buttons: {
                    success: {
                        label: "OK",
                        className: "blue",
                        callback: function() {
                            App.unblockUI();
                        }
                    }
                }
            });
        }
    };
    Cities.defaults = Cities.prototype.defaults;
    $.fn.cities = function(options) {
        return this.each(function() {
            new Cities(this, options).init();
        });
    };
    window.Cities = Plugin;
})(window, jQuery);

(function(window, $) {
    var FileInput = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    FileInput.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options);
            var _that = this;
            console.log(this.$element);
            _that.$element.find("#file").on("change", function() {
                if ($(this).val()) {
                    _that.$element.addClass("fileinput-exist");
                    var img = $("<img width='100%' src='" + $(this).val() + "'>");
                    _that.$element.find(".fileinput-preview").empty().append(img).show();
                    _that.$element.find(".fileinput-new").hide();
                }
            });
            _that.$element.find(".fileinput-remove").on("click", function(e) {
                _that.$element.find(".fileinput-preview").empty().hide();
                _that.$element.find(".fileinput-new").show();
                _that.$element.addClass("fileinput-exist");
                _that.$element.find("#file").val("");
            });
        }
    };
    FileInput.defaults = FileInput.prototype.defaults;
    $.fn.fileInput = function(options) {
        return this.each(function() {
            new FileInput(this, options).init();
        });
    };
    window.FileInput = Plugin;
})(window, jQuery);

(function(window, $) {
    var FormSave = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    FormSave.prototype = {
        defaults: {
            submitTrigger: 'button[type="submit"]',
            cancelTrigguer: ".form-action-cancel",
            enableValidators: false
        },
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            _that.$element.find(_that.config.submitTrigger).on("click", function(e) {
                e.preventDefault();
                var isValid = true;
                if (_that.$element.hasClass("enable-validators") || _that.config.enableValidators) {
                    isValid = _that.$element.valid();
                }
                if (isValid) {
                    App.blockUI({
                        cenrerY: true,
                        animate: true
                    });
                    setTimeout(function() {
                        $(_that.$element).submit();
                    }, 1500);
                }
            });
            _that.$element.find(_that.config.cancelTrigguer).on("click", function(e) {
                e.preventDefault();
                var _link = $(this);
                bootbox.dialog({
                    message: "Tem certeza que deseja descartar as alterações?",
                    title: "Atenção",
                    buttons: {
                        success: {
                            label: "OK",
                            className: "red",
                            callback: function() {
                                App.blockUI({
                                    cenrerY: true,
                                    animate: true
                                });
                                window.location.href = _link.attr("href");
                                return;
                            }
                        },
                        danger: {
                            label: "Cancelar",
                            className: "blue",
                            callback: function() {
                                return;
                            }
                        }
                    }
                });
            });
        }
    };
    FormSave.defaults = FormSave.prototype.defaults;
    $.fn.formSave = function(options) {
        return this.each(function() {
            new FormSave(this, options).init();
        });
    };
    window.FormSave = Plugin;
})(window, jQuery);

(function(window, $) {
    var FormValidation = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    FormValidation.prototype = {
        defaults: {
            alertMensage: ".alert-form-errors",
            errorElement: "span",
            errorClass: "help-block help-block-error"
        },
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            _that.$element.validate({
                errorElement: _that.config.errorElement,
                errorClass: _that.config.errorClass,
                focusInvalid: false,
                ignore: "",
                messages: {},
                errorPlacement: function(error, element) {
                    if (element.parent(".input-group").size() > 0) {
                        error.insertAfter(element.parent(".input-group"));
                    } else if (element.attr("data-error-container")) {
                        error.appendTo(element.attr("data-error-container"));
                    } else if (element.parents(".radio-list").size() > 0) {
                        error.appendTo(element.parents(".radio-list").attr("data-error-container"));
                    } else if (element.parents(".radio-inline").size() > 0) {
                        error.appendTo(element.parents(".radio-inline").attr("data-error-container"));
                    } else if (element.parents(".checkbox-list").size() > 0) {
                        error.appendTo(element.parents(".checkbox-list").attr("data-error-container"));
                    } else if (element.parents(".checkbox-inline").size() > 0) {
                        error.appendTo(element.parents(".checkbox-inline").attr("data-error-container"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                invalidHandler: function(event, validator) {
                    _that.$element.find(_that.config.alertMensage).show();
                    App.scrollTo($("div.alert.display-hide"), -200);
                },
                highlight: function(element) {
                    $(element).closest(".form-group").addClass("has-error");
                },
                unhighlight: function(element) {
                    $(element).closest(".form-group").removeClass("has-error");
                },
                success: function(label) {
                    label.closest(".form-group").removeClass("has-error");
                }
            });
            _that.$element.find(".date-picker .form-control").change(function() {
                _that.$element.validate().element($(this));
            });
        }
    };
    FormValidation.defaults = FormValidation.prototype.defaults;
    $.fn.formValidation = function(options) {
        return this.each(function() {
            new FormValidation(this, options).init();
        });
    };
    window.FormValidation = Plugin;
})(window, jQuery);

jQuery.extend(jQuery.validator.messages, {
    required: "Este campo é obrigatório.",
    remote: "Please fix this field.",
    email: "Endereço de email não é válido.",
    url: "Please enter a valid URL.",
    date: "Please enter a valid date.",
    dateISO: "Please enter a valid date (ISO).",
    number: "Please enter a valid number.",
    digits: "Please enter only digits.",
    creditcard: "Please enter a valid credit card number.",
    equalTo: "Please enter the same value again.",
    accept: "Please enter a value with a valid extension.",
    maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
    minlength: jQuery.validator.format("Please enter at least {0} characters."),
    rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
    range: jQuery.validator.format("Please enter a value between {0} and {1}."),
    max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
    min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
});

jQuery(document).ready(function() {
    tinymce.init({
        selector: ".tinymce",
        height: 350,
        directionality: "ltr",
        plugins: [ "autolink link image lists hr anchor", "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking", "table contextmenu directionality paste, responsivefilemanager, imagetools, fullscreen" ],
        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
        toolbar2: "| responsivefilemanager | link unlink anchor | image media | fullscreen",
        image_advtab: true,
        imagetools_cors_hosts: [ "localhost", "codepen.io" ],
        image_caption: true,
        style_formats: [ {
            title: "Image Left",
            selector: "img",
            styles: {
                "float": "left",
                margin: "0 10px 0 10px"
            }
        }, {
            title: "Image Right",
            selector: "img",
            styles: {
                "float": "right",
                margin: "0 10px 0 10px"
            }
        } ],
        content_css: [ "//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css", "//www.tinymce.com/css/codepen.min.css" ],
        relative_urls: false,
        remove_script_host: false,
        external_filemanager_path: "/filemanager/",
        filemanager_title: "Responsive Filemanager",
        external_plugins: {
            filemanager: "/filemanager/plugin.min.js"
        }
    });
    $(".post-sidebar-options").postStatus();
    $(".slug-container").slug();
    $(".post-list").postListStatus();
    $(".post-sites").postSites();
    $("form.default-form-actions").formSave();
    $(".cep").cep();
    $(".state-cities").cities();
    $(".password-generator").passwordGenerator();
    if (jQuery().datepicker) {
        $(".date-picker").datepicker({
            rtl: App.isRTL(),
            todayBtn: true,
            autoclose: true,
            language: "pt-BR",
            todayHighlight: true
        });
    }
    if (jQuery().timepicker) {
        $(".time-picker").timepicker({
            defaultTime: false,
            showMeridian: false
        });
    }
    $("form.enable-validators").formValidation();
    $(".dd").nestable();
    $("#sortable_banner").sortable({
        items: ".banner-item",
        opacity: .8,
        handle: ".portlet-title",
        coneHelperSize: true
    });
    $(".gallery-items").sortable({
        items: ".gallery-item",
        opacity: .8,
        coneHelperSize: true,
        handle: ".gallery-item-move"
    });
    $("select.multi-select").multiSelect({
        selectableOptgroup: true
    });
    $.fn.select2.defaults.set("theme", "bootstrap");
    $("select.select2").select2({
        placeholder: "Selecione"
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
    $(".responsivefilemanager").modalResponsiveFileManager();
    $(".page-gallery").banner();
    $("div#admin-menu").menu();
    $(".fileinput").fileInput();
});

(function(window, $) {
    var Menu = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    Menu.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            var formExternalUrl = _that.$element.find(".admin-menu-add .admin-menu-add-external-url form");
            _that.$element.find(".admin-menu-add .admin-menu-add-external-url .action-add").on("click", function(e) {
                e.preventDefault();
                if (_that.validateForm(formExternalUrl)) {
                    _that.createNode(formExternalUrl.serialize());
                }
            });
            var formPages = _that.$element.find(".admin-menu-add .admin-menu-add-page form");
            _that.$element.find('.admin-menu-add .admin-menu-add-page form a[data-toggle="tab"]').on("shown.bs.tab", function(e) {
                formPages.find("input.icheck").iCheck("uncheck");
            });
            _that.$element.find(".admin-menu-add .admin-menu-add-page .action-add").on("click", function(e) {
                e.preventDefault();
                if (_that.validateForm(formPages)) {
                    if (formPages.find('input[type="checkbox"]:checked').length > 0) {
                        _that.createNode(formPages.serialize());
                    }
                }
            });
            $(document).on("click", ".admin-menu-items .menu-items .form-item-edit .actions .remove", function(e) {
                e.preventDefault();
                var target = $(this).closest(".dd-item");
                target.children(".dd-handle").first().css({
                    "border-color": "red",
                    background: "#FFB5B5"
                });
                target.slideUp(function() {
                    target.remove();
                });
            });
            $(document).on("keyup", '.admin-menu-items .menu-items .form-item-edit input[name="label"]', function(e) {
                $(this).closest(".dd-item").data("label", $(this).val()).children(".dd-handle").children().text($(this).val());
            });
            $(document).on("keyup", '.admin-menu-items .menu-items .form-item-edit input[name="url"]', function(e) {
                $(this).closest(".dd-item").data("external-url", $(this).val());
            });
            _that.$element.find(".admin-menu-add .search-page-action").on("click", function(e) {
                e.preventDefault();
                if (!_that.$element.find('#tab_search_pages .form-search input[name="search"]').val()) {
                    return;
                }
                var form = _that.$element.find("#tab_search_pages .form-search :input").serialize();
                App.blockUI({
                    cenrerY: true,
                    animate: true
                });
                $.ajax({
                    type: "GET",
                    url: _that.config.urlPage,
                    data: form,
                    success: function(data) {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            _that.$element.find(".admin-menu-add #tab_search_pages .page-list").replaceWith(data.pages);
                            $(".icheck").iCheck({
                                checkboxClass: "icheckbox_minimal-grey"
                            });
                        }
                    },
                    error: function() {
                        alert("Erro desconhecido. Tente novamente");
                    },
                    complete: function() {
                        App.unblockUI();
                    }
                });
            });
            _that.$element.find(".admin-menu-items .form-actions .action-save").on("click", function(e) {
                e.preventDefault();
                var menuSerialize = _that.$element.find(".admin-menu-items .menu-items .dd").nestable("serialize");
                App.blockUI({
                    cenrerY: true,
                    animate: true
                });
                $('<form method="POST">').append($('<input name="menu">').val(JSON.stringify(menuSerialize))).submit();
            });
        },
        createNode: function(data) {
            var _that = this;
            App.blockUI({
                cenrerY: true,
                animate: true
            });
            $.ajax({
                type: "POST",
                url: _that.config.urlItem,
                data: data,
                success: function(data) {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        _that.addNode(data.node);
                        _that.resetForm();
                    }
                },
                error: function() {
                    alert("Erro desconhecido. Tente novamente");
                },
                complete: function() {
                    App.unblockUI();
                }
            });
        },
        addNode: function(node) {
            this.$element.find(".admin-menu-items .menu-items .dd-list").first().append(node);
        },
        resetForm: function() {
            this.$element.find(".admin-menu-add form").trigger("reset");
            this.$element.find(".icheck").iCheck("uncheck");
            $(document).find(".icheck").iCheck("uncheck");
        },
        validateForm: function(form) {
            form.validate({
                highlight: function(element) {
                    $(element).closest(".form-group").addClass("has-error");
                },
                errorPlacement: function(error, element) {
                    return "";
                },
                unhighlight: function(element) {
                    $(element).closest(".form-group").removeClass("has-error");
                }
            });
            return form.valid();
        }
    };
    Menu.defaults = Menu.prototype.defaults;
    $.fn.menu = function(options) {
        return this.each(function() {
            new Menu(this, options).init();
        });
    };
    window.Menu = Plugin;
})(window, jQuery);

(function(window, $) {
    var ModalResponsiveFileManager = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    ModalResponsiveFileManager.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            var modal = $(_that.config.target);
            _that.$element.on("click", function(e) {
                e.preventDefault();
                App.blockUI({
                    cenrerY: true,
                    animate: true
                });
                var ifr = $("<iframe/>", {
                    src: _that.config.url,
                    style: "width: 100%; min-height: 600px",
                    frameborder: 0,
                    load: function() {
                        App.unblockUI();
                        modal.modal("show");
                    }
                });
                modal.find(".modal-body").empty().replaceWith(ifr);
                modal.on("hide.bs.modal	", function(e) {
                    App.unblockUI();
                    modal.find(".modal-body").empty();
                });
            });
        }
    };
    ModalResponsiveFileManager.defaults = ModalResponsiveFileManager.prototype.defaults;
    $.fn.modalResponsiveFileManager = function(options) {
        return this.each(function() {
            new ModalResponsiveFileManager(this, options).init();
        });
    };
    window.ModalResponsiveFileManager = Plugin;
})(window, jQuery);

function responsive_filemanager_callback(field_id) {
    var field = $("#" + field_id);
    field.trigger("change");
    return;
}

(function(window, $) {
    var PasswordGenerator = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    PasswordGenerator.prototype = {
        defaults: {
            buttonTrigger: "button",
            input: 'input[name="temp-pass"]'
        },
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            _that.$element.find(_that.config.buttonTrigger).on("click", function(e) {
                e.preventDefault();
                App.blockUI({
                    cenrerY: true,
                    animate: true
                });
                $.ajax({
                    type: "GET",
                    url: _that.config.url,
                    success: function(data) {
                        if (data.error) {
                            _that.erroMessage(data.error);
                        } else {
                            _that.$element.find(_that.config.input).val(data.password);
                            App.unblockUI();
                        }
                    },
                    error: function() {
                        _that.erroMessage("Não foi possível gerar a senha. Por favor, tente novamente.");
                    }
                });
            });
        },
        erroMessage: function(msg) {
            bootbox.dialog({
                message: msg,
                title: "Atenção",
                buttons: {
                    success: {
                        label: "OK",
                        className: "blue",
                        callback: function() {
                            App.unblockUI();
                        }
                    }
                }
            });
        }
    };
    PasswordGenerator.defaults = PasswordGenerator.prototype.defaults;
    $.fn.passwordGenerator = function(options) {
        return this.each(function() {
            new PasswordGenerator(this, options).init();
        });
    };
    window.PasswordGenerator = Plugin;
})(window, jQuery);

(function(window, $) {
    var PostListStatus = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    PostListStatus.prototype = {
        defaults: {
            removeMsg: "Tem certeza que deseja exluir o item?"
        },
        init: function() {
            this.config = $.extend({}, this.defaults, this.options);
            var _that = this;
            _that.trashAction();
        },
        trashAction: function() {
            var _that = this;
            console.log(_that.$element.find(".post-list-remove"));
            _that.$element.find(".post-list-remove").on("click", function(e) {
                e.preventDefault();
                _that.remove(_that.config.removeMsg, $(this).attr("href"));
            });
        },
        remove: function(msg, link) {
            bootbox.dialog({
                message: msg,
                title: "Atenção",
                buttons: {
                    success: {
                        label: "OK",
                        className: "red",
                        callback: function() {
                            App.blockUI({
                                cenrerY: true,
                                animate: true
                            });
                            window.location.href = link;
                            return;
                        }
                    },
                    danger: {
                        label: "Cancelar",
                        className: "blue",
                        callback: function() {
                            return;
                        }
                    }
                }
            });
        }
    };
    PostListStatus.defaults = PostListStatus.prototype.defaults;
    $.fn.postListStatus = function(options) {
        return this.each(function() {
            new PostListStatus(this, options).init();
        });
    };
    window.PostListStatus = Plugin;
})(window, jQuery);

(function(window, $) {
    var PostSites = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    PostSites.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            var form = _that.$element.find(".post-site-form");
            _that.$element.find(".post-site-form .action.add").on("click", function(e) {
                e.preventDefault();
                var site = form.find('select[name="sites-enabled"]').select2("data");
                var siteId = site[0].id;
                var siteTitle = site[0].text;
                var highlight = form.find('input[name="site-highlight"]').is(":checked");
                if (siteId == "") {
                    return;
                }
                if (_that.isExist(siteId)) {
                    return;
                }
                App.blockUI({
                    cenrerY: true,
                    animate: true
                });
                var table = _that.$element.find(".post-sites-table tbody");
                if (highlight) {
                    siteTitle = siteTitle + '<span class="label label-sm label-success"> Destaque </span>';
                }
                table.append("<tr class='post-sites-item' data-id='" + siteId + "'>					<td>" + siteTitle + "</td>					<td>					<button class='btn btn-sm btn-default action remove'><i class='fa fa-close'></i></button>					<input type='hidden' name='sites[" + siteId + "][id]' value='" + siteId + "' />					<input type='hidden' name='sites[" + siteId + "][highlight]' value='" + highlight + "' /></td>");
                setTimeout(function() {
                    App.unblockUI();
                    form.find('select[name="sites-enabled"]').val(null).trigger("change");
                    form.find('input[name="site-highlight"]').iCheck("uncheck");
                }, 1e3);
            });
            $(document).on("click", ".post-sites-table .post-sites-item .action.remove", function(e) {
                e.preventDefault();
                var item = $(this).closest(".post-sites-item");
                item.fadeOut("slow", function() {
                    item.remove();
                });
            });
            _that.$element.find(".post-sites-table .action.remove-all").on("click", function(e) {
                e.preventDefault();
                _that.$element.find(".post-sites-table .post-sites-item").remove();
            });
        },
        isExist: function(siteId) {
            var _that = this;
            if (_that.$element.find('.post-sites-table .post-sites-item[data-id="' + siteId + '"]').length > 0) {
                return true;
            }
            return false;
        }
    };
    PostSites.defaults = PostSites.prototype.defaults;
    $.fn.postSites = function(options) {
        return this.each(function() {
            new PostSites(this, options).init();
        });
    };
    window.PostSites = Plugin;
})(window, jQuery);

(function(window, $) {
    var Slug = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    Slug.prototype = {
        defaults: {
            btnAction: ".slug-action-edit",
            inputElement: "#slug"
        },
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            _that.process();
            _that.processTarget();
        },
        process: function() {
            var _that = this;
            var slug = $(this.config.inputElement);
            $(_that.config.btnAction).on("click", function(e) {
                e.preventDefault();
                slug.removeProp("readonly");
            });
        },
        processTarget: function() {
            var _that = this;
            var target = $(this.config.slugtarget);
            target.on("blur", function(e) {
                e.preventDefault();
                if (target.val() === "") {
                    return false;
                }
                if (_that.getSlugValue() != "") {
                    return false;
                }
                _that.generateSlug(target.val());
            });
        },
        getSlugValue: function() {
            return $(this.config.inputElement).val();
        },
        generateSlug: function(str) {
            var _that = this;
            var data = "slug=" + str;
            $.ajax({
                type: "POST",
                url: _that.config.slugurlvalidation,
                data: data,
                success: function(data) {
                    _that.displaySlug(data.slug);
                },
                error: function() {
                    alert("error");
                }
            });
        },
        displaySlug: function(slug) {
            if (slug != "") {
                $(this.config.inputElement).val(slug);
            }
        }
    };
    Slug.defaults = Slug.prototype.defaults;
    $.fn.slug = function(options) {
        return this.each(function() {
            new Slug(this, options).init();
        });
    };
    window.Slug = Plugin;
})(window, jQuery);

(function(window, $) {
    var PostStatus = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    PostStatus.prototype = {
        defaults: {
            form: "#post-form"
        },
        init: function() {
            this.config = $.extend({}, this.defaults, this.options);
            var _that = this;
            _that.changeStatus();
            _that.changePostDate();
            _that.descartAction();
            _that.publishAction();
            _that.draftAction();
            _that.saveAction();
        },
        changeStatus: function() {
            var _that = this;
            _that.$element.find(".status .action.edit").on("click", function(e) {
                e.preventDefault();
                var optionsBlock = _that.$element.find(".status .options");
                if (optionsBlock.hasClass("show-options")) {
                    optionsBlock.removeClass("show-options").hide(100);
                    $(this).text("Editar");
                } else {
                    optionsBlock.addClass("show-options").show(100);
                    $(this).text("Cancelar");
                }
            });
            _that.$element.find(".status .action.ok").on("click", function(e) {
                e.preventDefault();
                var status = _that.$element.find(".status .options select[name='status']").val();
                if (status == "") {
                    return;
                }
                _that.$element.find(".status span > strong").text(_that.$element.find(".status .options select[name='status'] option:selected").text());
                _that.$element.find(".status .options").removeClass("show-options").hide(100);
                _that.$element.find(".status .action.edit").text("Editar");
            });
        },
        changePostDate: function() {
            var _that = this;
            _that.$element.find(".post-date .action.edit").on("click", function(e) {
                e.preventDefault();
                var optionsBlock = _that.$element.find(".post-date .options");
                if (optionsBlock.hasClass("show-options")) {
                    optionsBlock.removeClass("show-options").hide(100);
                    $(this).text("Editar");
                } else {
                    optionsBlock.addClass("show-options").show(100);
                    $(this).text("Cancelar");
                }
            });
            _that.$element.find(".post-date .action.ok").on("click", function(e) {
                e.preventDefault();
                var date = _that.$element.find(".post-date input[name='date']").datepicker("getDate");
                var hour = _that.$element.find(".post-date input[name='hour']").timepicker("getTime").val();
                var postDate = new Date(date.getFullYear(), date.getMonth(), date.getDate(), hour.split(":")[0], hour.split(":")[1]);
                var momentdate = moment(postDate);
                _that.$element.find(".post-date span strong").text(momentdate.format("DD/MM/YYYY [às] HH:mm"));
                _that.$element.find(".post-date input[name='postDate']").val(momentdate.format("DD/MM/YYYY HH:mm"));
                _that.$element.find(".post-date .options").hide(100);
                _that.$element.find(".post-date .options").removeClass("show-options");
                _that.$element.find(".post-date .action.edit").text("Editar");
            });
        },
        descartAction: function() {
            this.$element.find(".form-actions .action.cancel").on("click", function(e) {
                e.preventDefault();
                var _that = $(this);
                bootbox.dialog({
                    message: "Tem certeza que deseja descartar as alterações?",
                    title: "Atenção",
                    buttons: {
                        success: {
                            label: "OK",
                            className: "red",
                            callback: function() {
                                App.blockUI({
                                    cenrerY: true,
                                    animate: true
                                });
                                window.location.href = _that.attr("href");
                                return;
                            }
                        },
                        danger: {
                            label: "Cancelar",
                            className: "blue",
                            callback: function() {
                                return;
                            }
                        }
                    }
                });
            });
        },
        publishAction: function() {
            var _that = this;
            _that.$element.find('.form-actions button[name="publish"]').on("click", function(e) {
                e.preventDefault();
                _that.$element.find(".status .options select[name='status']").val("published");
                _that.save();
            });
        },
        draftAction: function() {
            var _that = this;
            _that.$element.find(".heding-actions .action.save-draft").on("click", function(e) {
                e.preventDefault();
                _that.$element.find(".status .options select[name='status']").val("draft");
                _that.save();
            });
        },
        saveAction: function() {
            var _that = this;
            _that.$element.find('.form-actions button[name="save"]').on("click", function(e) {
                e.preventDefault();
                _that.save();
            });
        },
        save: function() {
            var _that = this;
            App.blockUI({
                cenrerY: true,
                animate: true
            });
            setTimeout(function() {
                $(_that.config.form).submit();
            }, 1500);
        }
    };
    PostStatus.defaults = PostStatus.prototype.defaults;
    $.fn.postStatus = function(options) {
        return this.each(function() {
            new PostStatus(this, options).init();
        });
    };
    window.PostStatus = Plugin;
})(window, jQuery);

(function(window, $) {
    var PostThumb = function(options) {
        this.options = options;
    };
    PostThumb.prototype = {
        defaults: {
            btnAdd: ".post-sidebar-thumb-action-add",
            btnRemove: ".post-sidebar-thumb-action-remove",
            inputElement: "#returnResponsivefilemanager",
            imgContainer: ".post-sidebar-thumb-img"
        },
        init: function() {
            this.config = $.extend({}, this.defaults, this.options);
            var _that = this;
            $(_that.config.inputElement).on("change", function(e) {
                if ($(this).val()) {
                    var img = $("<img width='100%' src='" + $(this).val() + "'>");
                    $(_that.config.imgContainer).empty().append(img);
                    $(_that.config.btnRemove).show();
                }
            });
            $(_that.config.btnRemove).on("click", function(e) {
                e.preventDefault();
                $(_that.config.btnRemove).hide();
                $(_that.config.imgContainer).empty();
                $(_that.config.inputElement).val("");
                return;
            });
        }
    };
    PostThumb.defaults = Slug.prototype.defaults;
    $.fn.postThumb = function(options) {
        return new PostThumb(options).init();
    };
    window.PostThumb = Plugin;
})(window, jQuery);

(function(window, $) {
    var SelectImage = function(options) {
        this.options = options;
    };
    SelectImage.prototype = {
        defaults: {
            container: ".select-image",
            btnAdd: ".select-image-add",
            btnRemove: ".select-image-remove",
            inputTarget: ".select-image-input",
            imgContainerTarget: ".select-image-container"
        },
        init: function() {
            this.config = $.extend({}, this.defaults, this.options);
            var _that = this;
            console.log($(_that.config.container));
            $(_that.config.inputTarget).on("change", function(e) {
                if ($(this).val()) {
                    var img = $("<img width='100%' height='200px' src='" + $(this).val() + "'>");
                    $(_that.config.imgContainerTarget).empty().append(img);
                    $(_that.config.container).addClass("select-image-has-image");
                }
            });
            $(_that.config.btnRemove).on("click", function(e) {
                e.preventDefault();
                $(_that.config.imgContainerTarget).empty();
                $(_that.config.inputTarget).val("");
                $(_that.config.container).removeClass("select-image-has-image");
                return;
            });
        }
    };
    SelectImage.defaults = Slug.prototype.defaults;
    $.fn.selectImage = function(options) {
        return new SelectImage(options).init();
    };
    window.SelectImage = Plugin;
})(window, jQuery);