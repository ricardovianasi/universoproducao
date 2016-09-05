(function(window, $) {
	var ICheckControl = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	ICheckControl.prototype = {
		defaults: {
			'disableAllBtn': '.icheckcontrol-disable-all',
			'enableAllBtn': '.icheckcontrol-enable-all',
			'toggleAllBtn': '.icheckcontrol-toggle-all',
			'target': 'input.icheck'
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options);
			var _that = this;

			_that.$element.on('click', function(e) {
				e.preventDefault;
				var target = $(_that.config.target);				

				if($(this).hasClass(_that.config.disableAllBtn)) {
					target.iCheck('uncheck');
				} else if($(this).hasClass(_that.config.enableAllBtn)) {
					target.iCheck('check');
				} else {
					if(target.not(":checked").length > 0) {
						target.iCheck('check');
					} else {
						target.iCheck('uncheck');
					}
				}
			});
		},
	}

	ICheckControl.defaults = ICheckControl.prototype.defaults;

	$.fn.iCheckControl = function(options) {
		new ICheckControl(this, options).init();
	};

	window.ICheckControl = Plugin;

})(window, jQuery);

jQuery(document).ready(function() {
	$('.icheckcontrol').iCheckControl();
});