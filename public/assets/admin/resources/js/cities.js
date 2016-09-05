//Cep
(function(window, $) {
	var Cities = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	Cities.prototype = {
		defaults: {
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;

			_that.$element.find(_that.config.stateElement).on('change', function(e) {
				e.preventDefault();

				var stateValue = $(this).val();
				if(!stateValue) {
					return;
				}

				App.blockUI({
					cenrerY: true,
					animate: true
				});

				$.ajax({
					type: 'POST',
					url: _that.config.url,
					data: 'state='+stateValue,
					success: function(data) {
						if(data.error) {
							_that.erroMessage(data.error);
						} else {
							_that.$element.find(_that.config.cityElement).empty().append(data.cities);
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

	Cities.defaults = Cities.prototype.defaults;

	$.fn.cities = function(options) {
		return this.each(function () {
			new Cities(this, options).init();
		});
	};

	window.Cities = Plugin;

})(window, jQuery);