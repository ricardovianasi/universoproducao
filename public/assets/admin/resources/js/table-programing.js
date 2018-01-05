//Cep
(function(window, $) {
	var TablePrograming = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	TablePrograming.prototype = {
		defaults: {
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;

			//add
			$('.table-programing-add', _that.$element).on('click', function(e) {
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
					date = _that.$element.find('input[name="date"]'),
					start = _that.$element.find('input[name="start_time"]')
					end = _that.$element.find('input[name="end_time"]')
					place = _that.$element.find('select[name="place"]');

				_that.$element.find('tbody > tr:last').before($('<tr data-row="'+id+'"></tr>')
					.append('<td>'+date.val()+'</td>')
					.append('<td>'+start.val()+'</td>')
					.append('<td>'+end.val()+'</td>')
					.append('<td>'+place.find('option:selected').text()+'</td>')
					.append($('<td></td>')
						.append('<a href="#" class="btn btn-sm btn-default table-programing-remove" data-remove="'+id+'"><i class="fa fa-close"></i></a>')
						.append('<input type="hidden" name="programing['+id+'][date]" value="'+date.val()+'">')
						.append('<input type="hidden" name="programing['+id+'][start_time]" value="'+start.val()+'">')
						.append('<input type="hidden" name="programing['+id+'][end_time]" value="'+end.val()+'">')
						.append('<input type="hidden" name="programing['+id+'][place]" value="'+place.val()+'">')
					)
				);

				date.val('').attr('style', '');
				end.val('').attr('style', '')
				start.val('').attr('style', '');
				place.val('').attr('style', '');
			});

			$(document).on('click', '.table-programing-remove', function(e) {
				e.preventDefault();
				var row = $(document).find('tr[data-row="'+$(this).data('remove')+'"]');
				row.fadeOut("slow", function() {
					row.remove();
				});
			});
		}
	}

	TablePrograming.defaults = TablePrograming.prototype.defaults;

	$.fn.tablePrograming = function(options) {
		return this.each(function () {
			new TablePrograming(this, options).init();
		});
	};

	window.TablePrograming = Plugin;

})(window, jQuery);