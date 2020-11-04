"use strict"

function init() {
	
	player.tag = playerTag;
	
	canUpdateQueue = true;
	console.log('client init');
	//$("#plConn-conf").click(chPlayer);
	
}

var prompt_show = false;
function chPlayerPrompt(a = 'show', r = null) {
	if(a == 'show' && !prompt_show) {
		$("#plConn-prompt").stop().fadeIn({duration: 200, start: function() {
			if(r == 'connectLost') $('#plConn-prompt .err').html('utracono połączenie z playerem o tym tagu');
			prompt_show = true; $("#plConn-prompt").addClass('active');
		}});
	}
	else if(a == 'hide' && prompt_show) {
		$("#plConn-prompt").stop().fadeOut({duration: 100, start: function() {
			prompt_show = false; $("#plConn-prompt").removeClass('active');
		}});
	}
}

function chPlayer() {
	var tag = $("#plConn-tag").val();
	//clearInterval(checkConnectInt);
	// change player
	//checkConnectInt = setInterval(updateConnection, 100);
}

function connectLost() {
	
	console.log('UTRACONO POŁĄCZENIE');
	$('#tag').html('<h1>utracono połączenie</h1>');
	
}

function changeNowPlaying() {
	
}
