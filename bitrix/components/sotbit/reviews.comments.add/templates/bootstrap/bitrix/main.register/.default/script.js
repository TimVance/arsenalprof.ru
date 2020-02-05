$(document).on('click', '#registration_comment .checkbox label', function() {
	$(this).addClass('checked');
	$(this).closest('.checkbox').find('input[type="checkbox"]').attr('checked', '');
	//return false;
});
$(document).on('click', '#registration_comment .checkbox label.checked', function() {
	$(this).removeClass('checked');
	$(this).closest('.checkbox').find('input[type="checkbox"]').removeAttr('checked', '');
	//return false;
});