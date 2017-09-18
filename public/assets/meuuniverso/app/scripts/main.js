$(document).ready(function () {
    var parsleyOptions = {
        // successClass: 'has-success',
        errorClass: 'has-error',
        classHandler : function( _el ){
            return _el.$element.closest('.form-group');
        },
        errorsWrapper: '<span class="help-block"></span>',
        errorTemplate: '<span></span>'
    };

    $('form[data-js-validate]').parsley( parsleyOptions );

    if (jQuery().datepicker) {
        $('.date-picker').datepicker({
            todayBtn: true,
            autoclose: true,
            language: 'pt-BR',
            todayHighlight: true
        });
    }

    //Inicia o plugin que controla a busca de cep
    $(".cep").cep();

    //Inicia o plugin que controla a busca de cidades
    $(".state-cities").cities();

    $('.admin-phone').adminPhone();

    $('#user-dependents').adminDependents();
});

