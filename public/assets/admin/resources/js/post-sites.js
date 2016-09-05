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

			_that.$element.find("input[name='publish_all_sites']").on('ifChecked', function(e) {
				e.preventDefault();
				_that.$element.find(".post-site-options").slideUp();
				_that.$element.find("input[name='publish_highlight_all_sites']").iCheck('enable');
			})

			_that.$element.find("input[name='publish_all_sites']").on('ifUnchecked', function(e) {
				e.preventDefault();
				_that.$element.find(".post-site-options").slideDown();
				_that.$element.find("input[name='publish_highlight_all_sites']").iCheck('disable');
			})

			//adiciona
			_that.$element.find('.post-site-form .action.add').on('click', function(e) {
				e.preventDefault();
				var siteId = form.find('select[name="sites-enabled"]').val();
				var highlight = form.find('input[name="site-highlight"]').is(':checked');
				if(siteId == "") {
					//form.find('select[name="sites-enabled"]').closest('.form-group').addClass('has-error')
					return;
				}

				if(_that.isExist(siteId)) {
					return;
				}

				App.blockUI({
					cenrerY: true,
					animate: true
				});

				$.ajax({
					type: 'POST',
					url: _that.config.url,
					data: {site: siteId, highlight: highlight ? 1 : 0},
					success: function(data) {
						if(data.error) {
							alert(data.error);
						} else {
							_that.$element.find('.post-sites-items .post-sites-table').show().append(data.postSite);
							form.find('select[name="sites-enabled"]').val(null).trigger("change");
							form.find('input[name="site-highlight"]').iCheck('uncheck');
						}
					},
					error: function() {
						alert('Erro desconhecido. Tente novamente');
					},
					complete: function() {
						App.unblockUI();
					}
				});
			});

			//remove
			$(document).on('click', '.post-sites-items .post-sites-table .post-sites-item .action.remove', function(e){
				e.preventDefault();
				
				var item = $(this).closest('.post-sites-item')
				item.fadeOut("slow", function() {
					item.remove();
				});

				if(_that.$element.find('.post-sites-items .post-sites-table .post-sites-item').length == 0) {
					_that.$element.find('.post-sites-items .post-sites-table').slideUp();
				}

			});

			//remove todos
			_that.$element.find('.post-sites-table .action.remove-all').on('click', function(e) {
				e.preventDefault();

				_that.$element.find('.post-sites-table').slideUp(function() {
					_that.$element.find('.post-sites-table .post-sites-item').remove();
				});
			});


		},
		isExist: function(siteId) {
			var _that = this;
			if(_that.$element.find('.post-sites-items .post-sites-table .post-sites-item[data-id="'+siteId+'"]').length > 0) {
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