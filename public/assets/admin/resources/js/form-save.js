//Cep
(function(window, $) {
	var FormSave = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	FormSave.prototype = {
		defaults: {
			'submitTrigger':'button[type="submit"]',
			'cancelTrigguer': '.form-action-cancel',
			'enableValidators': false
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;

			_that.$element.find(_that.config.submitTrigger).on('click', function(e) {
				e.preventDefault();

				var isValid = true;
				if(_that.$element.hasClass("enable-validators") || _that.config.enableValidators) {
					isValid = _that.$element.valid()
				}

				if(isValid) {
					App.blockUI({
						cenrerY: true,
						animate: true
					});

					setTimeout(function() {
						$(_that.$element).submit();
					}, 1500);
				}

			});

			_that.$element.find(_that.config.cancelTrigguer).on('click', function(e) {
				e.preventDefault();
				
				var _link = $(this);
				//Alerta
				bootbox.dialog({
					message: "Tem certeza que deseja descartar as alterações?",
					title: "Atenção",
					buttons: {
						success: {
							label: "OK",
							className: "red",
							callback: function() {
								App.blockUI({
									cenrerY: true,
									animate: true
								});

								window.location.href = _link.attr('href');
								return;
							}
						},
						danger: {
							label: "Cancelar",
							className: "blue",
							callback: function() {
								return;
							}
						}
					}
				});

			});
		}
	}

	FormSave.defaults = FormSave.prototype.defaults;

	$.fn.formSave = function(options) {
		return this.each(function () {
			new FormSave(this, options).init();
		});
	};

	window.FormSave = Plugin;

})(window, jQuery);