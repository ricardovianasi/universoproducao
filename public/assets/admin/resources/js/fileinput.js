(function(window, $) {
	var FileInput = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};
	FileInput.prototype = {
		defaults: {
		},
		init: function() {
			this.config = $.extend({}, this.defaults, this.options);
			var _that = this;

			console.log(this.$element);

			_that.$element.find("#file").on("change", function() {
				if($(this).val()) {
					_that.$element.addClass("fileinput-exist");

					var img = $("<img width='100%' src='"+$(this).val()+"'>");
					_that.$element.find(".fileinput-preview").empty().append(img).show();
					_that.$element.find(".fileinput-new").hide();
				}
			});

			_that.$element.find(".fileinput-remove").on("click", function(e) {
				_that.$element.find(".fileinput-preview").empty().hide();
				_that.$element.find(".fileinput-new").show();
				_that.$element.addClass("fileinput-exist");
				_that.$element.find("#file").val("");
			});
		}
	}
	FileInput.defaults = FileInput.prototype.defaults;
	$.fn.fileInput = function(options) {
		return this.each(function () {
			new FileInput(this, options).init();
		});
	};
	window.FileInput = Plugin;
})(window, jQuery);
