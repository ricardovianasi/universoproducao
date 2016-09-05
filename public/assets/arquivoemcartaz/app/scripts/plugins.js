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
                $coversContainer.append(
                    $("<figure class='banner__cover'>")
                        .css({"background-image": $item.css("background-image")})
                        .attr("data-index", count)
                        .attr("data-state", "none").append(bannercontent.removeClass("banner__content").addClass("banner__content-cover"))
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
            $item.on("click", function(e) {
                e.preventDefault();
                var onClick = $(this);

                $this.find(options.items).removeClass("place__item--active");
                onClick.addClass("place__item--active");

                $this.find(".place__desc-title").text(onClick.attr("data-title"));
                $this.find(".place__desc-text").text(onClick.attr("data-desc"));
                $this.find(".place__image").fadeOut(400, function() {
                    $this.find(".place__image").attr("src", onClick.attr("data-image"));
                }).fadeIn(400);
            });
        });
    };
})(jQuery);