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
                    complete: function() {
                        App.unblockUI();
                    },
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
                        App.unblockUI();
                        _that.erroMessage("Não foi possível localizar o cep informado. Por favor, tente novamente ou informe o endereço manualmente.");
                    }
                });
            });
        },
        erroMessage: function(msg) {
            bootbox.alert({
                message: msg,
                title: "Atenção"
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

(function(f) {
    if (typeof exports === "object" && typeof module !== "undefined") {
        module.exports = f();
    } else if (typeof define === "function" && define.amd) {
        define([], f);
    } else {
        var g;
        if (typeof window !== "undefined") {
            g = window;
        } else if (typeof global !== "undefined") {
            g = global;
        } else if (typeof self !== "undefined") {
            g = self;
        } else {
            g = this;
        }
        g.Clipboard = f();
    }
})(function() {
    var define, module, exports;
    return function e(t, n, r) {
        function s(o, u) {
            if (!n[o]) {
                if (!t[o]) {
                    var a = typeof require == "function" && require;
                    if (!u && a) return a(o, !0);
                    if (i) return i(o, !0);
                    var f = new Error("Cannot find module '" + o + "'");
                    throw f.code = "MODULE_NOT_FOUND", f;
                }
                var l = n[o] = {
                    exports: {}
                };
                t[o][0].call(l.exports, function(e) {
                    var n = t[o][1][e];
                    return s(n ? n : e);
                }, l, l.exports, e, t, n, r);
            }
            return n[o].exports;
        }
        var i = typeof require == "function" && require;
        for (var o = 0; o < r.length; o++) s(r[o]);
        return s;
    }({
        1: [ function(require, module, exports) {
            var DOCUMENT_NODE_TYPE = 9;
            if (typeof Element !== "undefined" && !Element.prototype.matches) {
                var proto = Element.prototype;
                proto.matches = proto.matchesSelector || proto.mozMatchesSelector || proto.msMatchesSelector || proto.oMatchesSelector || proto.webkitMatchesSelector;
            }
            function closest(element, selector) {
                while (element && element.nodeType !== DOCUMENT_NODE_TYPE) {
                    if (typeof element.matches === "function" && element.matches(selector)) {
                        return element;
                    }
                    element = element.parentNode;
                }
            }
            module.exports = closest;
        }, {} ],
        2: [ function(require, module, exports) {
            var closest = require("./closest");
            function delegate(element, selector, type, callback, useCapture) {
                var listenerFn = listener.apply(this, arguments);
                element.addEventListener(type, listenerFn, useCapture);
                return {
                    destroy: function() {
                        element.removeEventListener(type, listenerFn, useCapture);
                    }
                };
            }
            function listener(element, selector, type, callback) {
                return function(e) {
                    e.delegateTarget = closest(e.target, selector);
                    if (e.delegateTarget) {
                        callback.call(element, e);
                    }
                };
            }
            module.exports = delegate;
        }, {
            "./closest": 1
        } ],
        3: [ function(require, module, exports) {
            exports.node = function(value) {
                return value !== undefined && value instanceof HTMLElement && value.nodeType === 1;
            };
            exports.nodeList = function(value) {
                var type = Object.prototype.toString.call(value);
                return value !== undefined && (type === "[object NodeList]" || type === "[object HTMLCollection]") && "length" in value && (value.length === 0 || exports.node(value[0]));
            };
            exports.string = function(value) {
                return typeof value === "string" || value instanceof String;
            };
            exports.fn = function(value) {
                var type = Object.prototype.toString.call(value);
                return type === "[object Function]";
            };
        }, {} ],
        4: [ function(require, module, exports) {
            var is = require("./is");
            var delegate = require("delegate");
            function listen(target, type, callback) {
                if (!target && !type && !callback) {
                    throw new Error("Missing required arguments");
                }
                if (!is.string(type)) {
                    throw new TypeError("Second argument must be a String");
                }
                if (!is.fn(callback)) {
                    throw new TypeError("Third argument must be a Function");
                }
                if (is.node(target)) {
                    return listenNode(target, type, callback);
                } else if (is.nodeList(target)) {
                    return listenNodeList(target, type, callback);
                } else if (is.string(target)) {
                    return listenSelector(target, type, callback);
                } else {
                    throw new TypeError("First argument must be a String, HTMLElement, HTMLCollection, or NodeList");
                }
            }
            function listenNode(node, type, callback) {
                node.addEventListener(type, callback);
                return {
                    destroy: function() {
                        node.removeEventListener(type, callback);
                    }
                };
            }
            function listenNodeList(nodeList, type, callback) {
                Array.prototype.forEach.call(nodeList, function(node) {
                    node.addEventListener(type, callback);
                });
                return {
                    destroy: function() {
                        Array.prototype.forEach.call(nodeList, function(node) {
                            node.removeEventListener(type, callback);
                        });
                    }
                };
            }
            function listenSelector(selector, type, callback) {
                return delegate(document.body, selector, type, callback);
            }
            module.exports = listen;
        }, {
            "./is": 3,
            delegate: 2
        } ],
        5: [ function(require, module, exports) {
            function select(element) {
                var selectedText;
                if (element.nodeName === "SELECT") {
                    element.focus();
                    selectedText = element.value;
                } else if (element.nodeName === "INPUT" || element.nodeName === "TEXTAREA") {
                    var isReadOnly = element.hasAttribute("readonly");
                    if (!isReadOnly) {
                        element.setAttribute("readonly", "");
                    }
                    element.select();
                    element.setSelectionRange(0, element.value.length);
                    if (!isReadOnly) {
                        element.removeAttribute("readonly");
                    }
                    selectedText = element.value;
                } else {
                    if (element.hasAttribute("contenteditable")) {
                        element.focus();
                    }
                    var selection = window.getSelection();
                    var range = document.createRange();
                    range.selectNodeContents(element);
                    selection.removeAllRanges();
                    selection.addRange(range);
                    selectedText = selection.toString();
                }
                return selectedText;
            }
            module.exports = select;
        }, {} ],
        6: [ function(require, module, exports) {
            function E() {}
            E.prototype = {
                on: function(name, callback, ctx) {
                    var e = this.e || (this.e = {});
                    (e[name] || (e[name] = [])).push({
                        fn: callback,
                        ctx: ctx
                    });
                    return this;
                },
                once: function(name, callback, ctx) {
                    var self = this;
                    function listener() {
                        self.off(name, listener);
                        callback.apply(ctx, arguments);
                    }
                    listener._ = callback;
                    return this.on(name, listener, ctx);
                },
                emit: function(name) {
                    var data = [].slice.call(arguments, 1);
                    var evtArr = ((this.e || (this.e = {}))[name] || []).slice();
                    var i = 0;
                    var len = evtArr.length;
                    for (i; i < len; i++) {
                        evtArr[i].fn.apply(evtArr[i].ctx, data);
                    }
                    return this;
                },
                off: function(name, callback) {
                    var e = this.e || (this.e = {});
                    var evts = e[name];
                    var liveEvents = [];
                    if (evts && callback) {
                        for (var i = 0, len = evts.length; i < len; i++) {
                            if (evts[i].fn !== callback && evts[i].fn._ !== callback) liveEvents.push(evts[i]);
                        }
                    }
                    liveEvents.length ? e[name] = liveEvents : delete e[name];
                    return this;
                }
            };
            module.exports = E;
        }, {} ],
        7: [ function(require, module, exports) {
            (function(global, factory) {
                if (typeof define === "function" && define.amd) {
                    define([ "module", "select" ], factory);
                } else if (typeof exports !== "undefined") {
                    factory(module, require("select"));
                } else {
                    var mod = {
                        exports: {}
                    };
                    factory(mod, global.select);
                    global.clipboardAction = mod.exports;
                }
            })(this, function(module, _select) {
                "use strict";
                var _select2 = _interopRequireDefault(_select);
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : {
                        "default": obj
                    };
                }
                var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function(obj) {
                    return typeof obj;
                } : function(obj) {
                    return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
                };
                function _classCallCheck(instance, Constructor) {
                    if (!(instance instanceof Constructor)) {
                        throw new TypeError("Cannot call a class as a function");
                    }
                }
                var _createClass = function() {
                    function defineProperties(target, props) {
                        for (var i = 0; i < props.length; i++) {
                            var descriptor = props[i];
                            descriptor.enumerable = descriptor.enumerable || false;
                            descriptor.configurable = true;
                            if ("value" in descriptor) descriptor.writable = true;
                            Object.defineProperty(target, descriptor.key, descriptor);
                        }
                    }
                    return function(Constructor, protoProps, staticProps) {
                        if (protoProps) defineProperties(Constructor.prototype, protoProps);
                        if (staticProps) defineProperties(Constructor, staticProps);
                        return Constructor;
                    };
                }();
                var ClipboardAction = function() {
                    function ClipboardAction(options) {
                        _classCallCheck(this, ClipboardAction);
                        this.resolveOptions(options);
                        this.initSelection();
                    }
                    _createClass(ClipboardAction, [ {
                        key: "resolveOptions",
                        value: function resolveOptions() {
                            var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
                            this.action = options.action;
                            this.container = options.container;
                            this.emitter = options.emitter;
                            this.target = options.target;
                            this.text = options.text;
                            this.trigger = options.trigger;
                            this.selectedText = "";
                        }
                    }, {
                        key: "initSelection",
                        value: function initSelection() {
                            if (this.text) {
                                this.selectFake();
                            } else if (this.target) {
                                this.selectTarget();
                            }
                        }
                    }, {
                        key: "selectFake",
                        value: function selectFake() {
                            var _this = this;
                            var isRTL = document.documentElement.getAttribute("dir") == "rtl";
                            this.removeFake();
                            this.fakeHandlerCallback = function() {
                                return _this.removeFake();
                            };
                            this.fakeHandler = this.container.addEventListener("click", this.fakeHandlerCallback) || true;
                            this.fakeElem = document.createElement("textarea");
                            this.fakeElem.style.fontSize = "12pt";
                            this.fakeElem.style.border = "0";
                            this.fakeElem.style.padding = "0";
                            this.fakeElem.style.margin = "0";
                            this.fakeElem.style.position = "absolute";
                            this.fakeElem.style[isRTL ? "right" : "left"] = "-9999px";
                            var yPosition = window.pageYOffset || document.documentElement.scrollTop;
                            this.fakeElem.style.top = yPosition + "px";
                            this.fakeElem.setAttribute("readonly", "");
                            this.fakeElem.value = this.text;
                            this.container.appendChild(this.fakeElem);
                            this.selectedText = (0, _select2.default)(this.fakeElem);
                            this.copyText();
                        }
                    }, {
                        key: "removeFake",
                        value: function removeFake() {
                            if (this.fakeHandler) {
                                this.container.removeEventListener("click", this.fakeHandlerCallback);
                                this.fakeHandler = null;
                                this.fakeHandlerCallback = null;
                            }
                            if (this.fakeElem) {
                                this.container.removeChild(this.fakeElem);
                                this.fakeElem = null;
                            }
                        }
                    }, {
                        key: "selectTarget",
                        value: function selectTarget() {
                            this.selectedText = (0, _select2.default)(this.target);
                            this.copyText();
                        }
                    }, {
                        key: "copyText",
                        value: function copyText() {
                            var succeeded = void 0;
                            try {
                                succeeded = document.execCommand(this.action);
                            } catch (err) {
                                succeeded = false;
                            }
                            this.handleResult(succeeded);
                        }
                    }, {
                        key: "handleResult",
                        value: function handleResult(succeeded) {
                            this.emitter.emit(succeeded ? "success" : "error", {
                                action: this.action,
                                text: this.selectedText,
                                trigger: this.trigger,
                                clearSelection: this.clearSelection.bind(this)
                            });
                        }
                    }, {
                        key: "clearSelection",
                        value: function clearSelection() {
                            if (this.trigger) {
                                this.trigger.focus();
                            }
                            window.getSelection().removeAllRanges();
                        }
                    }, {
                        key: "destroy",
                        value: function destroy() {
                            this.removeFake();
                        }
                    }, {
                        key: "action",
                        set: function set() {
                            var action = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : "copy";
                            this._action = action;
                            if (this._action !== "copy" && this._action !== "cut") {
                                throw new Error('Invalid "action" value, use either "copy" or "cut"');
                            }
                        },
                        get: function get() {
                            return this._action;
                        }
                    }, {
                        key: "target",
                        set: function set(target) {
                            if (target !== undefined) {
                                if (target && (typeof target === "undefined" ? "undefined" : _typeof(target)) === "object" && target.nodeType === 1) {
                                    if (this.action === "copy" && target.hasAttribute("disabled")) {
                                        throw new Error('Invalid "target" attribute. Please use "readonly" instead of "disabled" attribute');
                                    }
                                    if (this.action === "cut" && (target.hasAttribute("readonly") || target.hasAttribute("disabled"))) {
                                        throw new Error('Invalid "target" attribute. You can\'t cut text from elements with "readonly" or "disabled" attributes');
                                    }
                                    this._target = target;
                                } else {
                                    throw new Error('Invalid "target" value, use a valid Element');
                                }
                            }
                        },
                        get: function get() {
                            return this._target;
                        }
                    } ]);
                    return ClipboardAction;
                }();
                module.exports = ClipboardAction;
            });
        }, {
            select: 5
        } ],
        8: [ function(require, module, exports) {
            (function(global, factory) {
                if (typeof define === "function" && define.amd) {
                    define([ "module", "./clipboard-action", "tiny-emitter", "good-listener" ], factory);
                } else if (typeof exports !== "undefined") {
                    factory(module, require("./clipboard-action"), require("tiny-emitter"), require("good-listener"));
                } else {
                    var mod = {
                        exports: {}
                    };
                    factory(mod, global.clipboardAction, global.tinyEmitter, global.goodListener);
                    global.clipboard = mod.exports;
                }
            })(this, function(module, _clipboardAction, _tinyEmitter, _goodListener) {
                "use strict";
                var _clipboardAction2 = _interopRequireDefault(_clipboardAction);
                var _tinyEmitter2 = _interopRequireDefault(_tinyEmitter);
                var _goodListener2 = _interopRequireDefault(_goodListener);
                function _interopRequireDefault(obj) {
                    return obj && obj.__esModule ? obj : {
                        "default": obj
                    };
                }
                var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function(obj) {
                    return typeof obj;
                } : function(obj) {
                    return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
                };
                function _classCallCheck(instance, Constructor) {
                    if (!(instance instanceof Constructor)) {
                        throw new TypeError("Cannot call a class as a function");
                    }
                }
                var _createClass = function() {
                    function defineProperties(target, props) {
                        for (var i = 0; i < props.length; i++) {
                            var descriptor = props[i];
                            descriptor.enumerable = descriptor.enumerable || false;
                            descriptor.configurable = true;
                            if ("value" in descriptor) descriptor.writable = true;
                            Object.defineProperty(target, descriptor.key, descriptor);
                        }
                    }
                    return function(Constructor, protoProps, staticProps) {
                        if (protoProps) defineProperties(Constructor.prototype, protoProps);
                        if (staticProps) defineProperties(Constructor, staticProps);
                        return Constructor;
                    };
                }();
                function _possibleConstructorReturn(self, call) {
                    if (!self) {
                        throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                    }
                    return call && (typeof call === "object" || typeof call === "function") ? call : self;
                }
                function _inherits(subClass, superClass) {
                    if (typeof superClass !== "function" && superClass !== null) {
                        throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
                    }
                    subClass.prototype = Object.create(superClass && superClass.prototype, {
                        constructor: {
                            value: subClass,
                            enumerable: false,
                            writable: true,
                            configurable: true
                        }
                    });
                    if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
                }
                var Clipboard = function(_Emitter) {
                    _inherits(Clipboard, _Emitter);
                    function Clipboard(trigger, options) {
                        _classCallCheck(this, Clipboard);
                        var _this = _possibleConstructorReturn(this, (Clipboard.__proto__ || Object.getPrototypeOf(Clipboard)).call(this));
                        _this.resolveOptions(options);
                        _this.listenClick(trigger);
                        return _this;
                    }
                    _createClass(Clipboard, [ {
                        key: "resolveOptions",
                        value: function resolveOptions() {
                            var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
                            this.action = typeof options.action === "function" ? options.action : this.defaultAction;
                            this.target = typeof options.target === "function" ? options.target : this.defaultTarget;
                            this.text = typeof options.text === "function" ? options.text : this.defaultText;
                            this.container = _typeof(options.container) === "object" ? options.container : document.body;
                        }
                    }, {
                        key: "listenClick",
                        value: function listenClick(trigger) {
                            var _this2 = this;
                            this.listener = (0, _goodListener2.default)(trigger, "click", function(e) {
                                return _this2.onClick(e);
                            });
                        }
                    }, {
                        key: "onClick",
                        value: function onClick(e) {
                            var trigger = e.delegateTarget || e.currentTarget;
                            if (this.clipboardAction) {
                                this.clipboardAction = null;
                            }
                            this.clipboardAction = new _clipboardAction2.default({
                                action: this.action(trigger),
                                target: this.target(trigger),
                                text: this.text(trigger),
                                container: this.container,
                                trigger: trigger,
                                emitter: this
                            });
                        }
                    }, {
                        key: "defaultAction",
                        value: function defaultAction(trigger) {
                            return getAttributeValue("action", trigger);
                        }
                    }, {
                        key: "defaultTarget",
                        value: function defaultTarget(trigger) {
                            var selector = getAttributeValue("target", trigger);
                            if (selector) {
                                return document.querySelector(selector);
                            }
                        }
                    }, {
                        key: "defaultText",
                        value: function defaultText(trigger) {
                            return getAttributeValue("text", trigger);
                        }
                    }, {
                        key: "destroy",
                        value: function destroy() {
                            this.listener.destroy();
                            if (this.clipboardAction) {
                                this.clipboardAction.destroy();
                                this.clipboardAction = null;
                            }
                        }
                    } ], [ {
                        key: "isSupported",
                        value: function isSupported() {
                            var action = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [ "copy", "cut" ];
                            var actions = typeof action === "string" ? [ action ] : action;
                            var support = !!document.queryCommandSupported;
                            actions.forEach(function(action) {
                                support = support && !!document.queryCommandSupported(action);
                            });
                            return support;
                        }
                    } ]);
                    return Clipboard;
                }(_tinyEmitter2.default);
                function getAttributeValue(suffix, element) {
                    var attribute = "data-clipboard-" + suffix;
                    if (!element.hasAttribute(attribute)) {
                        return;
                    }
                    return element.getAttribute(attribute);
                }
                module.exports = Clipboard;
            });
        }, {
            "./clipboard-action": 7,
            "good-listener": 4,
            "tiny-emitter": 6
        } ]
    }, {}, [ 8 ])(8);
});

(function(window, $) {
    var AdminDependents = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    AdminDependents.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options);
            var _that = this;
            _that.$element.find('input[data-required="required"], select[data-required="required"]').each(function() {
                $(this).on("change", function(e) {
                    if ($(this).val() != "") {
                        $(this).css({
                            border: ""
                        });
                    }
                });
            });
            _that.$element.find(".user-dependents-add").on("click", function(e) {
                e.preventDefault();
                var valid = true;
                _that.$element.find('input[data-required="required"], select[data-required="required"]').each(function() {
                    if ($(this).val() == "") {
                        $(this).css({
                            border: "1px solid red"
                        });
                        valid = false;
                    }
                });
                if (!valid) {
                    return;
                }
                var id = Math.floor(Date.now() / 1e3), name = _that.$element.find("#dependent_name"), birth = _that.$element.find("#dependent_birth_date");
                identifier = _that.$element.find("#dependent_identifier");
                gender = _that.$element.find("#dependent_gender");
                _that.$element.find("tbody > tr:last").before($('<tr data-row="' + id + '"></tr>').append("<td>" + name.val() + "</td>").append("<td>" + birth.val() + "</td>").append("<td>" + identifier.val() + "</td>").append("<td>" + gender.find("option:selected").text() + "</td>").append($("<td></td>").append('<a href="#" class="btn btn-sm btn-default user-dependents-remove" data-remove="' + id + '"><i class="fa fa-close"></i></a>').append('<a href="#" class="btn btn-sm btn-default user-dependents-edit" data-edit="' + id + '"><i class="fa fa-pencil"></i></a>').append('<input type="hidden" name="dependents[' + id + '][name]" value="' + name.val() + '">').append('<input type="hidden" name="dependents[' + id + '][birth_date]" value="' + birth.val() + '">').append('<input type="hidden" name="dependents[' + id + '][identifier]" value="' + identifier.val() + '">').append('<input type="hidden" name="dependents[' + id + '][gender]" value="' + gender.val() + '">')));
                name.val("");
                birth.val("");
                identifier.val("");
                gender.val("");
            });
            $(document).on("click", ".user-dependents-remove", function(e) {
                e.preventDefault();
                var row = $(document).find('tr[data-row="' + $(this).data("remove") + '"]');
                row.fadeOut("slow", function() {
                    row.remove();
                });
            });
            $(document).on("click", ".user-dependents-edit", function(e) {
                e.preventDefault();
                var row = $(document).find('tr[data-row="' + $(this).data("edit") + '"]');
                console.log($(document).find('input[name^="dependents[' + $(this).data("edit") + ']"]'));
            });
        }
    };
    AdminDependents.defaults = AdminDependents.prototype.defaults;
    $.fn.adminDependents = function(options) {
        return this.each(function() {
            new AdminDependents(this, options).init();
        });
    };
    window.AdminDependents = Plugin;
})(window, jQuery);

(function(window, $) {
    var EventPopulate = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    EventPopulate.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            _that.$element.on("change", function() {
                var stateValue = $(this).val();
                if (!stateValue) {
                    return;
                }
                App.blockUI({
                    cenrerY: true,
                    animate: true
                });
            });
            $.ajax({
                type: "POST",
                url: _that.config.url,
                data: "event=" + stateValue,
                success: function(data) {
                    if (data.error) {
                        _that.erroMessage(data.error);
                    } else {
                        console.log(data);
                        App.unblockUI();
                    }
                },
                error: function() {
                    _that.erroMessage("Erro ao localizar locais e sub-mostra. Por favor, tente novamente.");
                }
            });
        }
    };
    EventPopulate.defaults = EventPopulate.prototype.defaults;
    $.fn.eventPopulate = function(options) {
        return this.each(function() {
            new EventPopulate(this, options).init();
        });
    };
    window.EventPopulate = Plugin;
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
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            console.log(_that.config);
            var $fileElement;
            if (_that.config.fileId) {
                $fileElement = $("#" + _that.config.fileId, _that.$element);
            } else {
                $fileElement = $("#file", _that.$element);
            }
            $fileElement.on("change", function() {
                console.log("bla");
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
                $fileElement.val("");
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

(function(window, $) {
    var ImageCollection = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    ImageCollection.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            $(document).on("change.bs.fileinput clear.bs.fileinput reset.bs.fileinput", ".fileinput", function() {
                $(this).find(".image-collection-id").val("");
                $(this).find(".image-collection-src").val("");
            });
            _that.$element.find(".image-collection-add").on("click", function(e) {
                e.preventDefault();
                var id = Math.floor(Date.now() / 1e3);
                var template = $("#image-collection-template").clone().html();
                template = template.replace(/__index__/g, id);
                _that.$element.find(".image-collection-items").append(template).filter(".fileinput").fileinput();
            });
            $(document).on("click", ".image-collection-delete", function(e) {
                e.preventDefault();
                var el = $(this).closest(".image-collection-item").remove();
            });
        }
    };
    ImageCollection.defaults = ImageCollection.prototype.defaults;
    $.fn.imageCollection = function(options) {
        return this.each(function() {
            new ImageCollection(this, options).init();
        });
    };
    window.ImageCollection = Plugin;
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

(function(window, $) {
    var ListActions = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    ListActions.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            console.log(_that.config);
            _that.initCheckIntens();
            _that.initFormActions();
        },
        initFormActions: function() {
            var _that = this;
            var $form = $(_that.config.form);
            var $actionOptions = $(".list-actions-options");
            var $actionSubmit = $(".list-actions-submit");
            $actionSubmit.on("click", function(e) {
                e.preventDefault();
                var $checkItens = $('input[name="id[]"]');
                var totalChecked = $checkItens.filter(":checked").length;
                if (!totalChecked) {
                    bootbox.dialog({
                        message: "Nenum item foi selecionado. É necessário efetuar a seleção de pelo menos um item da lista",
                        title: "Atenção",
                        buttons: {
                            success: {
                                label: "OK",
                                className: "red",
                                callback: function() {
                                    return;
                                }
                            }
                        }
                    });
                    return;
                }
                var $selectedOption = $actionOptions.find("option:selected");
                if ($selectedOption.val() == "") {
                    bootbox.dialog({
                        message: "É necessário selecionar uma ação",
                        title: "Atenção",
                        buttons: {
                            success: {
                                label: "OK",
                                className: "red",
                                callback: function() {
                                    return;
                                }
                            }
                        }
                    });
                    return;
                }
                var $selected = $('input[name="selected"]');
                if ($selected.val() != "all") {
                    var seletedItens = [];
                    $('input[name="id[]"]:checked').each(function() {
                        seletedItens.push($(this).val());
                    });
                    $selected.val(seletedItens.toString());
                }
                var filterForm = $form.serialize();
                var $action = $("option:selected", $actionOptions);
                if ($action.attr("data-modal")) {
                    var $modal = $($action.attr("data-modal"));
                    if ($modal) {
                        $modal.find('input[name="filter"]').val(filterForm);
                        $modal.modal();
                    }
                }
            });
        },
        initCheckIntens: function() {
            var _that = this;
            var $checkAll = $("input.select-all");
            var $checkItens = $('input[name="id[]"]');
            var ignoreCheckAllEvent = false;
            var ignoreCheckItemEvent = false;
            $checkAll.on("ifChecked", function() {
                if (!ignoreCheckAllEvent) {
                    $checkItens.iCheck("check");
                }
                ignoreCheckAllEvent = false;
            });
            $checkAll.on("ifUnchecked", function() {
                if (!ignoreCheckAllEvent) {
                    $checkItens.iCheck("uncheck");
                }
                ignoreCheckAllEvent = false;
            });
            $checkItens.on("ifChanged", function(e) {
                if (!ignoreCheckItemEvent) {
                    var totalChecked = $checkItens.filter(":checked").length;
                    _that.processChecked(totalChecked);
                }
            });
            $(document).on("click", ".select-all-list", function(e) {
                e.preventDefault();
                $checkItens.iCheck("check");
                _that.processChecked(_that.config.total);
            });
        },
        processChecked: function(totalChecked) {
            var _that = this;
            var $infoContainer = $(".list-actions-selected");
            var $form = $(_that.config.form);
            var $checkItens = $('input[name="id[]"]');
            if (totalChecked > 0) {
                var txt = totalChecked + " itens selecionados nesta página.";
                if (totalChecked < _that.config.total) {
                    txt += '<a href="#" class="select-all-list">Selecionar todos os ' + _that.config.total + " registros disponíveis.</a>";
                    $('input[name="selected"]').val("");
                } else if (totalChecked == _that.config.total) {
                    txt = "Todos os " + _that.config.total + " registros disponíveis estão selecionados.";
                    $('input[name="selected"]').val("all");
                }
                $infoContainer.html(txt);
            } else {
                $infoContainer.text("");
                $('input[name="selected"]').val("");
            }
        },
        getCheckedValues: function() {
            var $checkItens = $('input[name="id[]"]');
        }
    };
    ListActions.defaults = ListActions.prototype.defaults;
    $.fn.listActions = function(options) {
        return this.each(function() {
            new ListActions(this, options).init();
        });
    };
    window.ListActions = Plugin;
})(window, jQuery);

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
        content_css: [ "//www.tinymce.com/css/codepen.min.css" ],
        relative_urls: false,
        remove_script_host: false,
        external_filemanager_path: "/filemanager/",
        filemanager_title: "Responsive Filemanager",
        external_plugins: {
            filemanager: "/filemanager/plugin.min.js"
        }
    });
    tinymce.init({
        selector: ".tinymce_minimal",
        height: 250,
        directionality: "ltr",
        plugins: [ "autolink link lists hr anchor", "searchreplace wordcount visualblocks visualchars insertdatetime nonbreaking", "table contextmenu directionality paste, fullscreen" ],
        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist",
        toolbar2: "link unlink anchor | fullscreen",
        content_css: [ "//www.tinymce.com/css/codepen.min.css" ],
        relative_urls: false,
        remove_script_host: false
    });
    $(":input").inputmask();
    $(".post-sidebar-options").postStatus();
    $(".slug-container").slug();
    $(".post-list").postListStatus();
    $(".post-sites").postSites();
    $(".report-link").report();
    $("form.default-form-actions").formSave();
    $(".cep").cep();
    $(".state-cities").cities();
    $(".password-generator").passwordGenerator();
    $(".programing-table").programing();
    $(".workshop-pontuation").workshopPontuation();
    $(".movie-session").movieSession();
    $(".table-programing").tablePrograming();
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
            autoclose: true,
            minuteStep: 5,
            showSeconds: false,
            showMeridian: false
        });
    }
    $("form.enable-validators").formValidation();
    $(".dd").nestable();
    $(".nestable-session-movie").nestable({
        maxDepth: 0,
        rootClass: "nestable-session-movie",
        listNodeName: "ol"
    });
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
    $("select.select2, .select2-multiple").select2({
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
    $(".fileinput2").fileInput();
    $(".list-actions").listActions();
    $(".admin-phone").adminPhone();
    $(".user-modal").user();
    $(".image-collection").imageCollection();
    $("#user-dependents").adminDependents();
    $("#post-url-btn").on("click", function(e) {
        e.preventDefault();
        if (copyToClipboard(document.getElementById("post-url"))) {}
    });
    new Clipboard(".data-copy");
    $(".movie-programing-form .event-populate").on("change", function(e) {
        var selected = $(this).find("option:selected").val();
        var form = $(".movie-programing-form");
        var validate = form.validate();
        validate.destroy();
        form.append($('<input type="hidden" name="no-validate" value="no-validate">'));
        form.submit();
        App.blockUI({
            cenrerY: true,
            animate: true
        });
    });
    $(".registration-form select[name=type]").on("change", function(e) {
        var selected = $(this).find("option:selected").val();
        var form = $("#registration-form");
        form.append($('<input type="hidden" name="no-validate" value="no-validate">'));
        form.submit();
        App.blockUI({
            cenrerY: true,
            animate: true
        });
    });
    $(".movie-form #registration").on("change", function() {
        var form = $(".movie-form"), validate = form.validate();
        validate.destroy();
        form.append($('<input type="hidden" name="no-validate" value="no-validate">'));
        form.submit();
        App.blockUI({
            cenrerY: true,
            animate: true
        });
    });
    $("#movie-programing-type").on("change", function() {
        var selected = $(this).find("option:selected").val();
        var form = $(".movie-programing-form");
        var validate = form.validate();
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
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
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
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    var succeed;
    try {
        succeed = document.execCommand("copy");
    } catch (e) {
        succeed = false;
    }
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }
    if (isInput) {
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        target.textContent = "";
    }
    return succeed;
}

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
                var form = $('<form method="POST">').append($('<input name="menu">').val(JSON.stringify(menuSerialize)));
                $(document.body).append(form);
                form.submit();
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
    console.log("responsive_filemanager_callback " + field_id);
    var field = $("#" + field_id);
    console.log(field);
    field.trigger("change");
    return;
}

(function(window, $) {
    var MovieSession = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    MovieSession.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            $(".movie-session-add", _that.$element).on("click", function(e) {
                e.preventDefault();
                var movieSelected = $('select[name="movie"] option:selected', _that.$element);
                if (movieSelected.val() == "") {
                    return;
                }
                _that.addNode(movieSelected.val(), movieSelected.text());
            });
            $(document).on("click", ".movie-session-remove", function(e) {
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
            $(".action-save").on("click", function(e) {
                e.preventDefault();
                App.blockUI({
                    cenrerY: true,
                    animate: true
                });
                var session = $(".dd").nestable("serialize");
                var form = $(".movie-programing-form");
                form.find('input[name="sessions"]').val(JSON.stringify(session));
            });
        },
        addNode: function(id, text) {
            var _that = this;
            var el = $('<li class="dd-item" data-id="' + id + '">').append('<div class="item-controls"><a class="movie-session-remove" role="button">excluir</a></div>').append('<div class="dd-handle"><span class="item-title">' + text + "</span></div>");
            $(".dd-list", _that.$element).append(el);
        }
    };
    MovieSession.defaults = MovieSession.prototype.defaults;
    $.fn.movieSession = function(options) {
        return this.each(function() {
            new MovieSession(this, options).init();
        });
    };
    window.MovieSession = Plugin;
})(window, jQuery);

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
    var AdminPhone = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    AdminPhone.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options);
            var _that = this;
            $(".admin-phone-add").on("click", function(e) {
                e.preventDefault();
                console.log("Tentando incluir telefone");
                var ddd = _that.$element.find("#admin-phone-ddd");
                var number = _that.$element.find("#admin-phone-number");
                var name = _that.$element.find("#admin-phone-contact_name");
                var tipo = _that.$element.find("#admin-phone-type");
                if (ddd.val() == "" || number.val() == "") {
                    console.log("Nenhum telefone adicionado");
                    return;
                }
                var id = Math.floor(Date.now() / 1e3);
                var newLine = $('<tr data-row="' + id + '"></tr>').append("<td>" + ddd.val() + "</td>").append("<td>" + number.val() + "</td>").append("<td>" + name.val() + "</td>").append("<td>" + tipo.find("option:selected").text() + "</td>").append($("<td></td>").append('<a href="#" class="btn btn-sm btn-default admin-phone-remove" data-remove="' + id + '"><i class="glyphicon glyphicon-remove"></i></a>').append('<input type="hidden" name="phones[' + id + '][ddd]" value="' + ddd.val() + '">').append('<input type="hidden" name="phones[' + id + '][number]" value="' + number.val() + '">').append('<input type="hidden" name="phones[' + id + '][contact_name]" value="' + name.val() + '">').append('<input type="hidden" name="phones[' + id + '][type]" value="' + tipo.val() + '">'));
                _that.$element.find("tbody > tr:last").before(newLine);
                ddd.val("");
                number.val("");
                name.val("");
                tipo.val("");
            });
            $(document).on("click", ".admin-phone-remove", function(e) {
                e.preventDefault();
                var row = $(document).find('tr[data-row="' + $(this).data("remove") + '"]');
                row.fadeOut("slow", function() {
                    row.remove();
                });
            });
        }
    };
    AdminPhone.defaults = AdminPhone.prototype.defaults;
    $.fn.adminPhone = function(options) {
        return this.each(function() {
            new AdminPhone(this, options).init();
        });
    };
    window.AdminPhone = Plugin;
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
    var Programing = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    Programing.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options);
            var _that = this;
            $element.find(".programing-add").on("click", function(e) {});
        }
    };
    Programing.defaults = Programing.prototype.defaults;
    $.fn.programing = function(options) {
        return this.each(function() {
            new Programing(this, options).init();
        });
    };
    window.Programing = Plugin;
})(window, jQuery);

(function(window, $) {
    var Report = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    Report.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            _that.$element.on("click", function(e) {
                e.preventDefault();
                var $form = null, urlReport = _that.$element.data("url"), reportFilter = "";
                if (_that.$element.data("form")) {
                    var $form = $(_that.$element.data("form"));
                    reportFilter = $form.serialize();
                }
                App.blockUI({
                    cenrerY: true,
                    animate: true
                });
                console.log(urlReport);
                $.ajax({
                    type: "GET",
                    url: urlReport,
                    data: reportFilter,
                    success: function(data) {
                        if (data.error) {
                            _that.erroMessage(data.error);
                        } else if (data.report) {
                            _that.successMessage(data.report);
                            App.unblockUI();
                        } else {
                            _that.erroMessage("Erro ao gerar relatório. Por favor, tente novamente.");
                        }
                    },
                    error: function() {
                        _that.erroMessage("Erro ao gerar relatório. Por favor, tente novamente.");
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
        },
        successMessage: function(urlReport) {
            var $reportResult = $("#report_result");
            $(".report-result-action.download", $reportResult).attr("href", urlReport);
            $reportResult.modal();
        }
    };
    Report.defaults = Report.prototype.defaults;
    $.fn.report = function(options) {
        return this.each(function() {
            new Report(this, options).init();
        });
    };
    window.Report = Plugin;
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
            $(_that.config.inputTarget).on("change", function(e) {
                console.log($(this).val());
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

(function(window, $) {
    var TabSelection = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    TabSelection.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            $('a[data-toggle="tab"]').on("shown.bs.tab", function(e) {
                localStorage.setItem("lastTab", $(this).attr("href"));
            });
            var lastTab = localStorage.getItem("lastTab");
            if (lastTab) {
                $("a[href=" + lastTab + "]").tab("show");
            } else {
                $('a[data-toggle="tab"]:first').tab("show");
            }
        }
    };
    TabSelection.defaults = TabSelection.prototype.defaults;
    $.fn.tabSelection = function(options) {
        return this.each(function() {
            new TabSelection(this, options).init();
        });
    };
    window.TabSelection = Plugin;
})(window, jQuery);

(function(window, $) {
    var TablePrograming = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    TablePrograming.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            $(".table-programing-add", _that.$element).on("click", function(e) {
                e.preventDefault();
                var valid = true;
                _that.$element.find('input[data-required="required"], select[data-required="required"]').each(function() {
                    if ($(this).val() == "") {
                        $(this).css({
                            border: "1px solid red"
                        });
                        valid = false;
                    }
                });
                if (!valid) {
                    return;
                }
                var id = Math.floor(Date.now() / 1e3), date = _that.$element.find('input[name="date"]'), start = _that.$element.find('input[name="start_time"]');
                end = _that.$element.find('input[name="end_time"]');
                place = _that.$element.find('select[name="place"]');
                _that.$element.find("tbody > tr:last").before($('<tr data-row="' + id + '"></tr>').append("<td>" + date.val() + "</td>").append("<td>" + start.val() + "</td>").append("<td>" + end.val() + "</td>").append("<td>" + place.find("option:selected").text() + "</td>").append($("<td></td>").append('<a href="#" class="btn btn-sm btn-default table-programing-remove" data-remove="' + id + '"><i class="fa fa-close"></i></a>').append('<input type="hidden" name="programing[' + id + '][date]" value="' + date.val() + '">').append('<input type="hidden" name="programing[' + id + '][start_time]" value="' + start.val() + '">').append('<input type="hidden" name="programing[' + id + '][end_time]" value="' + end.val() + '">').append('<input type="hidden" name="programing[' + id + '][place]" value="' + place.val() + '">')));
                date.val("").attr("style", "");
                end.val("").attr("style", "");
                start.val("").attr("style", "");
                place.val("").attr("style", "");
            });
            $(document).on("click", ".table-programing-remove", function(e) {
                e.preventDefault();
                var row = $(document).find('tr[data-row="' + $(this).data("remove") + '"]');
                row.fadeOut("slow", function() {
                    row.remove();
                });
            });
        }
    };
    TablePrograming.defaults = TablePrograming.prototype.defaults;
    $.fn.tablePrograming = function(options) {
        return this.each(function() {
            new TablePrograming(this, options).init();
        });
    };
    window.TablePrograming = Plugin;
})(window, jQuery);

(function(window, $) {
    var User = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
        this.$modal = $("#user-modal");
    };
    User.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            _that.$element.on("click", function(e) {
                e.preventDefault();
                _that.$modal.empty();
                App.blockUI({
                    cenrerY: true,
                    animate: true
                });
                var data = {};
                if (_that.config.userId) {
                    data.id = _that.config.userId;
                }
                if (_that.config.hasOwnProperty("viewOnly")) {
                    data.viewOnly = 1;
                }
                var request = $.ajax({
                    type: "GET",
                    url: urlUserModal,
                    data: jQuery.param(data),
                    dataType: "html"
                });
                request.done(function(content) {
                    _that.$modal.attr("style", "");
                    _that.$modal.html(content);
                    _that.$modal.modal();
                    _that.registerEvents();
                    App.unblockUI();
                });
            });
        },
        registerEvents: function() {
            var _that = this;
            _that.$modal.find("#user-modal-paginator a").on("click", function(e) {
                e.preventDefault();
                var src = $(this).attr("href");
                if (src == "#") {
                    return;
                }
                App.blockUI({
                    cenrerY: true,
                    animate: true,
                    target: _that.$modal
                });
                var request = $.ajax({
                    type: "GET",
                    url: src,
                    dataType: "html"
                });
                request.done(function(content) {
                    _that.$modal.html(content);
                    _that.registerEvents();
                    App.unblockUI();
                });
            });
            var formSearch = _that.$modal.find(".user-search");
            formSearch.find('button[type="submit"]').on("click", function(e) {
                e.preventDefault();
                App.blockUI({
                    cenrerY: true,
                    animate: true,
                    target: _that.$modal
                });
                formSearch.append($('<input type="hidden" name="peform-filter"/>'));
                var src = urlUserModal;
                if (_that.config.userId) {
                    src += "?id=" + _that.config.userId;
                }
                var request = $.ajax({
                    type: "GET",
                    url: src,
                    data: formSearch.serialize(),
                    dataType: "html"
                });
                request.done(function(content) {
                    _that.$modal.html(content);
                    _that.registerEvents();
                    _that.resizeModal();
                    App.unblockUI();
                });
            });
            _that.$modal.find(".user-modal-select").on("click", function(e) {
                e.preventDefault();
                var el = $(this), name = el.data("name"), id = el.data("id");
                if (_that.config.selectIdTo) {
                    var selectIdTo = $(_that.config.selectIdTo);
                    selectIdTo.val(id);
                }
                if (_that.config.selectNameTo) {
                    var selectNameTo = $(_that.config.selectNameTo);
                    selectNameTo.val(name);
                }
                _that.$element.data("user-id", id);
                _that.$modal.modal("hide");
                _that.$modal.on("hidden.bs.modal", function(e) {
                    _that.$modal.empty();
                    _that.$element.off();
                    _that.$element.user();
                });
            });
        },
        resizeModal: function() {
            var _that = this, newMargin = _that.$modal.height() / 2;
            _that.$modal.css("margin-top", "-" + newMargin + "px");
        }
    };
    User.defaults = User.prototype.defaults;
    $.fn.user = function(options) {
        return this.each(function() {
            new User(this, options).init();
        });
    };
    window.User = Plugin;
})(window, jQuery);

(function(window, $) {
    var WorkshopPontuation = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };
    WorkshopPontuation.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            var $checkItens = $('input[name="pontuation[]"]');
            $checkItens.on("ifChanged", function(e) {
                var total = 0;
                $checkItens.filter(":checked").each(function() {
                    total += $(this).data("value");
                });
                $(".workshop-pontuation-total").text(total);
            });
            console.log("Pontuação oficina iniciado");
        }
    };
    WorkshopPontuation.defaults = WorkshopPontuation.prototype.defaults;
    $.fn.workshopPontuation = function(options) {
        return this.each(function() {
            new WorkshopPontuation(this, options).init();
        });
    };
    window.WorkshopPontuation = Plugin;
})(window, jQuery);