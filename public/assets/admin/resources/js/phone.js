(function(window, $) {
	var AdminPhone = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	AdminPhone.prototype = {
		defaults: {
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options);
			var _that = this;

			$('.admin-phone-add').on('click', function(e) {
				e.preventDefault();

				console.log("Tentando incluir telefone");

				var ddd = _that.$element.find('#admin-phone-ddd');
				var number = _that.$element.find('#admin-phone-number');
				var name = _that.$element.find('#admin-phone-contact_name');
				var tipo = _that.$element.find('#admin-phone-type');

				if(ddd.val() == "" || number.val() == "") {
					console.log("Nenhum telefone adicionado");
					return;
				}

				var id = Math.floor(Date.now() / 1000);
				var newLine = $('<tr data-row="'+id+'"></tr>')
					.append('<td>'+ddd.val()+'</td>')
					.append('<td>'+number.val()+'</td>')
					.append('<td>'+name.val()+'</td>')
					.append('<td>'+tipo.find('option:selected').text()+'</td>')
					.append($('<td></td>')
							.append('<a href="#" class="btn btn-sm btn-default admin-phone-remove" data-remove="'+id+'"><i class="glyphicon glyphicon-remove"></i></a>')
							.append('<input type="hidden" name="phones['+id+'][ddd]" value="'+ddd.val()+'">')
							.append('<input type="hidden" name="phones['+id+'][number]" value="'+number.val()+'">')
							.append('<input type="hidden" name="phones['+id+'][contact_name]" value="'+name.val()+'">')
							.append('<input type="hidden" name="phones['+id+'][type]" value="'+tipo.val()+'">'));

				_that.$element.find('tbody > tr:last').before(newLine);

				ddd.val('');
				number.val('');
				name.val('');
				tipo.val('');
			});

			$(document).on('click', '.admin-phone-remove', function(e) {
				e.preventDefault();

				var row = $(document).find('tr[data-row="'+$(this).data('remove')+'"]');
				row.fadeOut("slow", function() {
					row.remove();
				});

			});
		}
	};

	AdminPhone.defaults = AdminPhone.prototype.defaults;

	$.fn.adminPhone = function(options) {
		return this.each(function() {
			new AdminPhone(this, options).init();
		});
	};

	window.AdminPhone = Plugin;

})(window, jQuery);