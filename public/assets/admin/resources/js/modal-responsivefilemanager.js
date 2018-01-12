(function(window, $) {
	var ModalResponsiveFileManager = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	ModalResponsiveFileManager.prototype = {
		defaults: {
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;
			var modal = $(_that.config.target);

			_that.$element.on('click', function(e) {
				e.preventDefault();

				console.log(_that.config.url);

				App.blockUI({
					cenrerY: true,
					animate: true
				});

				var ifr=$('<iframe/>', {
					src: _that.config.url,
					style: "width: 100%; min-height: 600px",
					frameborder: 0,
					load: function() {
						App.unblockUI();
						modal.modal('show');
					}
				});

				modal.find('.modal-body').empty().html(ifr);

				modal.on('hide.bs.modal	', function (e) {
					App.unblockUI();
					modal.find('.modal-body').empty();
				});
			});
		}
	}

	ModalResponsiveFileManager.defaults = ModalResponsiveFileManager.prototype.defaults;

	$.fn.modalResponsiveFileManager = function(options) {
		return this.each(function () {
			new ModalResponsiveFileManager(this, options).init();
		});
	};

	window.ModalResponsiveFileManager = Plugin;

})(window, jQuery);

function responsive_filemanager_callback(field_id) {
    var field = $("#"+field_id);
    field.trigger('change');
    return;
}