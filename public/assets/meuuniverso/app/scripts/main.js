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

    $("*[data-inputmask]").inputmask();

    //Inicia o plugin que controla a busca de cep
    $(".cep").cep();

    //Inicia o plugin que controla a busca de cidades
    $(".state-cities").cities();

    $('.admin-phone').adminPhone();

    $('#user-dependents').adminDependents();

    if (!jQuery().bootstrapWizard) {
        return;
    }

    $('.scroller').each(function() {
      if ($(this).attr("data-initialized")) {
          return; // exit
      }

      var height;

      if ($(this).attr("data-height")) {
          height = $(this).attr("data-height");
      } else {
          height = $(this).css('height');
      }

      $(this).slimScroll({
          allowPageScroll: true, // allow page scroll when the element scroll is ended
          size: '7px',
          color: ($(this).attr("data-handle-color") ? $(this).attr("data-handle-color") : '#bbb'),
          wrapperClass: ($(this).attr("data-wrapper-class") ? $(this).attr("data-wrapper-class") : 'slimScrollDiv'),
          railColor: ($(this).attr("data-rail-color") ? $(this).attr("data-rail-color") : '#eaeaea'),
          position: 'right',
          height: height,
          alwaysVisible: ($(this).attr("data-always-visible") == "1" ? true : false),
          railVisible: ($(this).attr("data-rail-visible") == "1" ? true : false),
          disableFadeOut: true
      });

      $(this).attr("data-initialized", "1");
  });

    $(".project-category-reload #project_category").on("change", function () {
      var form = $(".project-category-reload"),
          validate = form.parsley('destroy');

      App.blockUI({
        cenrerY: true,
        animate: true
      });

      validate.destroy();

      form.append($('<input type="hidden" name="no-validate" value="no-validate">'));
      form.submit();

    });
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

