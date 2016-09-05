//Cep
(function(window, $) {
	var PasswordGenerator = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	PasswordGenerator.prototype = {
		defaults: {
			'buttonTrigger': 'button',
			'input': 'input[name="temp-pass"]'
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;

			_that.$element.find(_that.config.buttonTrigger).on('click', function(e) {
				e.preventDefault();

				App.blockUI({
					cenrerY: true,
					animate: true
				});

				$.ajax({
					type: 'GET',
					url: _that.config.url,
					success: function(data) {
						if(data.error) {
							_that.erroMessage(data.error);
						} else {
							_that.$element.find(_that.config.input).val(data.password);
							App.unblockUI();
						}
					},
					error: function() {
						_that.erroMessage('Não foi possível gerar a senha. Por favor, tente novamente.');
					}
				});
			});
				
		},
		erroMessage: function(msg) {
			bootbox.dialog({
	            message: msg,
	            title: "Atenção",
	            buttons: {
	            	success: {
	            		label: "OK",
	                	className: "blue",
	                	callback: function() {
                    		App.unblockUI();
                    	}
	              	}
	            }
	        });
		}
	}

	PasswordGenerator.defaults = PasswordGenerator.prototype.defaults;

	$.fn.passwordGenerator = function(options) {
		return this.each(function () {
			new PasswordGenerator(this, options).init();
		});
	};

	window.PasswordGenerator = Plugin;

})(window, jQuery);