(function(window, $) {
	var WorkshopPontuation = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	WorkshopPontuation.prototype = {
		defaults: {},
		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;

			var $checkItens = $('input[name="pontuation[]"]');
			$checkItens.on('ifChanged', function(e) {
				var total = 0;
				$checkItens.filter(':checked').each(function() {
					total+= $(this).data('value');
				});
				$('.workshop-pontuation-total').text(total);
			});

			console.log('Pontuação oficina iniciado');
		}
	}

	WorkshopPontuation.defaults = WorkshopPontuation.prototype.defaults;

	$.fn.workshopPontuation = function(options) {
		return this.each(function () {
			new WorkshopPontuation(this, options).init();
		});
	};

	window.WorkshopPontuation = Plugin;

})(window, jQuery);