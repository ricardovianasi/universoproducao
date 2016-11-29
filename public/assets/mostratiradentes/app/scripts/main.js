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

    $(".banner-items").owlCarousel({
        items: 1,
        autoHeight:true,
        nav: false,
        navText: [
            "<button class='gallery__arrow gallery__arrow--left'><span class='icon icon-arrow-left'></span></button>",
            "<button class='gallery__arrow gallery__arrow--right'><span class='icon icon-arrow-right'></span></button>"
        ],
        dots: false,
        mouseDrag: false,
        lazyLoad: false,
        loop: true,
        center: true,
        autoplay: true,
        autoplayTimeout: 7000,
        animateOut: 'fadeOut'
    });

    $(".gallery-list").owlCarousel({
        items: 1,
        autoWidth: true,
        autoHeight:true,
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
        lazyLoad: false,
        loop: true
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
            }
        }
    });
});