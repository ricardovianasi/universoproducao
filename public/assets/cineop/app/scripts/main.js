$(document).ready(function () {

    activateOverlay.setWrapper('.site-wrapper');
    activateOverlay.registerAllowedModal('#mainmenu-modal')

    $(document).on("click", "#mainmenu-access,#mainmenu-access-active", function (e) {
            e.preventDefault();
            var _this = this,
                $this = $(_this);
        
            activateOverlay.intelligentToggle($this, '#mainmenu-modal', function() {

                $(".mainmenu__navigation-wrapper").perfectScrollbar();

                var header = $('.header .header-left').clone();
                header.addClass('header-left--mainmenu').find("#mainmenu-access").attr("id", "mainmenu-access-active");
                $("#mainmenu-modal .mainmenu__navigation-wrapper").prepend(header.addClass('').css({"display":"none", "top":"0", "position":"absolute"}));
                header.fadeIn('slow');
                $('body').addClass("mainmenu-modal-active");
            });
    });
    // $("#mainmenu-modal").on('activeoverlay.active', function(e) {
    //     e.preventDefault();
    //     var _this = this,
    //         $this = $(_this);
    //
    //     var header = $('.header .header-left').clone();
    //     header.find("#mainmenu-access").attr("id", "mainmenu-access-active");
    //     $this.find('.mainmenu__wrapper').append(header.css({"display":"block", "top":"0", "position":"absolute"}));
    // });
    $("#mainmenu-modal").on('activeoverlay.gone', function(e) {
        e.preventDefault();
        var _this = this,
            $this = $(_this);

        $this.find('.header-left').detach();
    });

    $(document).on('keydown', null, 'esc', function () {
        $(".overlay").length > 0 && activateOverlay.forceRemove();
    });

    $("body").on("click", "a[href='']", function (e) {
        e.preventDefault();
    });

    $("#back-to-top").scrollSlide({offset: ['.header', '.breadcrumbs']});

    $(".home-slider__list").slick({
        autoplay: true,
        infinite: true,
        arrows: false,
        slide: 'li',
        draggable: false,
        dots: true,
        appendDots: '.home-slider__markers-container',
        dotsClass: 'home-slider__markers',
        fade: true,
        adaptiveHeight: true,
        mobileFirst: true
    });
});