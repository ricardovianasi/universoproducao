$( document ).ready(function() {

    $('#acesso-menu-principal').on('click', function(e) {
        e.preventDefault();
        var _that = $(this);
        if(!_that.hasClass('active')) {
            _that.addClass('active');
            $('#mainmenu').removeClass('none');
        } else {
            _that.removeClass('active');
            $('#mainmenu').addClass('none');
        }
    });

    $(".mainmenu-list .dropdown span").on('click', function(e) {
        e.preventDefault();
        var target = $(this).closest('.mainmenu-list .dropdown').find("ul.mainmenu-dropdown-list");
        if(target.is(":visible")) target.hide();
        else target.show();
    });

	$('#acesso-minha').on('click', function(e) {
		e.preventDefault();
		var _that = $(this);
		if(!_that.hasClass('active')) {
			_that.addClass('active');
			$('.minha-bar').css('display', 'block');
		} else {
			_that.removeClass('active');
			$('.minha-bar').css('display', 'none');
		}
	});//adsf
});