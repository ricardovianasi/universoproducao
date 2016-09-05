//Cep
(function(window, $) {
	var Cep = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	Cep.prototype = {
		defaults: {
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;

			_that.$element.find('#searchcep').on('click', function(e) {
				e.preventDefault();

				var cepValue = _that.$element.find('input[name="cep"]').val();
				if(!cepValue) {
					return;
				}

				App.blockUI({
					cenrerY: true,
					animate: true
				});
				$.ajax({
					type: 'POST',
					url: _that.config.urlCep,
					data: 'cep='+cepValue,
					success: function(data) {
						if(data.error) {
							_that.erroMessage(data.error);
						} else {
							$(_that.config.form+' select[name="state"]').val(data.cep.state);
							$(_that.config.form+' select[name="city"]').empty().append(data.cep.cities).val(data.cep.city);

							$(_that.config.form+' input[name="address"]').val(data.cep.address);
							$(_that.config.form+' input[name="district"]').val(data.cep.district);

							App.unblockUI();
						}
					},
					error: function() {
						_that.erroMessage('Não foi possível localizar o cep informado. Por favor, tente novamente ou informe o endereço manualmente.');
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

	Cep.defaults = Cep.prototype.defaults;

	$.fn.cep = function(options) {
		return this.each(function () {
			new Cep(this, options).init();
		});
	};

	window.Cep = Plugin;

})(window, jQuery);