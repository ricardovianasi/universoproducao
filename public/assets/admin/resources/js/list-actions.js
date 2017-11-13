(function(window, $) {
	var ListActions = function(element, options) {
		this.element = element;
		this.$element = $(element);
		this.options = options;
	};

	ListActions.prototype = {
		defaults: {},
		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.$element.data());
			var _that = this;

			console.log(_that.config);

			_that.initCheckIntens();
			_that.initFormActions();

		},
		initFormActions: function() {
			var _that = this;
			var $form = $(_that.config.form);
			var $actionOptions = $('.list-actions-options');
			var $actionSubmit  = $('.list-actions-submit');

			$actionSubmit.on('click', function(e) {
				e.preventDefault();

				var $selectedOption = $actionOptions.find('option:selected');
				if($selectedOption.val() == "") {
					alert('necessário selecionar uma opção');
					return;
				}
				var attr = $selectedOption.data();

				var dataForm = $form.serialize();
				console.log(dataForm);

				//var validation = [];
				if(attr.hasOwnProperty('requiredFields') && attr.requiredFields) {
					//validar os campos
					requiredFields = attr.requiredFields.split(';');
				}

			});

		},
		initCheckIntens: function() {
			var _that = this;

			var $checkAll = $('input.select-all');
			var $checkItens = $('input[name="id[]"]');
			var ignoreCheckAllEvent = false;
			var ignoreCheckItemEvent = false;

			$checkAll.on('ifChecked', function() {
				if(!ignoreCheckAllEvent) {
					$checkItens.iCheck('check');
				}
				ignoreCheckAllEvent = false;
			});

			$checkAll.on('ifUnchecked', function() {
				if(!ignoreCheckAllEvent) {
					$checkItens.iCheck('uncheck');
				}
				ignoreCheckAllEvent = false;
			});

			$checkItens.on('ifChanged', function(e) {
				if(!ignoreCheckItemEvent) {
					var totalChecked = $checkItens.filter(':checked').length;
					_that.processChecked(totalChecked);
				}
			});

			$(document).on('click', '.select-all-list', function(e) {
				e.preventDefault();
				//ignoreCheckAllEvent = true;
				$checkItens.iCheck('check');
				//$checkAll.iCheck('check');
				_that.processChecked(_that.config.total);
			});
		},
		processChecked: function(totalChecked) {
			var _that = this;
			var $infoContainer = $('.list-actions-selected');

			if(totalChecked > 0) {
				var txt = totalChecked+' itens selecionados nesta página.';
				if(totalChecked < _that.config.total) {
					txt+= '<a href="#" class="select-all-list">Selecionar todos os '+_that.config.total+' registros disponíveis.</a>';
				} else if(totalChecked == _that.config.total) {
					txt = 'Todos os '+_that.config.total+' registros disponíveis estão selecionados.'
				}
				$infoContainer.html(txt);
			} else {
				$infoContainer.text('');
			}
		}
	}

	ListActions.defaults = ListActions.prototype.defaults;

	$.fn.listActions = function(options) {
		return this.each(function () {
			new ListActions(this, options).init();
		});
	};

	window.ListActions = Plugin;

})(window, jQuery);