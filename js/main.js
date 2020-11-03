/*
- check if still connected (check)
- check now_playing (check)
- check queue (check)
- get info about now playing

- insert into / delete from queue (check) / (madamada)
*/

function comms(type = '', options = {}, sc = null, err = null) {
	
	if(type == '') return false;
	
	var d = {'a': type};
	if(options.data != undefined)
		var d = Object.assign(options.data, {'a': type});
	
	$.ajax(Object.assign(options, {
		url: 'action.php', method: 'post', data: d,
		success: (typeof sc === "function") ? sc : null,
		error: (typeof error === "function") ? err : null
	}));
	
}

$(document).ready(function() {
	
	player.tag = playerTag;
	
	$("#add-button").click(addToQueue);
	$("#add-bar").keypress(function (e) {
		if (e.which == 13) {
			addToQueue();
			return;
		}
	});
	
	if (window.document.documentMode) {
		$("body").addClass("ie");
	}
	
	checkConnectInt = setInterval(updateConnection, 1000);
	
	
	init();
	
});

var player = {
	tag: "",
	now_playing: { queue_pos: null, yt_id: '' },
	queue: [ /*{ yt_id: '', queue_pos: null, div_id: '' }*/ ],
};

var checkConnectInt;

/* CONNECTION */
function updateConnection() {
	comms('getPlayerStatus', { timeout: 500 }, function(data) {
		var plInfo = JSON.parse(data);
		if(plInfo.status == 1) update(plInfo);
		// go back to index with error 'connection lost'
		else {
			connectLost();
			update({'now_playing': null, 'items': []});
		}
	});
}

var start = true;

/* REFRESH DATA */
function update(data) {
	
	var change = false;
	var queueChange = false;
	var nowPlayingChange = false;
	
	if(player.now_playing != null) start = false;
	
	var same = true;
	if(player.queue.length != data.items.length) same = false;
	if(same) {
		for(var i = 0; i < data.items.length; i++) {
			var found = false;
			for(var j = 0; j < player.queue.length; j++) {
				if(data.items[i].yt_id == player.queue[j].yt_id && player.queue[j].queue_pos == i) {
					found = true;
					break;
				}
			}
			if(!found) { same = false; break; }
		}
	}
	
	if(!same) {
		
		player.queue.forEach(val => val.queue_pos = -1);
		
		for(var i = 0; i < data.items.length; i++) {
			
			var found = false;
			for(var j = 0; j < player.queue.length; j++) {
				if(data.items[i].yt_id == player.queue[j].yt_id && player.queue[j].queue_pos == -1) {
					player.queue[j].queue_pos = i;
					found = true;
					break;
				}
			}
			
			if(!found) {
				player.queue[player.queue.length] = { yt_id: data.items[i].yt_id, queue_pos: i, div_id: '' };
			}
			
		}
		for(var k = 0; k < player.queue.length; k++) {
			
			var found = false;
			for(var l = 0; l < data.items.length; l++) {
				if(player.queue[k].yt_id == data.items[l].yt_id && player.queue[k].queue_pos == l) {
					found = true;
					break;
				}
			}
			if(!found) {
				delete player.queue[k];
			}
			
		}
		var tmpArr = [];
		player.queue.forEach(val => tmpArr[tmpArr.length] = val);
		player.queue = tmpArr;
		
		change = true;
		queueChange = true;
	}
	
	if((data.now_playing >= 0 && data.now_playing < player.queue.length) && player.now_playing.queue_pos != data.now_playing) {
		for(var i = 0; i < player.queue.length; i++) {
			if(	player.queue[i].queue_pos == data.now_playing ) {
				player.now_playing = { queue_pos: data.now_playing, yt_id: player.queue[i].yt_id };
				change = true;
				nowPlayingChange = true;
				break;
			}
		}
	}
	
	if(change) {
		console.log('%c -- %cPLAYER CHANGE%c -- ', 'color: rgb(140, 140, 140)', 'color: rgb(180, 220, 220)', 'color: rgb(140, 140, 140)');
		console.log(player);
		console.log('%c ------------------- ', 'color: rgb(140, 140, 140)');
		if(queueChange)
			updateQueue();
		if(nowPlayingChange)
			updateNowPLaying();
	}
	
}


/* VIDEO */
function addToQueue() {
	var bar = $("#add-bar");
	var link = bar.val();
	var regEx = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=|\?v=)([^#&?]*).*/;
	var regEx_idOnly = /(?!(\/|v=))([a-z0-9_-]{11})/i;

	if ((link != undefined && link != "") && (link.match(regEx) || link.match(regEx_idOnly))) {
		var vid_id = link.match(regEx_idOnly)[0];
		
		comms('addToQueue', {data: {'id': vid_id}}, function(res) {
			console.log("%cadd to queue response (%c" + res + "%c)", 'color: rgb(140, 140, 140)', 'color: rgb(' + (res == 1 ? '20, 220' : '220, 20') + ', 60)', 'color: rgb(140, 140, 140)');
			$("#add-bar").val("");
			updateConnection();
		});

	}
	else { console.log('regex doesnt match'); }
	
}

function deleteFromQueue(vid) {
	var que_pos = $("#container-" + vid).find('.que-pos').html();
	if (!isNaN(que_pos) && player.queue.length > 0 && (que_pos >= 0 && que_pos < player.queue.length)) {
		$("#container-" + vid).remove();
		comms('deleteFromQueue', { data: { queue_pos: que_pos } }, function(response) {
			if(response == 1) {
				console.log('%cdeleted', 'color: rgb(140, 140, 140)');
				updateConnection();
			}
		});
	}
}

function rearrangeQueue(id = -1) {
	
}

function changeVid(vid) {
	if (!isNaN(vid) && player.queue.length > 0 && (vid >= 0 && vid < player.queue.length)) {
		comms('changeVid', {data: { queue_pos: vid }}, function(response) {
			if(response == 1) {
				console.log(`change vid ${vid}`);
				updateConnection();
			}
		});
	}
}


/* REFRESH PAGE */
function updateQueue() {
	
	console.log('Queue update');
	
	if(player.queue.length == 0) {
		$("#vid-list").html('');
		return;
	}
	
	var vid_list = [];
	for(var i = 0; i < player.queue.length; i++) {
		if(player.queue[i].div_id == '')
			vid_list[player.queue[i].queue_pos] = 0;
		else
			vid_list[player.queue[i].queue_pos] = "#container-" + player.queue[i].div_id;
		
	}
	
	for(var i = 0; i < vid_list.length; i++) {
		var lastElement;
		if(vid_list[i] != 0 && $(vid_list[i]).length == 1) {
			if($("#vid-list").get(i) != $(vid_list[i])) {
				if(i == 0)
					$("#vid-list").append($(vid_list[i]));
				else {
					$(vid_list[i]).insertAfter(lastElement);
				}
			}
			lastElement = $(vid_list[i]);
			lastElement.find(".que-pos").html(i);
		}
		else {
			var vid_id = player.queue[i].yt_id;
			var div_id = vid_id + String(Math.floor(Math.random() * 100000) + 100);
			var queue_pos = player.queue[i].queue_pos;
			player.queue[i].div_id = div_id;
			
			vid_list[i] = "#container-" + div_id;
			$("#vid-list").append('<div class="que-vid-container" id="container-' + div_id + '"></div>');
			
			comms('getVidInfo', {data: {yt_id: vid_id}, add: { div_id: div_id, queue_pos: queue_pos } }, function(response) {
				response = JSON.parse(response);
				
				console.log('%cYT API REQUEST%c (' + response.vid.items[0].id + ')', 'color: rgb(220, 220, 60); margin: 8px 0', 'color: rgb(140, 140, 140)');
				
				if(response.success) {
					vid_info = response.vid;
					vid_info.items[0].add = this.add;
					addListItem(vid_info.items[0]);
				}
				else if($("#container-" + this.id).length)
					$("#container-" + this.id).remove();
			});
		}
	}
	
	$("#vid-list .que-vid-container").each(function() {
		var found = false;
		for(var i = 0; i < vid_list.length; i++) {
			if("#" + $(this).attr('id') == vid_list[i]) {
				found = true;
				break;
			}
		}
		if(!found) {
			$(this).remove();
		}
	});
	
	console.log('---');
	
}

function updateNowPLaying() {
	
	changeNowPlaying();
}

function addListItem(info) {
	
	var duration = parseDuration('a');
	
var item = 
`
<div class="que-vid">
	<div class="que-pos">${info.add.queue_pos}</div>
	<div class="que-main-container">
		<div class="que-img">
			<img src="https://img.youtube.com/vi/${info.id}/0.jpg" />
		</div>
		<div class="front">
			<div class="que-title">${info.snippet.title}</div>
			<div class="que-channel">${info.snippet.channelTitle}</div>
		</div>
	</div>
	<div class="que-delete">
		<span>X</span>
	</div>
</div>
`;
	
	$("#vid-list #container-" + info.add.div_id).append(item);
	var curr_container = "#vid-list #container-" + info.add.div_id + " .que-vid ";

	$(curr_container + ".que-main-container").click(function () {
		changeVid(info.add.queue_pos);
	});
	
	$(curr_container + ".que-delete").click(function () {
		deleteFromQueue(info.add.div_id);
	});
	
	
}

function parseDuration(d) {
	var regex = /[DHMS]/;
	var dur = { 'D': '', 'M': '', 'S': '' };
	var curr = 'Q';
	var last = '';
	for(var i = d.length - 1; i >= 0; i--) {
		var a = d[i];
		if(isNaN(a) && a.match(/[DHMS]/)) {
			curr = a;
			last = '';
		}
		else if(!isNaN(a)) {
			dur[curr] = a + last;
			last = a + last;
		}
	}
	var ret = '';
	
	return "--/--";
}
