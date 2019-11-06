//Cep
(function(window, $) {
	var Usercategory = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	Usercategory.prototype = {
		defaults: {
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;

			_that.$element.find(_that.config.categoryElement).on('change', function(e) {
				e.preventDefault();

				var categoryValue = $(this).val();
				if(!categoryValue) {
					return;
					_that.$element.find(_that.config.subcategoryElement).empty();
				}

				App.blockUI({
					cenrerY: true,
					animate: true
				});

				$.ajax({
					type: 'POST',
					url: _that.config.url,
					data: 'category='+categoryValue,
					success: function(data) {
						if(data.error) {
							_that.erroMessage(data.error);
						} else {
							_that.$element.find(_that.config.subcategoryElement).empty().append(data.subcategories);
							App.unblockUI();
						}
					},
					error: function() {
						_that.erroMessage('Não foi possível localizar a categoria informada. Por favor, tente novamente.');
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

	Usercategory.defaults = Usercategory.prototype.defaults;

	$.fn.usercategory = function(options) {
		return this.each(function () {
			new Usercategory(this, options).init();
		});
	};

	window.Usercategory = Plugin;

})(window, jQuery);