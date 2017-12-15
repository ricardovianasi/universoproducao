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
					reportFilter = '';

				if(_that.$element.data('form')) {
					var $form = $(_that.$element.data('form'));
					reportFilter = $form.serialize();
				}

				App.blockUI({
					cenrerY: true,
					animate: true
				});

				console.log(urlReport);

				$.ajax({
					type: 'GET',
					url: urlReport,
					data: reportFilter,
					success: function(data) {
						if(data.error) {
							_that.erroMessage(data.error);
						} else if(data.report) {
							_that.successMessage(data.report);
							App.unblockUI();
						} else {
							_that.erroMessage('Erro ao gerar relatório. Por favor, tente novamente.');	
						}
					},
					error: function() {
						_that.erroMessage('Erro ao gerar relatório. Por favor, tente novamente.');
					}
				});
			});
		},
		erroMessage: function(msg) {
			bootbox.dialog({
	            message: msg,
	            title: "Atenção",
	            buttons: {
	            	success: {
	            		label: "OK",
	                	className: "blue",
	                	callback: function() {
                    		App.unblockUI();
                    	}
	              	}
	            }
	        });
		},
		successMessage: function(urlReport) {
			var $reportResult = $("#report_result");
			$('.report-result-action.download', $reportResult).attr('href', urlReport);
			$reportResult.modal();
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