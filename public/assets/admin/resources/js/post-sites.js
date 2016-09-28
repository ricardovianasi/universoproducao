//Cep
(function(window, $) {
	var PostSites = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	PostSites.prototype = {
		defaults: {
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;
			var form = _that.$element.find('.post-site-form');

			//adiciona
			_that.$element.find('.post-site-form .action.add').on('click', function(e) {
				e.preventDefault();
				var site = form.find('select[name="sites-enabled"]').select2("data");
				var siteId = site[0].id;
				var siteTitle = site[0].text;
				var highlight = form.find('input[name="site-highlight"]').is(':checked');
				if(siteId == "") {
					return;
				}

				if(_that.isExist(siteId)) {
					return;
				}

				App.blockUI({
					cenrerY: true,
					animate: true
				});

				var table = _that.$element.find('.post-sites-table tbody');
				if(highlight) {
					siteTitle = siteTitle+'<span class="label label-sm label-success"> Destaque </span>';
				}
				table.append("<tr class='post-sites-item' data-id='"+siteId+"'>\
					<td>"+siteTitle+"</td>\
					<td>\
					<button class='btn btn-sm btn-default action remove'><i class='fa fa-close'></i></button>\
					<input type='hidden' name='sites["+siteId+"][id]' value='"+siteId+"' />\
					<input type='hidden' name='sites["+siteId+"][highlight]' value='"+highlight+"' /></td>");


				setTimeout(function() {
					App.unblockUI();
					form.find('select[name="sites-enabled"]').val(null).trigger("change");
					form.find('input[name="site-highlight"]').iCheck('uncheck');
				}, 1000);
			});

			//remove
			$(document).on('click', '.post-sites-table .post-sites-item .action.remove', function(e){
				e.preventDefault();
				var item = $(this).closest('.post-sites-item')
				item.fadeOut("slow", function() {
					item.remove();
				});
			});

			//remove todos
			_that.$element.find('.post-sites-table .action.remove-all').on('click', function(e) {
				e.preventDefault();
				_that.$element.find('.post-sites-table .post-sites-item').remove();
			});
		},
		isExist: function(siteId) {
			var _that = this;
			if(_that.$element.find('.post-sites-table .post-sites-item[data-id="'+siteId+'"]').length > 0) {
				return true;
			}

			return false;
		}
	}

	PostSites.defaults = PostSites.prototype.defaults;

	$.fn.postSites = function(options) {
		return this.each(function () {
			new PostSites(this, options).init();
		});
	};

	window.PostSites = Plugin;

})(window, jQuery);