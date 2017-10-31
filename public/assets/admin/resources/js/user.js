//Cep
(function(window, $) {
	var User = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
		this.$modal = $('#user-modal');
	};

	User.prototype = {
		defaults: {},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;
			_that.$element.on('click', function(e) {
				e.preventDefault();
				
				_that.$modal.empty();

				App.blockUI({
					cenrerY: true,
					animate: true
				});

				var data = {};
				if(_that.config.userId) {
					data.id = _that.config.userId;
				}					

				if (_that.config.hasOwnProperty('viewOnly')) {
					data.viewOnly = 1;
				}

				var request = $.ajax({
					type: 'GET',
					url: urlUserModal,
					data: jQuery.param(data),
					dataType: 'html'
				});
				request.done(function(content) {
					_that.$modal.html(content);
					_that.$modal.modal();
					_that.registerEvents();
					App.unblockUI();
				});
			});
		},
		registerEvents: function() {
			var _that = this;

			//paginação
			_that.$modal.find('#user-modal-paginator a').on('click', function(e) {
				e.preventDefault();
			
				var src = $(this).attr('href');
				if(src == "#") {
					return;
				}

				App.blockUI({
					cenrerY: true,
					animate: true,
					target: _that.$modal
				});

				var request = $.ajax({
					type: 'GET',
					url: src,
					dataType: 'html'
				});

				request.done(function(content) {
					_that.$modal.html(content);
					_that.registerEvents();
					App.unblockUI();
				});
			});

			//formulário de busca
			var formSearch = _that.$modal.find('.user-search');
			formSearch.find('button[type="submit"]').on('click', function(e) {
				e.preventDefault();
				
				App.blockUI({
					cenrerY: true,
					animate: true,
					target: _that.$modal
				});

				formSearch.append($('<input type="hidden" name="peform-filter"/>'));
				var src = urlUserModal;
				if(_that.config.userId) {
					src+='?id='+_that.config.userId;
				}

				var request = $.ajax({
					type: 'GET',
					url: src,
					data: formSearch.serialize(),
					dataType: 'html'
				});

				request.done(function(content) {
					_that.$modal.html(content);
					_that.registerEvents();
					_that.resizeModal();
					App.unblockUI();
				});
			});

			//Seleção de usuário
			_that.$modal.find('.user-modal-select').on('click', function(e) {
				e.preventDefault();

				var el = $(this),
					name = el.data('name'),
					id = el.data('id');

				if(_that.config.selectIdTo) {
					var selectIdTo = $(_that.config.selectIdTo);
					selectIdTo.val(id);
				}

				if(_that.config.selectNameTo) {
					var selectNameTo = $(_that.config.selectNameTo);
					selectNameTo.val(name);
				}
				_that.$element.data('user-id', id);
				_that.$modal.modal('hide');
				_that.$modal.on('hidden.bs.modal', function (e) {
					_that.$modal.empty();
					_that.$element.off();
					_that.$element.user();
				})
			});

		},
		resizeModal: function() {
			var _that = this,
				newMargin = _that.$modal.height()/2;
			_that.$modal.css('margin-top', '-'+newMargin+'px');
		}
	}

	User.defaults = User.prototype.defaults;

	$.fn.user = function(options) {
		return this.each(function () {
			new User(this, options).init();
		});
	};

	window.User = Plugin;

})(window, jQuery);