$(document).ready(function () {

    $("body").on("click", "a[href='']", function (e) {
        e.preventDefault();
    });
    
    $(".home-slider").slick({
        dots: true,
        arrows: false,
        autoplay: true,
        autoplaySpeed: 4000,
        infinite: true,
        fade: true,
        draggable: false,
        appendDots: '.home-slider-dots',
        dotsClass: 'home-slider-dots__list',
    });

    $(window).load(function() {
        $('.home-slider .home-slider__slide--img').height($(window).height());
    });
    $(window).resize(function() {
        $('.home-slider .home-slider__slide--img').height($(window).height());
    });

    $(document).on("click", "#mainmenu-access, #mainmenu-close", function (e) {
        e.preventDefault();
        $("body").toggleClass("mainmenu-active");
    });

    $(document).on("click", "#usermenu-access, #userlogin-close", function (e) {
        e.preventDefault();
        $("body").toggleClass("usermenu-active");
    });

    $(document).on("click", "#contact-access, #contact-close", function (e) {
        e.preventDefault();
        $("body").toggleClass("contact-active");
    });
});