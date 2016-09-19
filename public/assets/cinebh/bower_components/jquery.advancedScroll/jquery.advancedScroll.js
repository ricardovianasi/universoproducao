/*! Author: Luiz Gustavo Freire Gama - @luizgamabh */

'use strict';

(function($) {
    $.fn.advancedScroll = function() {
        return this.each(function() {
            var _this = this,
                $this = $(_this),
                lastScrollTop = $this.scrollTop(),
                startX,
                startY,
                deltaParam = 20,
                touchable = (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch);

            if (_this.self != window.self) return; // Allows only window to use this plugin

            if (touchable) {
                $this.bind('touchstart', touchstart);
                $this.bind('touchend', touchend);
            } else {
                $this.on("scroll", scroll);
            }

            // Handlers
            function scroll(event) {
                var actualScrollTop = $this.scrollTop();
                if (actualScrollTop > lastScrollTop) {
                    $this.trigger("scrolldown", [actualScrollTop]);
                } else {
                    $this.trigger("scrollup", [actualScrollTop]);
                }
                lastScrollTop = actualScrollTop;
            };

            function touchstart(event) {
                var touches = event.originalEvent.touches;
                if (touches && touches.length) {
                    startX = touches[0].pageX;
                    startY = touches[0].pageY;
                    $this.bind('touchmove', touchmove);
                }
            };

            function touchmove(event) {
                var touches = event.originalEvent.touches;

                if (touches && touches.length && touches[0].pageX >1) {
                    var deltaX = startX - touches[0].pageX;
                    var deltaY = startY - touches[0].pageY;
                    if(deltaX == 0){}
                    if (deltaX >= deltaParam) {
                        $this.trigger("scrolleft", [$this.scrollLeft()]);
                    }else
                    if (deltaX <= -deltaParam) {
                        $this.trigger("scrollright", [$this.scrollLeft()]);
                    } else
                    if (deltaY >= deltaParam) {
                        $this.trigger("scrolldown", [$this.scrollTop()]);
                    }else
                    if (deltaY <= -deltaParam) {
                        $this.trigger("scrollup", [$this.scrollTop()]);
                    }
                    if (Math.abs(deltaX) >= deltaParam || Math.abs(deltaY) >= deltaParam) {
                        $this.unbind('touchmove', touchmove);
                        //return false;
                    }

                }
                //event.preventDefault();
            };

            function touchend(event) {
                $this.unbind('touchmove', touchmove);
            };
        });
    };
})(jQuery);

// Uncomment the following line to enable it wherever, or call this on each page you want to use:
// $(window).advancedScroll();