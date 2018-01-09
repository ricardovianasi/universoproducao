//Cep
(function(window, $) {
	var TableOrder = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	TableOrder.prototype = {
		defaults: {
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;

			$('.table-order-save', _that.$element).on('click', function(e) {
				e.preventDefault();
				
				var order = $('.programing-table-sortable').sortable('toArray');
				var $form = $('<form method="POST" action="'+_that.config.action+'">')
					.append($('<input type="hidden" name="sort", value="'+order.join(';')+'">'));

				$('body').append($form);
				$form.submit();
				App.blockUI({
					cenrerY: true,
					animate: true
				});
			});
		}
	}

	TableOrder.defaults = TableOrder.prototype.defaults;

	$.fn.tableOrder = function(options) {
		return this.each(function () {
			new TableOrder(this, options).init();
		});
	};

	window.TableOrder = Plugin;

})(window, jQuery);