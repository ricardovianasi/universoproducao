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

				//Verifica se existe items selecionados
				var $checkItens = $('input[name="id[]"]');
				var totalChecked = $checkItens.filter(':checked').length;
				if(!totalChecked) {
					bootbox.dialog({
		                message: "Nenum item foi selecionado. É necessário efetuar a seleção de pelo menos um item da lista",
		                title: "Atenção",
		                buttons: {
		                	success: {
		                		label: "OK",
		                    	className: "red",
		                    	callback: function() {
		                      		return;
		                    	}
		                  	}
		                }
	            	});
	            	return;
				}

				//Verifica se alguma ação está selecionada
				var $selectedOption = $actionOptions.find('option:selected');
				if($selectedOption.val() == "") {
					bootbox.dialog({
		                message: "É necessário selecionar uma ação",
		                title: "Atenção",
		                buttons: {
		                	success: {
		                		label: "OK",
		                    	className: "red",
		                    	callback: function() {
		                      		return;
		                    	}
		                  	}
		                }
	            	});
	            	return;
				}

				//Monta a seleção dos ids ou toda a lista
				var $selected = $('input[name="selected"]');
				if($selected.val() != 'all') {
					var seletedItens = [];
					$('input[name="id[]"]:checked').each(function() {
						seletedItens.push($(this).val());
					});
					$selected.val(seletedItens.toString());
				}

				var filterForm = $form.serialize();

				//Verifica qual ação foi selecionada
				var $action = $('option:selected', $actionOptions);
				if($action.attr('data-modal')) {
					var $modal = $($action.attr('data-modal'));
					if($modal) {
						$modal.find('input[name="filter"]').val(filterForm);
						$modal.modal();
					}
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
				$checkItens.iCheck('check');
				_that.processChecked(_that.config.total);
			});
		},
		processChecked: function(totalChecked) {
			var _that = this;
			var $infoContainer = $('.list-actions-selected');
			var $form = $(_that.config.form);
			var $checkItens = $('input[name="id[]"]');

			if(totalChecked > 0) {
				var txt = totalChecked+' itens selecionados nesta página.';
				if(totalChecked < _that.config.total) {
					txt+= '<a href="#" class="select-all-list">Selecionar todos os '+_that.config.total+' registros disponíveis.</a>';
					$('input[name="selected"]').val('');

				} else if(totalChecked == _that.config.total) {
					txt = 'Todos os '+_that.config.total+' registros disponíveis estão selecionados.';
					$('input[name="selected"]').val('all');
				}
				$infoContainer.html(txt);
			} else {
				$infoContainer.text('');
				$('input[name="selected"]').val('');
			}
		},
		getCheckedValues: function() {
			var $checkItens = $('input[name="id[]"]');

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