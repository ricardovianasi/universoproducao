(function(window, $) {
	var Banner = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	Banner.prototype = {
		defaults: {
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;

			//add new item
			_that.$element.find(_that.config.input).on('change', function(e) {
				var val = $(this).val();
				console.log(val);
				if(!val) {
					return;
				}

				App.blockUI({
					cenrerY: true,
					animate: true
				});

				$.ajax({
					type: 'POST',
					url: _that.config.url,
					data: 'media='+val,
					success: function(data) {
						if(data.error) {
							alert(data.error);
						} else {
							_that.$element.find('.gallery-items').append(data.item);
							$('.icheck').iCheck({
								checkboxClass: 'icheckbox_minimal-grey',
							});
							App.unblockUI();
						}
					},
					error: function() {
						alert('Não foi inserir um novo item. Por favor, tente novamente.');
					}
				});
			});

			console.log(_that.element);

			//remove
			$(document).on('click', '.gallery-item-action-remove', function(e) {
				e.preventDefault();

				var target = $(this).closest('tr');
				target.css({'border-color': 'red', 'background': '#FFB5B5'});

				target.fadeOut("slow", function() {
					target.remove();
				});
			});
		}
	}

	Banner.defaults = Banner.prototype.defaults;

	$.fn.banner = function(options) {
		return this.each(function () {
			new Banner(this, options).init();
		});
	};

	window.Banner = Plugin;

})(window, jQuery);