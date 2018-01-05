//Cep
(function(window, $) {
	var MovieSession = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	MovieSession.prototype = {
		defaults: {
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;

			//add event
			$('.movie-session-add', _that.$element).on('click', function(e) {
				e.preventDefault();
				
				var movieSelected = $('select[name="movie"] option:selected', _that.$element);
				if(movieSelected.val() == '') {
	                return;
				}

				_that.addNode(movieSelected.val(), movieSelected.text());			
			});

			//remove node
			$(document).on('click', '.movie-session-remove', function(e) {
				e.preventDefault();
				
				var target = $(this).closest('.dd-item');
				target.children('.dd-handle').first().css({'border-color': 'red', 'background': '#FFB5B5'});

				target.slideUp(function() {
					target.remove();
				});
			});

			//Save
			$('.action-save').on('click', function(e) {
				e.preventDefault();

				App.blockUI({
					cenrerY: true,
					animate: true
				});

				var session = $('.dd').nestable('serialize');
				var form = $('.movie-programing-form');
				
				form.find('input[name="sessions"]').val(JSON.stringify(session))
			});
		},
		addNode: function(id, text) {
			var _that = this;

			var el = $('<li class="dd-item" data-id="'+id+'">')
				.append('<div class="item-controls"><a class="movie-session-remove" role="button">excluir</a></div>')
				.append('<div class="dd-handle"><span class="item-title">'+text+'</span></div>');

			$('.dd-list', _that.$element).append(el);
		}
	}

	MovieSession.defaults = MovieSession.prototype.defaults;

	$.fn.movieSession = function(options) {
		return this.each(function () {
			new MovieSession(this, options).init();
		});
	};

	window.MovieSession = Plugin;

})(window, jQuery);