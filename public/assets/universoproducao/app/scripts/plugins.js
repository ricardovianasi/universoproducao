var DEFAULT_SITE_BREAKS = DEFAULT_SITE_BREAKS || [768];
var DEFAULT_ANIMATION_EASE = DEFAULT_ANIMATION_EASE || $.bez([.87,.37,.27,.95]);
var DEFAULT_ANIMATION_TIME = DEFAULT_ANIMATION_TIME || 600;
var DEFAULT_ANIMATION_DELAY = DEFAULT_ANIMATION_DELAY || 0;
var DEFAULT_ANIMATION_TYPE = DEFAULT_ANIMATION_TYPE || "slide";
var DEFAULT_ANIMATION_ORIENTATION = DEFAULT_ANIMATION_ORIENTATION || "left";
var DEFAULT_ANIMATION_HORIZONTAL = DEFAULT_ANIMATION_HORIZONTAL || true;
var GENERAL_OFFSET = GENERAL_OFFSET || 0; // offset padrão
var GLOBAL_OVERLAY_ACTIVE = false;

// jQuery plugins
(function($){
    $.fn.scrollSlide = function(options) {
        options = $.extend({
            increment: 0,
            offset: 0
        }, options);
        var debug_count = 0,
            visible = false,
            initialPos,
            offset,
            scrollX,
            backupOffset = options.offset, // Somente para ufmg, fora remover
            advancedWindow = advancedWindow || $(window).advancedScroll().advancedBreak(DEFAULT_SITE_BREAKS);
        offset = advancedWindow.getIndex()==0 ? sumOffset(backupOffset) : sumOffset(backupOffset.concat([60]));
        //offset = sumOffset(options.offset);
        return this.each(function(){
            scrollX = window.innerWidth;
            initialPos = $(this).css("margin-top");
            var $this = $(this);
            $this.click(function(e){
                e.preventDefault();
                $("html:not(:animated),body:not(:animated)").animate({ scrollTop: 0 }, DEFAULT_ANIMATION_TIME, DEFAULT_ANIMATION_EASE);
            });
            $(window).on({
                break: function(event, index) { // somente para o mockup da ufmg, fora isso pode remover o evento
                    if (index > 0) options.offset = backupOffset.concat([60]);
                    else options.offset = backupOffset;
                    offset = sumOffset(options.offset);
                },
                resize: function() {
                    if (scrollX === window.innerWidth) return; // Caso o resize seja apenas vertical, ignora
                    scrollX = window.innerWidth;
                    //offset = sumOffset(options.offset);
                    visible = false; // seta como invisível
                    $this.stop(false, false).animate({
                        opacity: '0',
                        'margin-top': initialPos
                    }, 250); // esconde o elemento
                },
                scrollup: function(event, scrollTop) {
                    //$("#res").html('cima');
                    if (!visible && scrollTop > offset) {
                        //exibe
                        visible = true;
                        $this.stop(false, false).animate({
                            opacity: '1',
                            'margin-top': offset + options.increment
                        }, 250);
                    } else if (visible && scrollTop <= offset) {
                        //oculta
                        visible = false;
                        $this.stop(false, false).animate({
                            opacity: '0',
                            'margin-top': initialPos
                        }, 250);
                    }
                },
                scrolldown: function(event, st) {
                    //$("#res").html('baixo');
                    if (visible) {
                        //oculta
                        visible = false;
                        $this.stop(false, false).animate({
                            opacity: '0',
                            'margin-top': initialPos
                        }, 250);
                    }
                }
            });
        });
    };
})(jQuery);

/*!
 * Sum-offset by Luiz Gama
 *
 * Use:
 * sumOffset(['.element', integer, '.element2']);
 * */
var sumOffset = function(data) {
    if (typeof data !== 'number' && (typeof data !== 'object' || !(data instanceof Array))) return 0;
    if (typeof data === 'number') return parseInt(data, 10);
    if (typeof data === 'object') {
        var temp = 0;
        for (var i = 0; i < data.length; i++) {
            temp += typeof data[i] == 'number' ? data[i] : ($(data[i]).length === 0 ? 0 : parseInt($(data[i]).height(), 10));
        }
        return temp;
    }
    return 0;
}

/*!
 * Activate-overlay by Luiz Gama
 *
 * Use:
 * activateOverlay.new(offset, callback);
 * offset can be integer or array of integers and html elements [50, '.header']
 * callback is a function
 * any argument is optional
 * depends of sumOffset function
 * */
var activateOverlay = new function() {
    var _this = this,
        _rawOffset = [],
        _offset = 0,
        _callback = false,
        _wrapper = null,
        _posY = 0,
        _state = false,
        _overlay = null,
        _modals = new Array(),
        _modalsHome = new Array(),
        _lastModal = new Object,
        _activeModal = new Object,
        _pluginDisabled = false,
        RESIZE_MODAL_EVENT = null;

    // Private:
    var _sameModal = function() {
        if ( _lastModal.element && _activeModal.element && _lastModal.element.selector === _activeModal.element.selector ) {
            return true;
        }
        return false;
    };
    var _isActive = function() {
        return $("div.overlay").length>0 && _state;
    };
    var _loadModal = function() {

        _activeModal.element.trigger("activeoverlay.active");
        //console.log(_activeModal.element);

        _activeModal.element.appendTo("body").css(_activeModal.animation.cssParams).delay(_activeModal.animation.delay).animate(_activeModal.animation.params, _activeModal.animation.time, _activeModal.animation.ease, function() {
            _pluginDisabled = false;

            //Caso haja algum callback:
            _callback && _callback();
        });

        _activeModal.button.addClass("active");
        //$('body').addClass(_activeModal.element.attr('id') + '-active');
        RESIZE_MODAL_EVENT || (RESIZE_MODAL_EVENT = $(window).on("break.modal", function(event, breakIndex) {
            _this.updateOffset(_rawOffset);
        }));
    };
    var _goAwayModal = function(old, callback) {
        if (typeof callback === 'undefined') callback = false;
        if (typeof old === 'undefined') old = false;
        if (!old) {
            _activeModal.element.animate(_activeModal.animation.cssParams, _activeModal.animation.time, _activeModal.animation.ease, function() {
                _activeModal.element.detach().appendTo(_modalsHome[_modals.indexOf(_activeModal.element.selector)]).trigger("activeoverlay.gone");
                _activeModal.button.removeClass("active");
                $('body').removeClass(_activeModal.element.attr('id') + '-active');
                _activeModal = {};
                _lastModal = {}; // no caso do activemodal sempre remover o old tb
            });
        }
        else {
            _lastModal.element.animate(_lastModal.animation.cssParams, _lastModal.animation.time, _lastModal.animation.ease, function() {
                _lastModal.element.detach().appendTo(_modalsHome[_modals.indexOf(_lastModal.element.selector)]).trigger("activeoverlay.gone");
                _lastModal.button.removeClass("active");
                callback && callback();
            });
        }
        if (RESIZE_MODAL_EVENT) {
            $(window).off("break.modal");
            RESIZE_MODAL_EVENT = null;
        }
    };
    var _remove = function(old) {
        old = typeof old === 'undefined' ? false : old;
        _goAwayModal(old);
        var overlay = $("div.overlay");
        overlay.fadeOut(DEFAULT_ANIMATION_TIME, DEFAULT_ANIMATION_EASE, function(){
            $(this).remove();
            $(_wrapper).css({
                top: "",
                position: ""
            });
            $(window).scrollTop(_posY);
            // volta os atributos protegidos para o estado inicial
            _callback = false;
            _posY = 0;
            _state = false;
            _overlay = null;
            _pluginDisabled = false;
            RESIZE_MODAL_EVENT = null;
            setTimeout(function(){
                GLOBAL_OVERLAY_ACTIVE = false;
            }, 50);
        });
    };
    var _createOverlay = function() {
        GLOBAL_OVERLAY_ACTIVE = true;
        _state = true;
        if (_isActive()) {
            _overlay = $(".overlay").get(0);
        } else {
            _overlay = document.createElement("div");
            //$(_overlay).addClass("overlay").css("top", _offset+"px").appendTo("body").on("click", function(e){
            //$(_overlay).addClass("overlay").css("top", _offset+"px").hide().appendTo("body").fadeIn(DEFAULT_ANIMATION_TIME, DEFAULT_ANIMATION_EASE).on("click", function(e){
            $(_overlay).addClass("overlay").hide().appendTo("body").fadeTo(DEFAULT_ANIMATION_TIME, 1, DEFAULT_ANIMATION_EASE).on("click", function(e){
                _remove(); // TODO VERIFICAR ESSA PORRA, AS VEZES TEM Q REMOVER O OLD, AS VEZES NÃO, WHAT PORRA IS THAT!!!!!! DEBUGAR!
            });
            _posY = $(window).scrollTop();
        }
        $(_wrapper).css({
            top: -(_posY-_offset)+'px',
            // position: 'fixed'
        });
        //NOT_TODO CRIAR O ON E O OFF PARA MUDAR A POSIÇÃO DO OVERLAY CONFORME O OFFSET
        /*RESIZE_OVERLAY_EVENT || (RESIZE_OVERLAY_EVENT = $(window).on("break.overlay", function(event, breakIndex){

         }));*/
    };

    // Public methods:

    this.updateOffset = function(newOffset) {
        //typeof newOffset === 'undefined' && (newOffset = _rawOffset);
        typeof newOffset !== 'undefined' && (_rawOffset = newOffset);
        if (_isActive()) {
            _offset = sumOffset(_rawOffset) || 0;
            _activeModal.element.css("top", _offset);
            if (_activeModal.animation.horizontal) {
                _activeModal.animation.cssParams.top = _offset;
            }
        }
    };

    this.forceRemove = function() {
        _remove();
    };

    this.setWrapper = function(selector) {
        _wrapper = $(selector).get(0);
    };

    this.registerAllowedModal = function(element) {
        if ($(element).length>0, $.inArray('element', _modals)===-1) {
            _modals.push(element);
            _modalsHome.push($(element).parent());
        }
    };

    this.intelligentToggle = function(button_to_activate, modal, callback) {

        // seta valores default para os parâmetros opcionais
        //if (typeof offset === 'undefined') offset = 0; // Offset padrão é zero, caso não seja informado
        if (typeof callback === 'undefined') callback = false; // callback é opcional, caso não seja informado seta para null

        //Desativa o plugin temporariamente durante sua execução
        if (_pluginDisabled) return;
        _pluginDisabled = true;

        // Valida os campos pelos seus tipos
        if (_wrapper === null || (typeof button_to_activate !== 'Object' && !(button_to_activate instanceof jQuery)) || typeof modal !== 'string') return;

        // OFFSET:
        //_rawOffset = offset;
        _offset = sumOffset(_rawOffset);

        // CALLBACK:
        _callback = callback;

        // MODAL:
        var _modal = modal;
        if ($.inArray(_modal, _modals) !== -1) { // checa se o modal está registrado e se o click NÃO está tentando abrir o mesmo modal que já encontra-se aberto
            // _activeModal é populado:
            _lastModal = $.extend(_lastModal, _activeModal); // O último modal passa a ter os dados do modal atual, no caso de alternância entre modais no mesmo overlay
            _activeModal.element = $(_modal);
            _activeModal.button = button_to_activate;
            _activeModal.width = _activeModal.element.width();
            _activeModal.height = _activeModal.element.height();
            _activeModal.animation = {
                type: _activeModal.element.data("animation") || DEFAULT_ANIMATION_TYPE,
                horizontal: _activeModal.element.data("horizontal") || DEFAULT_ANIMATION_HORIZONTAL,
                orientation: _activeModal.element.data("from") || DEFAULT_ANIMATION_ORIENTATION,
                ease: _activeModal.element.data("ease") || DEFAULT_ANIMATION_EASE,
                time: _activeModal.element.data("time") || DEFAULT_ANIMATION_TIME,
                delay: _activeModal.element.data("delay") || DEFAULT_ANIMATION_DELAY
            };
            _activeModal.animation.params = { visibility: 'show' };
            switch (_activeModal.animation.type) {
                case "slide":
                    if (_activeModal.animation.horizontal) {
                        _activeModal.animation.orientation === "left" && ( _activeModal.animation.cssParams = { left: -_activeModal.width }, _activeModal.animation.params = { left: 0 } ) ||
                        ( _activeModal.animation.cssParams = { right: -_activeModal.width }, _activeModal.animation.params = { right: 0 } );
                        _activeModal.animation.cssParams.top = _offset;
                    } else {
                        _activeModal.animation.cssParams = { top: -_activeModal.height }, _activeModal.animation.params = { top: _offset }
                    }
                    break;
                case "fade":
                default:
                    _activeModal.animation.cssParams = { opacity: 'hide' };
                    _activeModal.animation.params = { opacity: 'show' };
            }

            // Mesmo modal?
            var samemodal, notsamemodal = !(samemodal=_sameModal());

            //Checa se existia outro modal, neste caso fecha o mesmo ;)
            if (_isActive() && notsamemodal) { // alternando entre modais
                _goAwayModal(true, function() {
                    // O elemento é extraído do DOM permanecendo na memória com a mesma id
                    _activeModal.element.detach();
                    //_createOverlay(); // cria um novo overlay, ou apenas mantém o overlay atual, quando alternamos entre modais
                    _loadModal(_callback); // carrega o novo modal e passa o callback para ser chamado
                }); // o parâmetro true indica que fecharemos o modal antigo (old)
            } else if (samemodal) { // removendo ao clicar no mesmo modal
                _remove();
            } else { // criando novo modal (primeiro click)
                _activeModal.element.detach();
                _createOverlay(); // cria um novo overlay, ou apenas mantém o overlay atual, quando alternamos entre modais
                _loadModal(_callback); // carrega o novo modal e passa o callback para ser chamado
            }
        } else { // caso não esteja registrado
            if (_isActive()) _remove();
        }
    };
}

$.fn.scrollSlide = function(options) {
    options = $.extend({
        increment: 0,
        offset: 0
    }, options);
    var debug_count = 0, visible = false, initialPos, offset, scrollX, backupOffset = options.offset, advancedWindow = advancedWindow || $(window).advancedScroll().advancedBreak(DEFAULT_SITE_BREAKS);
    offset = advancedWindow.getIndex() == 0 ? sumOffset(backupOffset) : sumOffset(backupOffset.concat([ 60 ]));
    return this.each(function() {
        scrollX = window.innerWidth;
        initialPos = $(this).css("margin-top");
        var $this = $(this);
        $this.click(function(e) {
            e.preventDefault();
            $("html:not(:animated),body:not(:animated)").animate({
                scrollTop: 0
            }, DEFAULT_ANIMATION_TIME, DEFAULT_ANIMATION_EASE);
        });
        $(window).on({
            "break": function(event, index) {
                if (index > 0) options.offset = backupOffset.concat([ 60 ]); else options.offset = backupOffset;
                offset = sumOffset(options.offset);
            },
            resize: function() {
                if (scrollX === window.innerWidth) return;
                scrollX = window.innerWidth;
                visible = false;
                $this.stop(false, false).animate({
                    opacity: "0",
                    "margin-top": initialPos
                }, 250);
            },
            scrollup: function(event, scrollTop) {
                if (!visible && scrollTop > offset) {
                    visible = true;
                    $this.stop(false, false).animate({
                        opacity: "1",
                        "margin-top": offset + options.increment
                    }, 250);
                } else if (visible && scrollTop <= offset) {
                    visible = false;
                    $this.stop(false, false).animate({
                        opacity: "0",
                        "margin-top": initialPos
                    }, 250);
                }
            },
            scrolldown: function(event, st) {
                if (visible) {
                    visible = false;
                    $this.stop(false, false).animate({
                        opacity: "0",
                        "margin-top": initialPos
                    }, 250);
                }
            }
        });
    });
};