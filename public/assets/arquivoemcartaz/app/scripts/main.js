$(document).ready(function () {
    $("body").on("click", "a[href='']", function (e) {
        e.preventDefault();
    });

    $.stellar({
        responsive: true,
    });

    $(".gallery__list").owlCarousel({
        items: 1,
        nav: true,
        navText: [
            "<button class='gallery__arrow gallery__arrow--left'><span class='icon icon-arrow-left'></span></button>",
            "<button class='gallery__arrow gallery__arrow--right'><span class='icon icon-arrow-right'></span></button>"
        ],
        dots: false,
        mouseDrag: false
    });

    $(".banner").sullivanBanner({
        items: ".banner__item"
    });

    $(".place").place({
        items: ".place__item"
    });

    $(document).on("click", "#mainmenu-access", function (e) {
        e.preventDefault();
        $("body").toggleClass("mainmenu-active");
    });

});