(function(window, $) {
	var Menu = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	Menu.prototype = {
		defaults: {
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;

			//add external url to menu
			var formExternalUrl = _that.$element.find('.admin-menu-add .admin-menu-add-external-url form');
			_that.$element.find('.admin-menu-add .admin-menu-add-external-url .action-add').on('click', function(e) {
				e.preventDefault();
				if(_that.validateForm(formExternalUrl)) {
					_that.createNode(formExternalUrl.serialize());
				}
			});


			// Add pages to menu
			var formPages = _that.$element.find('.admin-menu-add .admin-menu-add-page form');
			_that.$element.find('.admin-menu-add .admin-menu-add-page form a[data-toggle="tab"]')
				.on('shown.bs.tab', function (e) {
				formPages.find('input.icheck').iCheck('uncheck');
			})

			_that.$element.find('.admin-menu-add .admin-menu-add-page .action-add').on('click', function(e) {
				e.preventDefault();
				if(_that.validateForm(formPages)) {

					if(formPages.find('input[type="checkbox"]:checked').length > 0) {
						_that.createNode(formPages.serialize());
					}
				}
			});

			// remove element to menu
			$(document).on('click', '.admin-menu-items .menu-items .form-item-edit .actions .remove', function(e) {
				e.preventDefault();
				
				var target = $(this).closest('.dd-item');
				target.children('.dd-handle').first().css({'border-color': 'red', 'background': '#FFB5B5'});

				target.slideUp(function() {
					target.remove();
				});
			});

			//change title item
			$(document).on('keyup', '.admin-menu-items .menu-items .form-item-edit input[name="label"]', function(e) {
				//console.log('keyPress');
				$(this)
					.closest('.dd-item').data('label', $(this).val())
					.children('.dd-handle')
					.children()
					.text($(this).val());
			});

			//change url item
			$(document).on('keyup', '.admin-menu-items .menu-items .form-item-edit input[name="url"]', function(e) {
				$(this).closest('.dd-item').data('external-url', $(this).val());
			});

			//search pages
			_that.$element.find('.admin-menu-add .search-page-action').on('click', function(e) {
				e.preventDefault();

				if(!_that.$element.find('#tab_search_pages .form-search input[name="search"]').val()) {
					return;
				}

				var form = _that.$element.find('#tab_search_pages .form-search :input').serialize();

				App.blockUI({
					cenrerY: true,
					animate: true
				});

				$.ajax({
					type: 'GET',
					url: _that.config.urlPage,
					data: form,
					success: function(data) {
						if(data.error) {
							alert(data.error);
						} else {
							_that.$element.find('.admin-menu-add #tab_search_pages .page-list').replaceWith(data.pages);
							$('.icheck').iCheck({
								 checkboxClass: 'icheckbox_minimal-grey',
							});
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

			//Save Menu
			_that.$element.find('.admin-menu-items .form-actions .action-save').on('click', function(e) {
				e.preventDefault();

				var menuSerialize = _that.$element.find('.admin-menu-items .menu-items .dd').nestable('serialize');

				App.blockUI({
					cenrerY: true,
					animate: true
				});

				var form = $('<form method="POST">')
					//.attr('action', _that.config.url)
					.append($('<input name="menu">').val(JSON.stringify(menuSerialize)));

				$(document.body).append(form);
				form.submit();
				
			});

		},

		createNode: function(data) {
			var _that = this;

			App.blockUI({
				cenrerY: true,
				animate: true
			});

			$.ajax({
				type: 'POST',
				url: _that.config.urlItem,
				data: data,
				success: function(data) {
					if(data.error) {
						alert(data.error);
					} else {
						_that.addNode(data.node);
						_that.resetForm();
					}
				},
				error: function() {
					alert('Erro desconhecido. Tente novamente');
				},
				complete: function() {
					App.unblockUI();
				}
			});
		
		},

		addNode: function(node) {
			this.$element.find(".admin-menu-items .menu-items .dd-list").first().append(node);
		},

		resetForm: function() {
			this.$element.find(".admin-menu-add form").trigger("reset");
			this.$element.find(".icheck").iCheck("uncheck");

			$(document).find('.icheck').iCheck("uncheck");
		},

		validateForm: function(form) {
			form.validate({
				highlight: function (element) { // hightlight error inputs
		            $(element)
		                .closest('.form-group').addClass('has-error'); // set error class to the control group
		        },
		         errorPlacement: function (error, element) {
		         	return ''
		         },
		         unhighlight: function (element) {
		            $(element)
		                .closest('.form-group').removeClass('has-error'); // set error class to the control group
		        }
			});

			return form.valid();
		}
	}

	Menu.defaults = Menu.prototype.defaults;

	$.fn.menu = function(options) {
		return this.each(function () {
			new Menu(this, options).init();
		});
	};

	window.Menu = Plugin;

})(window, jQuery);