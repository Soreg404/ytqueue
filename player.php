<?php $BASE = true; require_once('include.php');

if(!isset($_SESSION['player']['tag']) || !$db->playerExists($_SESSION['player']['tag']))
	exit(header('location: ./'));

if(isset($_SESSION['client'])) unset($_SESSION['client']);

$playerTag = $_SESSION['player']['tag'];

$type = 'player'; require_once 'head.php'; ?>

<body>

<div id="content" class="player">
	
	<div id="tag">
		
		<h3>player tag: <?=$playerTag?></h3>
		
	</div>
	
	<main>
		<div id="video-container">
			<div id="player">
				<div id="video-frame">
				</div>
			</div>
		</div>
		
		<div id="add-bar-container">
			<div id="add-bar-wrap">
				<input id="add-bar" placeholder="dQw4w9WgXcQ" />
				<div id="add-button" class="noselect">add<span class="hover-underline"></span></div>
			</div>
			<div id="add-err" class="noselect">coś poszło nie tak</div>
		</div>
		
		<div id="queueRefresh" class="action-button no-select">Odśwież kolejkę</div>
		<div id="queueHide" class="action-button no-select">ukryj kolejkę</div>
		<div id="queueShow" class="action-button no-select">pokaż kolejkę</div>
		
		<div id="queue-container">
			<div id="vid-list"></div>
		</div>
	</main>
	
</div>

</body>

</html>