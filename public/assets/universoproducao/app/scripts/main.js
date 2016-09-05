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

    activateOverlay.setWrapper('.site__wrapper');
    activateOverlay.registerAllowedModal('#mainmenu-modal')

    $(document).on("click", "#mainmenu-acces,#mainmenu-close,#mainmenu-access-active", function (e) {
        e.preventDefault();
        var _this = this,
            $this = $(_this);

        activateOverlay.intelligentToggle($this, '#mainmenu-modal', function(e) {
            $("#mainmenu-modal").perfectScrollbar();
            $('body').addClass("mainmenu-modal-active");
        });
    });

    $(document).on('keydown', null, 'esc', function () {
        $(".overlay").length > 0 && activateOverlay.forceRemove();
    });
});