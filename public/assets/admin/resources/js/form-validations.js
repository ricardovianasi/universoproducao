//Cep
(function(window, $) {
	var FormValidation = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	FormValidation.prototype = {
		defaults: {
			'alertMensage': '.alert-form-errors',
			'errorElement': 'span',
			'errorClass': 'help-block help-block-error'
		},

		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;
			console.log('hahahahaha');
			_that.$element.validate({
		        errorElement: _that.config.errorElement, //default input error message container
		        errorClass: _that.config.errorClass, // default input error message class
		        focusInvalid: false, // do not focus the last invalid input
		        ignore: "",  // validate all fields including form hidden input
		        messages: {
		        	
		        },
		        errorPlacement: function (error, element) { // render error placement for each input type

                    if (element.parent(".input-group").size() > 0) {
                        error.insertAfter(element.parent(".input-group"));
                    } else if (element.attr("data-error-container")) { 
                        error.appendTo(element.attr("data-error-container"));
                    } else if (element.parents('.radio-list').size() > 0) { 
                        error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                    } else if (element.parents('.radio-inline').size() > 0) { 
                        error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                    } else if (element.parents('.checkbox-list').size() > 0) {
                        error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                    } else if (element.parents('.checkbox-inline').size() > 0) { 
                        error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                    } else if (element.parents('.checkbox').size() > 0) { 
                        error.appendTo(element.parents('.checkbox'));
                    } else {
                        error.insertAfter(element); // for other inputs, just perform default behavior
                    }
                },
		        invalidHandler: function (event, validator) { //display error alert on form submit 
		            _that.$element.find(_that.config.alertMensage).show();
		            App.scrollTo($("div.alert.display-hide"), -200);
		        },
		        highlight: function (element) { // hightlight error inputs
		            $(element)
		                .closest('.form-group').addClass('has-error'); // set error class to the control group
		        },

		        unhighlight: function (element) { // revert the change done by hightlight
		            $(element)
		                .closest('.form-group').removeClass('has-error'); // set error class to the control group
		        },

		        success: function (label) {
		            label
		                .closest('.form-group').removeClass('has-error'); // set success class to the control group
		        }
		    });

			//validate DatePicker
		    _that.$element.find('.date-picker .form-control').change(function() {
				_that.$element.validate().element($(this));
		    });
		}
	}

	FormValidation.defaults = FormValidation.prototype.defaults;

	$.fn.formValidation = function(options) {
		return this.each(function () {
			new FormValidation(this, options).init();
		});
	};

	window.FormValidation = Plugin;

})(window, jQuery);