//Cep
(function(window, $) {
	var TabSelection = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	TabSelection.prototype = {
		defaults: {
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;
			
			$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        		// save the latest tab; use cookies if you like 'em better:
		        localStorage.setItem('lastTab', $(this).attr('href'));
		    });

			var lastTab = localStorage.getItem('lastTab');
			if (lastTab) {
				$('a[href=' + lastTab + ']').tab('show');
			} else {
				// Set the first tab if cookie do not exist
				$('a[data-toggle="tab"]:first').tab('show');
			}
		}
	}

	TabSelection.defaults = TabSelection.prototype.defaults;

	$.fn.tabSelection = function(options) {
		return this.each(function () {
			new TabSelection(this, options).init();
		});
	};

	window.TabSelection = Plugin;

})(window, jQuery);