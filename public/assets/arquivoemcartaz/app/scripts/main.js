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

(function($){
    $.fn.sullivanBanner = function(options) {
        options = $.extend({}, options);

        var $this = $(this),
            $coversContainer = $this.find(".banner__covers"),
            $items = $this.find(options.items),
            count = 0;

        $this.on("mouseleave", function() {
            $items.attr("data-state", "none");
            $coversContainer.find(".banner__cover").attr("data-state", "none");
        });

        return $items.each(function() {
            var $item = $(this);

            if(!$item.hasClass("ignore")) {
                var bannercontent = $item.find(".banner__content").clone();
                var figCaption = $("<span>").addClass('banner__caption').text($item.attr('data-caption'));
                $coversContainer.append(
                    $("<figure class='banner__cover'>")
                        .css({"background-image": $item.css("background-image")})
                        .attr("data-index", count)
                        .attr("data-state", "none")
                        .append(figCaption)
                        .append(bannercontent.removeClass("banner__content").addClass("banner__content-cover"))
                );

                $item.on("mouseenter", function(e) {
                    var $itemHover = $(e.target),
                        currentIndex = $itemHover.attr("data-index");

                    $items.attr("data-state", "hidden");
                    $itemHover.attr("data-state", "active");
                    $coversContainer.find("[data-index='"+currentIndex+"']").attr("data-state", "active");
                })
            }

            $item.attr("data-index", count++).attr("data-state", "none");
            $item.on("mouseleave", function(e) {
                $items.attr("data-state", "none");
                $coversContainer.find(".banner__cover").attr("data-state", "none");

            })
        });
    };

    $.fn.place = function(options) {
        options = $.extend({
        }, options);

        var $this = $(this)
        return $this.find(options.items).each(function () {
            var $item = $(this);

            var img = new Image();
            img.src = $item.attr("data-image");

            $item.on("click", function(e) {
                e.preventDefault();
                var onClick = $(this);

                $this.find(options.items).removeClass("place__item--active");
                onClick.addClass("place__item--active");

                $this.find(".place__desc-title").text(onClick.attr("data-title"));
                $this.find(".place__desc-text")
                    .text(onClick.attr("data-desc"))
                    .append($("<a class='news__item-link'>").attr("href", onClick.attr("data-link")).html("<span class='icon icon-arrow-right8'></span>"));

                $this.find(".place__image").fadeOut(400, function() {
                    $this.find(".place__image").attr("src", onClick.attr("data-image"));
                }).fadeIn(400);
            });
        });
    };

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