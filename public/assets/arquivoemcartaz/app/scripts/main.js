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
        navigation: false,
        responsive: {
            1024: {
                autoHeight: true
            },
            900: {
                autoHeight: false,
                autoWidth: false
            }
        }
    });

    $(".place").place({
        items: ".place__item"
    });

    $(document).on("click", "#mainmenu-access", function (e) {
        e.preventDefault();
        $("body").toggleClass("mainmenu-active");
    });

    $(".customer-share").on("click", function(e) {
        $(this).customerPopup(e);
    });

    $(".search-link").on("click", function(e) {
        e.preventDefault();
        $(".search form").submit();
    });

});