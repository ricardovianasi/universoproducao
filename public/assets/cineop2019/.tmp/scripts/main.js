'use strict';

$(document).ready(function () {

    $('body').on('click', 'a[href=\'\']', function (e) {
        e.preventDefault();
    });

    $('#banner-home').owlCarousel({
        autoHeight: true,
        autoplay: true,
        autoplayTimeout: 7000,
        animateOut: 'fadeOut',
        center: true,
        dots: true,
        items: 1,
        loop: true,
        nav: false,
        navigation: false

    });

    $('#programation-highlights').owlCarousel({
        navigation: false,
        nav: false,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                center: true,
                dots: true,
                loop: true
            },
            900: {
                items: 4,
                loop: false,
                dots: false,
                mouseDrag: false
            }
        }
    });

    $('#news-highlight').owlCarousel({
        navigation: false,
        nav: false,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                center: true,
                dots: true,
                loop: true
            },
            900: {
                items: 3,
                loop: false,
                dots: false,
                mouseDrag: false
            }
        }

    });

    $('#gallery').owlCarousel({
        autoWidth: true,
        autoplay: false,
        center: true,
        items: 1,
        lazyLoad: false,
        loop: true,
        navigation: false,
        navText: ['<button><span class=\'icon icon-arrow-left4\'></span></button>', '<button><span class=\'icon icon-arrow-right4\'></span></button>'],
        responsive: {
            0: {
                dots: true,
                nav: false
            },
            900: {
                dots: false,
                nav: true
            }
        }
    });

    $('.mainmenu-mobile-btn').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('mainmenu-mobile-active');
    });

    $('.movie-carousel').owlCarousel({
        items: 1,
        // autoHeight:true,
        nav: false,
        video: true,
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

    $('.fancybox').fancybox({
        padding: 0,
        openEffect: 'elastic',
        maxWidth: 700,
        maxHeight: 700,
        width: '100%',
        height: '100%',
        helpers: {
            overlay: {
                locked: false
            },
            title: {
                type: 'outside'
            }
        }
    });

    $('a[href$=".jpg"],a[href$=".jpeg"],a[href$=".png"],a[href$=".gif"],a.winners').fancybox({
        padding: 0,
        openEffect: 'elastic',
        helpers: {
            overlay: {
                locked: false
            }
        }
    });
});
//# sourceMappingURL=main.js.map
