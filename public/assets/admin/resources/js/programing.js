(function(window, $) {
	var Programing = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	Programing.prototype = {
		defaults: {
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options);
			var _that = this;

			$element.find('.programing-add').on('click', function(e) {
					
			});
		}
	};

	Programing.defaults = Programing.prototype.defaults;

	$.fn.programing = function(options) {
		return this.each(function() {
			new Programing(this, options).init();
		});
	};

	window.Programing = Plugin;

})(window, jQuery);