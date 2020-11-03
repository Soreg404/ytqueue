"use strict"

refresh();

function init() {
	
	console.log('player init');
	createPlayer();
	
}

var mainPlayer;
function onYouTubeIframeAPIReady() {
	mainPlayer = new YT.Player('video-frame', {
		height: '100%',
		width: '100%',
		playerVars: {
			enablejsapi: 1,
			rel: 0,
			showinfo: 0,
			modestbranding: 1,
		},
		events: {
			'onReady': onPlayerReady,
			'onStateChange': stateChange
		}
	});
	
	console.log(mainPlayer);
	
}

function createPlayer() {
	setInterval(refresh, 2000);
}

function refresh() {
	comms('refreshPlayer');
}

function connectLost() {
	
	console.log('UTRACONO POŁĄCZENIE');
	$('#tag').html('<h1>utracono połączenie</h1>');
	
}

var playerReady = false;
function onPlayerReady() {
	playerReady = true;
}

function stateChange() {
	
}

function changeNowPlaying() {
	if(!mainPlayer) return;
	
	if(playerReady) {
		console.log('now playing: ' + player.now_playing.yt_id);
		mainPlayer.loadVideoById(player.now_playing.yt_id);
	}
	
}