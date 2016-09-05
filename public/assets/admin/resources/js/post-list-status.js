(function(window, $) {
	var PostListStatus = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	PostListStatus.prototype = {
		defaults: {
			'removeMsg': 'Tem certeza que deseja exluir o item?'
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options);
			var _that = this;

			_that.trashAction();
		},
		trashAction: function()
		{
			var _that = this;
			console.log(_that.$element.find('.post-list-remove'));
			_that.$element.find('.post-list-remove').on('click', function(e) {
				e.preventDefault();
				_that.remove(_that.config.removeMsg, $(this).attr('href'));
			})
		},
		remove: function(msg, link) {
			bootbox.dialog({
                message: msg,
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

                      		window.location.href = link;
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
		}
	};

	PostListStatus.defaults = PostListStatus.prototype.defaults;

	$.fn.postListStatus = function(options) {
		return this.each(function() {
			new PostListStatus(this, options).init();
		});
	};

	window.PostListStatus = Plugin;

})(window, jQuery);