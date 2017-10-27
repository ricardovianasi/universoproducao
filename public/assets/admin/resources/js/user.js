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
				var src = urlUserModal;
				if(_that.config.userId) {
					src+='?id='+_that.config.userId;
				}

				App.blockUI({
					cenrerY: true,
					animate: true
				});
				setTimeout(function() {
					_that.$modal.load(src, '', function() {
						_that.$modal.modal();
						_that.registerEvents();
						App.unblockUI();
					});
				}, 1000);
			});
		},
		registerEvents: function() {
			var _that = this;

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
					_that.registerEvent();
					App.unblockUI();
				});
			});

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