$(document).ready(function () {
	$('.movie-form select[name="has_cpb"]').on('change', function(e) {
		var selected = $(this).children('option:selected').val();
		var target = $('#has_cpb')
		var cpbField = $('.movie-form input[name="cpb"]');
		if(selected == 1) {
			target.show();
			cpbField.attr('required', 'required');
		} else {
			target.hide();
			cpbField.removeAttr('required');
			cpbField.val('');
		}
	});

	$('.movie-form select[name="options[classification]"]').on('change', function(e) {
		var selected = $(this).children('option:selected').text();
		var target = $('#content_scenes')
		var field = $('.movie-form textarea[name="content_scenes"]');
		if(selected.toLowerCase() != 'livre' && selected.toLowerCase() != 'selecione') {
			target.show();
			field.attr('required', 'required');
		} else {
			target.hide();
			field.removeAttr('required');
			field.val('');
		}
	});

	$('.movie-form select[name="has_participated_other_festivals"]').on('change', function(e) {
		var selected = $(this).children('option:selected').val();
		var target = $('#other_festivals');
		var field = $('.movie-form textarea[name="other_festivals"]');
		if(selected == 1) {
			target.show();
		} else {
			target.hide();
			field.val('');
		}
	});

	$('.movie-form select[name="has_conversations_languages"]').on('change', function(e) {
		var selected = $(this).children('option:selected').val();
		var target = $('#conversations_languages');
		var field = $('.movie-form textarea[name="conversations_languages"]');
		if(selected == 1) {
			target.show();
		} else {
			target.hide();
			field.val('');
		}
	});

	$('.movie-form select[name="has_subtitles_languages"]').on('change', function(e) {
		var selected = $(this).children('option:selected').val();
		var target = $('#subtitles_languages');
		var field = $('.movie-form textarea[name="subtitles_languages"]');
		if(selected == 1) {
			target.show();
		} else {
			target.hide();
			field.val('');
		}
	});

	$('.movie-form select[name="has_conversations_list_languages"]').on('change', function(e) {
		var selected = $(this).children('option:selected').val();
		var target = $('#conversations_list_languages');
		var field = $('.movie-form textarea[name="conversations_list_languages"]');
		if(selected == 1) {
			target.show();
		} else {
			target.hide();
			field.val('');
		}
	});
});