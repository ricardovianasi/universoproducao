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
        intWidth = intWidth || '500';
        intHeight = intHeight || '400';
        var strResize = (blnResize ? 'yes' : 'no');

        // Set title and open popup with focus on it
        var strTitle = ((typeof this.attr('title') !== 'undefined') ? this.attr('title') : 'Social Share'),
            strParam = 'width=' + intWidth + ',height=' + intHeight + ',resizable=' + strResize,
            objWindow = window.open(this.attr('href'), strTitle, strParam).focus();
    }
})(jQuery);
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
        lazyLoad: false,
        loop: true,
        center: true,
        autoplay: true,
        autoplayTimeout: 7000,
        animateOut: 'fadeOut'
    });

    $(".gallery__list").owlCarousel({
        items: 1,
        autoWidth: true,
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
        autoplayTimeout: 7000,
        lazyLoad: false,
        loop: true
    });

    $("#mainmenu-access, .mainmenu-close").on("click", function(e) {
        e.preventDefault();
        $("body").toggleClass("mainmenu-active");
    });

    $(".customer-share").on("click", function(e) {
        $(this).customerPopup(e);
    });

    $(".mainmenu-bgc").perfectScrollbar();

    $("#usermenu__search").on('click', function(e) {
        e.preventDefault();
        $(".mainmenu__search").toggleClass("active");
    });
});