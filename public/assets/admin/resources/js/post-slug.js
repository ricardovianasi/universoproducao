//slug
(function(window, $) {
	var Slug = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	Slug.prototype = {
		defaults: {
			'btnAction': ".slug-action-edit",
			'inputElement': "#slug"
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;

			_that.process();
			_that.processTarget();

		},
		process: function() {
			var _that = this;
			var slug = $(this.config.inputElement);
			
			$(_that.config.btnAction).on('click', function(e) {
				e.preventDefault();
				slug.removeProp('readonly');
			});

			
		},
		processTarget: function() {
			var _that = this;
			var target = $(this.config.slugtarget);

			target.on('blur', function(e) {
				e.preventDefault();
				if(target.val() === "") {
					return false;
				}

				if(_that.getSlugValue() != "") {
					return false;
				}

				_that.generateSlug(target.val());

			});
		},
		getSlugValue: function() {
			return $(this.config.inputElement).val();
		},
		generateSlug: function(str) {
			var _that = this;
			var data = 'slug='+str;

			$.ajax({
				type: 'POST',
				url: _that.config.slugurlvalidation,
				data: data,
				success: function(data) {
					_that.displaySlug(data.slug);
				},
				error: function() {
					alert('error');
				}
			});
		},
		displaySlug: function(slug) {
			if(slug != "") {
				$(this.config.inputElement).val(slug);
			}
		}
	}

	Slug.defaults = Slug.prototype.defaults;

	$.fn.slug = function(options) {
		return this.each(function () {
			new Slug(this, options).init();
		});
	};

	window.Slug = Plugin;

})(window, jQuery);