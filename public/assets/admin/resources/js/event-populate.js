//Cep
(function(window, $) {
	var EventPopulate = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	EventPopulate.prototype = {
		defaults: {
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;
			
			_that.$element.on('change', function() {
				var stateValue = $(this).val();
				if(!stateValue) {
					return;
				}

				App.blockUI({
					cenrerY: true,
					animate: true
				});
			});

			$.ajax({
					type: 'POST',
					url: _that.config.url,
					data: 'event='+stateValue,
					success: function(data) {
						if(data.error) {
							_that.erroMessage(data.error);
						} else {
							//_that.$element.find(_that.config.cityElement).empty().append(data.cities);
							console.log(data);
							App.unblockUI();
						}
					},
					error: function() {
						_that.erroMessage('Erro ao localizar locais e sub-mostra. Por favor, tente novamente.');
					}
				});
		}
	}

	EventPopulate.defaults = EventPopulate.prototype.defaults;

	$.fn.eventPopulate = function(options) {
		return this.each(function () {
			new EventPopulate(this, options).init();
		});
	};

	window.EventPopulate = Plugin;

})(window, jQuery);