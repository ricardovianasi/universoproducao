//Cep
(function(window, $) {
	var ImageCollection = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	ImageCollection.prototype = {
		defaults: {},
		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;

			$(document).on('change.bs.fileinput clear.bs.fileinput reset.bs.fileinput','.fileinput', function() {
				$(this).find('.image-collection-id').val('');
				$(this).find('.image-collection-src').val('');
			});

			_that.$element.find('.image-collection-add').on('click', function(e) {
				e.preventDefault();

				var id = Math.floor(Date.now() / 1000);
				var template = $('#image-collection-template').clone().html();

				template = template.replace(/__index__/g, id);
				_that.$element
					.find('.image-collection-items')
					.append(template);
				
				$('[data-id="'+id+'"] .fileinput').fileInput();
				$(document).find('.responsivefilemanager').modalResponsiveFileManager();
			});

			$(document).on('click', '.image-collection-delete', function(e) {
				e.preventDefault();
				var el = $(this).closest('.image-collection-item').remove();
			});
		}
	}

	ImageCollection.defaults = ImageCollection.prototype.defaults;

	$.fn.imageCollection = function(options) {
		return this.each(function () {
			new ImageCollection(this, options).init();
		});
	};

	window.ImageCollection = Plugin;

})(window, jQuery);