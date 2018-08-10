$(document).ready(function () {
    $('body').on('click', 'a[href=""]', function (e) {
        e.preventDefault();
    });

    $('.banner .owl-carousel').imagesLoaded( function() {
        $('.banner .owl-carousel').owlCarousel({
            autoWidth: true,
            autoHeight: true,
            items: 1,
            nav: false,
            dots: true,
            mouseDrag: false,
            lazyLoad: true,
            loop: true,
            center: true,
            autoplay: true,
            autoplayTimeout: 7000,
            animateOut: 'fadeOut'
        });
    });

    $('.gallery .owl-carousel').imagesLoaded( function() {
        $('.gallery .owl-carousel').owlCarousel({
            autoWidth: true,
            autoplay: true,
            animateOut: 'fadeOut',
            autoHeight: true,
            autoplayTimeout: 7000,
            center: true,
            dots: false,
            items: 1,
            lazyLoad: true,
            loop: true,
            mouseDrag: true,
            nav: true,
            navigation: false,
            navText: [
                '<button><span class=\'icon icon-arrow-left4\'></span></button>',
                '<button><span class=\'icon icon-arrow-right4\'></span></button>'
            ],
        });
    });

    $('.fancybox').fancybox({
        padding : 0,
        openEffect  : 'elastic',
        maxWidth	: 700,
        maxHeight	: 700,
        width		: '100%',
        height		: '100%',
        helpers: {
            overlay: {
                locked: false
            },
            title	: {
                type: 'outside'
            }
        }
    });

    jQuery(window).trigger('resize').trigger('scroll');
    $('.guide-bg').parallax();

    $('#menu-button').fancybox({
        arrows: false,
        margin: 0,
        smallBtn: false,
        toolbar: false
    });

    $('#programation-highlight').owlCarousel({
        navigation: false,
        nav: false,
        responsiveClass:true,
        responsive:{
            0: {
                items: 1,
                center: true,
                dots: true,
                loop: true,
            },
            900: {
                items: 4,
                loop:false,
                dots: false,
                mouseDrag: false
            }
        }
    });

    $('.movie-carousel').owlCarousel({
        items: 1,
        // autoHeight:true,
        nav: false,
        video:true,
        /*navText: [
            "<button class='gallery__arrow gallery__arrow--left'><span class='icon icon-arrow-left'></span></button>",
            "<button class='gallery__arrow gallery__arrow--right'><span class='icon icon-arrow-right'></span></button>"
        ],*/
        dots: true,
        mouseDrag: false,
        lazyLoad: false,
        loop: true,
        center: true,
        autoplay: true,
        autoplayTimeout: 7000,
        animateOut: 'fadeOut'
    });
});