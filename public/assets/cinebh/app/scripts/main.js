$(document).ready(function () {
    $("body").on("click", "a[href='']", function (e) {
        e.preventDefault();
    });

    $(".banner__items").owlCarousel({
        items: 1,
        nav: false,
        navText: [
            "<button class='gallery__arrow gallery__arrow--left'><span class='icon icon-arrow-left'></span></button>",
            "<button class='gallery__arrow gallery__arrow--right'><span class='icon icon-arrow-right'></span></button>"
        ],
        dots: true,
        mouseDrag: false,
        lazyLoad: true,
        loop: true,
        center: true,
        autoplay: true,
        autoplayTimeout: 7000,
        animateOut: 'fadeOut'
    });

    $(".gallery__list").owlCarousel({
        items: 1,
        autoWidth:true,
        nav: true,
        navText: [
            "<button class='gallery__arrow gallery__arrow--left'><span class='icon icon-arrow-left4'></span></button>",
            "<button class='gallery__arrow gallery__arrow--right'><span class='icon icon-arrow-right4'></span></button>"
        ],
        dots: false,
        mouseDrag: false,
        loop: true,
        center: true,
        autoplay: false,
        autoplayTimeout: 7000
    });

    $("#mainmenu-access, .mainmenu-close").on("click", function(e) {
        e.preventDefault();
        $("body").toggleClass("mainmenu-active");
    });

    $(".customer-share").on("click", function(e) {
        $(this).customerPopup(e);
    });
});