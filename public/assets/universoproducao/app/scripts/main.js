$(document).ready(function() {
    $('#banner-home').owlCarousel({
        navigation: true,
        singleItem: true,
        autoHeight: false,
        items: 1,
        center: true,
        loop: true,
        autoplay: true,
        animateOut: 'fadeOut',
        mouseDrag: false,
        autoplaySpeed: 6000
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

    $('.channel-slider--home').owlCarousel({
        nav: true,
        dots: false,
        margin: 8,
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

    $('.channel-slider').owlCarousel({
        nav: true,
        dots: false,
        margin: 8,
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
                items: 5
            }
        }
    });

    $('.modal').modal();

    $('#search-btn').on('click', function (e) {
        e.preventDefault();
        console.log('hello');
        $('.search').toggleClass('search-active');
    })

    function mouse_bottom() {
        $('#mouse-bottom').animate({
            'bottom': '0'
        }, 400).animate({
            'bottom': '-5px'
        }, 800, mouse_bottom);

        $('#mouse-bottom').on('click', function(e) {
            e.preventDefault();
            $(window).scrollTo( $('#news'), {duration: 200});
        });
    }
    mouse_bottom();
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