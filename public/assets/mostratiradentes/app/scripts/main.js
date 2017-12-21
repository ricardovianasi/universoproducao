(function($) {
    /**
     * jQuery function to prevent default anchor event and take the href * and the title to make a share pupup
     *
     * @param  {[object]} e           [Mouse event]
     * @param  {[integer]} intWidth   [Popup width defalut 500]
     * @param  {[integer]} intHeight  [Popup height defalut 400]
     * @param  {[boolean]} blnResize  [Is popup resizeabel default true]
     */
    $.fn.customerPopup = function (e, intWidth, intHeight, blnResize) {

        // Prevent default anchor event
        e.preventDefault();

        // Set values for window
        intWidth = intWidth || "500";
        intHeight = intHeight || "400";
        var strResize = (blnResize ? "yes" : "no");
    }
})(jQuery);

$(document).ready(function () {
    $("body").on("click", "a[href='']", function (e) {
        e.preventDefault();
    });

    $(".banner-items").owlCarousel({
        items: 1,
        nav: false,
        navText: [
            "<button class='gallery__arrow gallery__arrow--left'><span class='icon icon-arrow-left'></span></button>",
            "<button class='gallery__arrow gallery__arrow--right'><span class='icon icon-arrow-right'></span></button>"
        ],
        dots: false,
        mouseDrag: false,
        lazyLoad: false,
        loop: false,
        center: true,
        autoplay: true,
        autoplayTimeout: 7000,
        animateOut: 'fadeOut'
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
        animateOut: 'fadeOut',
        autoWidth: true
    });

    $(".gallery-list").owlCarousel({
        items: 1,
        autoWidth: true,
        nav: true,
        navText: [
            "<button class='owl-prev'><span class='icon icon-arrow-left4'></span></button>",
            "<button class='owl-next'><span class='icon icon-arrow-right4'></span></button>"
        ],
        dots: false,
        mouseDrag: false,
        loop: true,
        center: true,
        autoplay: false,
        autoplayTimeout: 7000,
        lazyLoad: true,
    });

    var timeline = $(".events").owlCarousel({
        items: 3,
        center: false,
        autoWidth: true,
        autoHeight:true,
        nav: true,
        navContainer: ".timeline-navigation",
        navText: [
            "<a href='' class='circle-button'><i class='icon icon-arrow-left4'></i></a>",
            "<a href='' class='circle-button'><i class='icon icon-arrow-right4'></i></a>"
        ],
        dots: false,
        mouseDrag: true,
        autoplay: false,
        lazyLoad: false,
        loop: false,
        stageElement: "ol",
        itemElement: "li"
    });

    timeline.on("translate.owl.carousel", function (e) {
        if(e.item.index == 0) {
            $(".timeline").removeClass("stage2").addClass("active stage1");
        } else if(e.item.index > 0) {
            $(".timeline").removeClass("stage1").addClass("active stage2");
        }
    })

    $("#timeline-active").on("click", function (e) {
        e.preventDefault();
        $(".timeline").toggleClass("active stage1");
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

    $("#menu-button").on("click", function(e) {
        e.preventDefault();
        $(".menu").toggleClass("menu-open");
    })

});