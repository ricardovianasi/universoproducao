"use strict";
$(document).ready(function () {
    $("body").on("click", "a[href='']", function (o) {
        o.preventDefault()
    }), $("#banner-home").owlCarousel({
        autoHeight: !0,
        autoplay: !0,
        autoplayTimeout: 7e3,
        animateOut: "fadeOut",
        center: !0,
        dots: !0,
        items: 1,
        loop: !0,
        nav: !1,
        navigation: !1,
        responsive: {1024: {autoHeight: !0}, 900: {autoHeight: !1, autoWidth: !1}}
    }), $("#programation-highlights").owlCarousel({
        navigation: !1,
        nav: !1,
        responsiveClass: !0,
        responsive: {0: {items: 1, center: !0, dots: !0, loop: !0}, 900: {items: 4, loop: !1, dots: !1, mouseDrag: !1}}
    }), $("#news-highlight").owlCarousel({
        navigation: !1,
        nav: !1,
        responsiveClass: !0,
        responsive: {0: {items: 1, center: !0, dots: !0, loop: !0}, 900: {items: 3, loop: !1, dots: !1, mouseDrag: !1}}
    }), $("#gallery").owlCarousel({
        autoWidth: !0,
        autoplay: !1,
        center: !0,
        items: 1,
        lazyLoad: !1,
        loop: !0,
        navigation: !1,
        navText: ["<button><span class='icon icon-arrow-left4'></span></button>", "<button><span class='icon icon-arrow-right4'></span></button>"],
        responsive: {0: {dots: !0, nav: !1}, 900: {dots: !1, nav: !0}}
    }), $(".mainmenu-mobile-btn").on("click", function (o) {
        o.preventDefault(), $("body").toggleClass("mainmenu-mobile-active")
    });

    $(".fancybox").fancybox({
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

    $("a[href$='.jpg'],a[href$='.jpeg'],a[href$='.png'],a[href$='.gif'],a.winners").fancybox({
        padding : 0,
        openEffect  : 'elastic',
        helpers: {
            overlay: {
                locked: false
            }
        }
    });

    $(".movie-carousel").owlCarousel({
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