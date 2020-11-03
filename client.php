<?php $BASE = true; require_once('include.php');

if(!isset($_SESSION['client']['tag'])) exit(header('location: ./'));
if(!isset($_SESSION['actionPasTag'])) {
	if($db->playerExists($_SESSION['client']['tag']))
		$_SESSION['error']['home']['reconnect'] = $_SESSION['client']['tag'];
	exit(header('location: ./'));
}
unset($_SESSION['actionPasTag']);

if(isset($_SESSION['player'])) unset($_SESSION['player']);

$playerTag = $_SESSION['client']['tag'];

$type = 'client'; require_once 'head.php';

?>

<body>

<div id="add-bar-container">
	<div id="add-bar-wrap">
		<input id="add-bar" placeholder="dQw4w9WgXcQ" />
		<div id="add-button" class="noselect">add<span class="hover-underline"></span></div>
	</div>
	<div id="add-err" class="noselect">coś poszło nie tak</div>
</div>

<div id="tag">
	
	<h3>player tag: <?=$playerTag?></h3>
	
</div>

<div id="plConn-prompt" style="display: none;">
	<h3 class="err"></h3>
	<input type="text" id="plConn-tag" />
	<div id="plConn-conf">połącz</div>
</div>

<div id="queue-container">
	<div id="vid-list"></div>
</div>

<script> var playerTag = <?='"'.$playerTag.'"'?>; </script>

</body>

</html>