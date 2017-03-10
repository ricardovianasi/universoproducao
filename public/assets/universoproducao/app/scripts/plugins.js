(function(window, $) {
    var Modal = function(element, options) {
        this.element = element;
        this.$element = $(element);
        this.options = options;
    };

    Modal.prototype = {
        defaults: {
        },

        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$element.data());
            var _that = this;
            $(this.config.open).on('click', function(e) {
                e.preventDefault();
                _that.openModal();
            });

            $('.modal-close').on('click', function (e) {
                e.preventDefault();
                _that.closeModal();
            })
        },
        openModal: function () {
            var _that = this;

            $('body').css({
                top: 0,
                position: 'fixed'
            });

            _that.$element.css({
                width: '100%',
                height: '100%',
                visibility: 'visible',
                opacity: 1
            });
        },
        closeModal: function() {
            var _that = this;

            $('body').css({
                position: 'relative'
            });

            _that.$element.css({
                width: '0',
                height: '0',
                visibility: 'hidden',
                opacity: 0
            });
        }
    }

    Modal.defaults = Modal.prototype.defaults;

    $.fn.modal = function(options) {
        return this.each(function () {
            new Modal(this, options).init();
        });
    };

    window.Modal = Plugin;

})(window, jQuery);

(function(window, $) {
    var ChannelSlide = function(element, options) {
        this.slide = element;
        this.$slide = $(element);
        this.$content = this.$slide.find('.channel-content'),
        this.$trick = this.$slide.find('.trick'),
        this.options = options;
    };

    ChannelSlide.prototype = {
        defaults: {
            scaling: 1.80
        },

        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.$slide.data());

            var _that = this;
            /*var windowWidth = $(window).width();
            var slideWidth = _that.$slide.width();
            var slideHeight = _that.$slide.height();*/

            _that.$slide.mouseover(function () {
               console.log('hover');

               //O conteúdo está ativo
               _that.$slide.addClass('hover');

            });
        },
    }

    ChannelSlide.defaults = ChannelSlide.prototype.defaults;

    $.fn.channelSlide = function(options) {
        return this.each(function () {
            new ChannelSlide(this, options).init();
        });
    };

    window.ChannelSlide = Plugin;
})(window, jQuery);