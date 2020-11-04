"use strict"

$(document).ready(function() {
	
	$('#field-player').click(function() { showForm('player'); });
	$('#field-client').click(function() { showForm('client'); });
	$('#prompt-bg').click(showForm);
	
});

function showForm(a = '') {
	
	switch(a) {
		case 'player':
			$('#prompt-bg').stop().fadeIn(400);
			$('#create-prompt').fadeIn().addClass('active');
			$('#connect-prompt').fadeOut().removeClass('active');
		break;
		case 'client':
			$('#prompt-bg').stop().fadeIn(400);
			$('#create-prompt').fadeOut().removeClass('active');
			$('#connect-prompt').fadeIn().addClass('active');
		break;
		default:
			$('#prompt-bg').fadeOut(200);
			$('#create-prompt').fadeOut().removeClass('active');
			$('#connect-prompt').fadeOut().removeClass('active');
		break;
	}
	
}