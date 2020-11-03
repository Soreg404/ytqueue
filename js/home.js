"use strict"

function init() {
	
	$("#field-player").on("click", create_player);
	$("#field-client").on("click", client_connect);
	
}

var processing = false;

// show creation form, if valid - redirect to player
function create_player() {
	processing = true;
	console.log("create plaer");
}

// find player id and if correct - redirect to client with this id
function client_connect() {
	processing = true;
	console.log("connect to client");
}