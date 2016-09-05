(function(window, $) {
	var PostThumb = function(options) {
		this.options = options;
	};

	PostThumb.prototype = {
		defaults: {
			'btnAdd': ".post-sidebar-thumb-action-add",
			'btnRemove': '.post-sidebar-thumb-action-remove',
			'inputElement': "#returnResponsivefilemanager",
			'imgContainer': '.post-sidebar-thumb-img'
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options);
			var _that = this;

			$(_that.config.inputElement).on('change', function(e) {
				if($(this).val()) {
					var img = $("<img width='100%' src='"+$(this).val()+"'>");
					$(_that.config.imgContainer).empty().append(img);

					$(_that.config.btnRemove).show();
				}
			});

			$(_that.config.btnRemove).on('click', function(e) {
				e.preventDefault();
				$(_that.config.btnRemove).hide();
				$(_that.config.imgContainer).empty();
				$(_that.config.inputElement).val('');
				return;
			});
			
		}
	}

	PostThumb.defaults = Slug.prototype.defaults;

	$.fn.postThumb = function(options) {
		return new PostThumb(options).init();
	};

	window.PostThumb = Plugin;

})(window, jQuery);