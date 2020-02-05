$(document).on('click', '#registration_question .checkbox label', function() {
	$(this).addClass('checked');
	$(this).closest('.checkbox').find('input[type="checkbox"]').attr('checked', '');
	//return false;
});
$(document).on('click', '#registration_question .checkbox label.checked', function() {
	$(this).removeClass('checked');
	$(this).closest('.checkbox').find('input[type="checkbox"]').removeAttr('checked', '');
	//return false;
});