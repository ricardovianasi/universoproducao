(function(window, $) {
	var PostStatus = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	PostStatus.prototype = {
		defaults: {
			'form': '#post-form'
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options);
			var _that = this;

			_that.changeStatus();
			_that.changePostDate();
			_that.descartAction();
			_that.publishAction();
			_that.draftAction();
			_that.saveAction();
		},
		changeStatus: function() {
			var _that = this;

			//Edit
			_that.$element.find(".status .action.edit").on('click', function(e) {
				e.preventDefault();

				var optionsBlock = _that.$element.find(".status .options");
				
				if(optionsBlock.hasClass('show-options')) {
					optionsBlock.removeClass('show-options').hide(100);
					$(this).text('Editar');
				} else {
					optionsBlock.addClass('show-options').show(100);
					$(this).text('Cancelar');
				}
			});

			//ok
			_that.$element.find(".status .action.ok").on('click', function(e) {
				e.preventDefault();

				var status = _that.$element.find(".status .options select[name='status']").val();
				if(status == "") {
					return;
				}

				_that.$element.find(".status span > strong").text(
					_that.$element.find(".status .options select[name='status'] option:selected").text()
				);
				_that.$element.find(".status .options").removeClass('show-options').hide(100);
				_that.$element.find(".status .action.edit").text('Editar');
			});
		},
		changePostDate: function() {
			var _that = this;
			
			//Edit
			_that.$element.find(".post-date .action.edit").on('click', function(e) {
				e.preventDefault();

				var optionsBlock = _that.$element.find(".post-date .options");
				
				if(optionsBlock.hasClass('show-options')) {
					optionsBlock.removeClass('show-options').hide(100);
					$(this).text('Editar');
				} else {
					optionsBlock.addClass('show-options').show(100);
					$(this).text('Cancelar');
				}
			});

			_that.$element.find(".post-date .action.ok").on('click', function(e) {
				e.preventDefault();

				var date = _that.$element.find(".post-date input[name='date']").datepicker('getDate');
				var hour = _that.$element.find(".post-date input[name='hour']").timepicker('getTime').val();
				var postDate = new Date(date.getFullYear(), date.getMonth(), date.getDate(), hour.split(':')[0], hour.split(':')[1]);
				var momentdate = moment(postDate);

				_that.$element.find(".post-date span strong").text(momentdate.format('DD/MM/YYYY [às] HH:mm'));
				_that.$element.find(".post-date input[name='postDate']").val(momentdate.format('DD/MM/YYYY HH:mm'));

				_that.$element.find(".post-date .options").hide(100);
				_that.$element.find(".post-date .options").removeClass('show-options');
				_that.$element.find(".post-date .action.edit").text('Editar');
			});
		},
		descartAction: function() {
			this.$element.find('.form-actions .action.cancel').on('click', function(e) {
				e.preventDefault();
				var _that = $(this);
				//Alerta
				bootbox.dialog({
                    message: "Tem certeza que deseja descartar as alterações?",
                    title: "Atenção",
                    buttons: {
                    	success: {
                    		label: "OK",
                        	className: "red",
                        	callback: function() {
                        		App.blockUI({
									cenrerY: true,
									animate: true
								});

                          		window.location.href = _that.attr('href');
                          		return;
                        	}
                      	},
                      	danger: {
                        	label: "Cancelar",
                        	className: "blue",
                        	callback: function() {
                          		return;
                        	}
                      	}
                    }
                });

			});
		},
		publishAction: function()
		{
			var _that = this;
			_that.$element.find('.form-actions button[name="publish"]').on('click', function(e) {
				e.preventDefault();
				
				_that.$element.find(".status .options select[name='status']").val('published');

				_that.save();
			})
		},
		draftAction: function()
		{
			var _that = this;
			_that.$element.find('.heding-actions .action.save-draft').on('click', function(e) {
				e.preventDefault();
				
				_that.$element.find(".status .options select[name='status']").val('draft');

				_that.save();
			})
		},
		saveAction: function()
		{
			var _that = this;
			_that.$element.find('.form-actions button[name="save"]').on('click', function(e) {
				e.preventDefault();
				_that.save();
			})
		},
		save: function()
		{
			var _that = this;
			App.blockUI({
				cenrerY: true,
				animate: true
			});

			setTimeout(function() {
				$(_that.config.form).submit();
			}, 1500);

		}
	};

	PostStatus.defaults = PostStatus.prototype.defaults;

	$.fn.postStatus = function(options) {
		return this.each(function() {
			new PostStatus(this, options).init();
		});
	};

	window.PostStatus = Plugin;

})(window, jQuery);