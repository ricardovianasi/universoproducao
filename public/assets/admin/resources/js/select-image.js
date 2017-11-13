(function(window, $) {
	var SelectImage = function(options) {
		this.options = options;
	};
	SelectImage.prototype = {
		defaults: {
			"container": ".select-image",
			"btnAdd": ".select-image-add",
			"btnRemove": ".select-image-remove",
			"inputTarget": ".select-image-input",
			"imgContainerTarget": ".select-image-container"
		},
		init: function() {
			this.config = $.extend({}, this.defaults, this.options);
			var _that = this;
			$(_that.config.inputTarget).on('change', function(e) {
				console.log($(this).val());
				if($(this).val()) {
					var img = $("<img width='100%' height='200px' src='"+$(this).val()+"'>");
					$(_that.config.imgContainerTarget).empty().append(img);
					$(_that.config.container).addClass("select-image-has-image");
				}
			});
			$(_that.config.btnRemove).on("click", function(e) {
				e.preventDefault();
				$(_that.config.imgContainerTarget).empty();
				$(_that.config.inputTarget).val("");
				$(_that.config.container).removeClass("select-image-has-image");
				return;
			});
		}
	}
	SelectImage.defaults = Slug.prototype.defaults;
	$.fn.selectImage = function(options) {
		return new SelectImage(options).init();
	};
	window.SelectImage = Plugin;
})(window, jQuery);