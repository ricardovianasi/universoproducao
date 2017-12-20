(function(window, $) {
	var AdminDependents = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	AdminDependents.prototype = {
		defaults: {
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options);
			var _that = this;

			_that.$element.find('input[data-required="required"], select[data-required="required"]').each(function() {
				$(this).on('change', function(e) {
					if($(this).val() != "") {
						$(this).css({
							border: ''
						});
					}
				}) 
			});

			_that.$element.find('.user-dependents-add').on('click', function(e) {
				e.preventDefault();

				var valid = true;
				_that.$element.find('input[data-required="required"], select[data-required="required"]').each(function() {
					if($(this).val() == "") {
						$(this).css({
							border: '1px solid red'
						});
						valid = false;
					}
				});

				if(!valid) {
					return;
				}

				var id = Math.floor(Date.now() / 1000),
					name = _that.$element.find('#dependent_name'),
					birth = _that.$element.find('#dependent_birth_date')
					identifier = _that.$element.find('#dependent_identifier')
					gender = _that.$element.find('#dependent_gender');

				_that.$element.find('tbody > tr:last').before($('<tr data-row="'+id+'"></tr>')
					.append('<td>'+name.val()+'</td>')
					.append('<td>'+birth.val()+'</td>')
					.append('<td>'+identifier.val()+'</td>')
					.append('<td>'+gender.find('option:selected').text()+'</td>')
					.append($('<td></td>')
						.append('<a href="#" class="btn btn-sm btn-default user-dependents-remove" data-remove="'+id+'"><i class="fa fa-close"></i></a>')
						.append('<a href="#" class="btn btn-sm btn-default user-dependents-edit" data-edit="'+id+'"><i class="fa fa-pencil"></i></a>')
						.append('<input type="hidden" name="dependents['+id+'][name]" value="'+name.val()+'">')
						.append('<input type="hidden" name="dependents['+id+'][birth_date]" value="'+birth.val()+'">')
						.append('<input type="hidden" name="dependents['+id+'][identifier]" value="'+identifier.val()+'">')
						.append('<input type="hidden" name="dependents['+id+'][gender]" value="'+gender.val()+'">')
					)
				);

				name.val('');
				birth.val('');
				identifier.val('');
				gender.val('');
			});
				

			$(document).on('click', '.user-dependents-remove', function(e) {
				e.preventDefault();
				var row = $(document).find('tr[data-row="'+$(this).data('remove')+'"]');
				row.fadeOut("slow", function() {
					row.remove();
				});
			});

			$(document).on('click', '.user-dependents-edit', function(e) {
				e.preventDefault();
				var row = $(document).find('tr[data-row="'+$(this).data('edit')+'"]');
				console.log($(document).find('input[name^="dependents['+$(this).data('edit')+']"]'));
			});
		}
	};

	AdminDependents.defaults = AdminDependents.prototype.defaults;

	$.fn.adminDependents = function(options) {
		return this.each(function() {
			new AdminDependents(this, options).init();
		});
	};

	window.AdminDependents = Plugin;

})(window, jQuery);