$(document).ready(function() {
    $('#banner-home').owlCarousel({
        navigation: true,
        singleItem: true,
        autoHeight: false,
        items: 1,
        center: true,
        loop: true,
        autoplay: false,
        animateOut: 'fadeOut',
        mouseDrag: false,
        autoplaySpeed: 8000
    });

    $('.project--caroucel .owl-carousel').owlCarousel({

        items: 1,
        center: true,
        loop: true,
        autoplay: false,
        mouseDrag: true,
        nav: true,
        margin: 10,
        navText: [
            '<button><span class="icon icon-arrow-left4"></span></button>',
            '<button><span class="icon icon-arrow-right4"></span></button>'
        ],
        responsive: {
            0: {
                stagePadding: 30,
                autoWidth: false
            },
            480: {
                stagePadding: 0,
                autoWidth: true
            }
        }
    });

    $('.channel-slider').owlCarousel({
        nav: true,
        dots: false,
        margin: 4,
        navText: [
            '<button><span class="icon icon-arrow-left4"></span></button>',
            '<button><span class="icon icon-arrow-right4"></span></button>'
        ],
        mouseDrag: false,
        responsive: {
            0: {
                items: 1,
            },
            320: {
                items: 2
            },
            768: {
                items: 3
            },
            1024: {
                items: 4
            },
            1280: {
                items: 5
            },
            1600: {
                items: 6
            }
        }
    });

    $('.modal').modal();

    $('.channel-item').channelSlide();

    /*$(".channel-item").hover(function() {
        var $this = $(this),
            $container = $this.find('.channel-content'),
            offset = $this.offset(),
            width = 45, //im percent
            height: 45; //im percent

        if(!$container.hasClass('visible')) {
            $container.addClass('visible').css({
                width: '190%',
                height: '190%',
                visibility: 'visible',
                top: '-45%',
                left: function() {
                    if(($this.offset().left-(($container.width()*45)/100)) > 0) {
                        return '-45%'
                    } else {
                        return 0;
                    }
                },
                opacity: 0
            }).animate({
                opacity: 1
            }, 300)
        }
    });

    $('.channel-content').hover(function(){}, function() {
        var $this = $(this);

        $this.removeClass('visible').animate({
            opacity: 0
        }, 300, function() {
            $this.css({
                width: '',
                height: '',
                visibility: '',
                top: '',
                left: ''
            })
        })
    });*/
});

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