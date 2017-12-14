//Cep
(function(window, $) {
	var Report = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	Report.prototype = {
		defaults: {},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;

			_that.$element.on('click', function(e) {
				e.preventDefault();

				var $form=null, 
					urlReport = _that.$element.data('url'),
					token = new Date().getTime(), //use the current timestamp as the token value
					downloadTimer,
					attempts = 60;

				if(_that.$element.data('form')) {
					$form = $(_that.$element.data('form'));
				} else {
					$form = $('<form method="GET">');
					$(document.body).append($form);
				}
				$form.attr('action', urlReport);
				$form.append($('<input type="hidden", name="downloadToken">').val(token));

				App.blockUI({
					cenrerY: true,
					animate: true
				});

				$form.submit();

				downloadTimer = window.setInterval(function () {
					var cookieToken = _that.getCookie('downloadToken');
					if( (cookieToken == token) || (attempts == 0) ) {
						App.unblockUI();
            			window.clearInterval( downloadTimer );
  						_that.expireCookie( "downloadToken" );
  						attempts = 60;
        			}
					attempts--;
				}, 1000);
			});
		},
		getCookie: function(name) {
			var parts = document.cookie.split(name + "=");
			if (parts.length == 2) return parts.pop().split(";").shift();
		},
		expireCookie: function(cName) {
			document.cookie = encodeURIComponent(cName) + "=deleted; expires=" + new Date( 0 ).toUTCString();
		}
	}

	Report.defaults = Report.prototype.defaults;

	$.fn.report = function(options) {
		return this.each(function () {
			new Report(this, options).init();
		});
	};

	window.Report = Plugin;

})(window, jQuery);