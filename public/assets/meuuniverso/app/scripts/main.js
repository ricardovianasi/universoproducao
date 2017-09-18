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

//has uppercase
window.Parsley.addValidator('uppercase', {
  requirementType: 'number',
  validateString: function(value, requirement) {
    var uppercases = value.match(/[A-Z]/g) || [];
    return uppercases.length >= requirement;
  },
  messages: {
    en: 'Your password must contain at least (%s) uppercase letter.',
    'pt-br': 'É necessário conter ao menos %s letra maiúscula'
  }
});

//has lowercase
window.Parsley.addValidator('lowercase', {
  requirementType: 'number',
  validateString: function(value, requirement) {
    var lowecases = value.match(/[a-z]/g) || [];
    return lowecases.length >= requirement;
  },
  messages: {
    en: 'Your password must contain at least (%s) lowercase letter.',
    'pt-br': 'É necessário conter ao menos %s letra minúscula.'
  }
});

//has number
window.Parsley.addValidator('number', {
  requirementType: 'number',
  validateString: function(value, requirement) {
    var numbers = value.match(/[0-9]/g) || [];
    return numbers.length >= requirement;
  },
  messages: {
    en: 'Your password must contain at least (%s) number.',
    'pt-br': 'É ncessário conter ao menos %s número.'
  }
});

//has special char
window.Parsley.addValidator('special', {
  requirementType: 'string',
  validateString: function(value, requirement) {
    var specials = value.match(/[a-zA-Z]/g) || [];
    return specials.length >= requirement;
  },
  messages: {
    en: 'Your password must contain at least %s characters.',
    'pt-br': 'É ncessário conter ao menos %s letra.'
  }
});

window.Parsley.addValidator('char', {
  requirementType: 'string',
  validateString: function(value, requirement) {
    var specials = value.match(/[^a-zA-Z0-9]/g) || [];
    return specials.length >= requirement;
  },
  messages: {
    en: 'Your password must contain at least (%s) special characters.',
    'pt-br': 'É ncessário conter ao menos %s caracter especial.'
  }
});
