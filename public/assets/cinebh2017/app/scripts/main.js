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
    $('.guide-bg').parallax({imageSrc: '../images/cidade.jpg'});


    $('#menu-button').fancybox({
        arrows: false,
        margin: 0,
        smallBtn: false,
        toolbar: false

    });
});