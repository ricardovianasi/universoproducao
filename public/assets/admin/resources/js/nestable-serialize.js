//Cep
(function(window, $) {
	var NestableSerialize = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	NestableSerialize.prototype = {
		defaults: {
			addEventSelector: '.nestable-serialize-add',
			removeEventSelector: '.nestable-serialize-remove',
			fieldSelector: '.nestable-serialize-input',
			serializeFieldSelector: '.nestable-serialize-serialized',
			formSelector: '.nestable-serialize-form',
			serializeEventSelector: '.nestable-serialize-submit'
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;

			//add event
			$(_that.config.addEventSelector, _that.$element).on('click', function(e) {
				e.preventDefault();
				

				var $field = $(_that.config.fieldSelector, _that.$element);
				if(!$field) {
					return;
				}

				var value = '',
					label = '';
				if($field.is('select')) {
					value = $field.children('option:selected').val();
					label = $field.children('option:selected').text();
				}
				
				if(!value) {
					return;
				}

				_that.addNode(value, label);
				$field.val('')			;
			});

			//remove node
			$(document).on('click', _that.config.removeEventSelector, function(e) {
				e.preventDefault();
				
				var target = $(this).closest('.dd-item');
				target.children('.dd-handle').first().css({'border-color': 'red', 'background': '#FFB5B5'});

				target.slideUp(function() {
					target.remove();
				});
			});

			//Save
			$(_that.config.serializeEventSelector).on('click', function(e) {
				e.preventDefault();

				console.log('save');

				var session = $('.dd').nestable('serialize')
					form = $(_that.config.formSelector)
					inputSerialize = $(_that.config.serializeFieldSelector);

				console.log(form);

				if(!inputSerialize) {
					return;
				}
				
				inputSerialize.val(JSON.stringify(session))
			});
			
		},
		addNode: function(id, text) {
			var _that = this;

			var el = $('<li class="dd-item" data-id="'+id+'">')
				.append('<div class="item-controls"><a class="'+_that.config.removeEventSelector.replace('.', '')+'" role="button">excluir</a></div>')
				.append('<div class="dd-handle"><span class="item-title">'+text+'</span></div>');

			$('.dd-list', _that.$element).append(el);
		}
	}

	NestableSerialize.defaults = NestableSerialize.prototype.defaults;

	$.fn.nestableSerialize = function(options) {
		return this.each(function () {
			new NestableSerialize(this, options).init();
		});
	};

	window.NestableSerialize = Plugin;

})(window, jQuery);