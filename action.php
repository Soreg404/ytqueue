<?php $BASE = true; require_once('include.php');

if(!isset($_POST['a'])) die();

$tag = '';
if(isset($_SESSION['player']['tag']))
	$tag =  $_SESSION['player']['tag'];
if(isset($_SESSION['client']['tag']))
	$tag =  $_SESSION['client']['tag'];

switch($_POST['a']) {
	
	case 'createPlayer':
		
		if(isset($_SESSION['player']['tag']))
			$db->deletePlayer($_SESSION['player']['tag']);
		
		$count = 4;
		// A - 65  Z - 90   [25]		
		do {
			$newTag = '';
			for($i = 0; $i < $count; $i++) {
				$newTag .= chr(rand(65, 90));
			}
			$count++;
			if($count > 10)
				exit(header('location: ./'));
		} while (!$db->createPlayer($newTag));
		
		$_SESSION['player'] = array(
			'tag' => $newTag,
			'created' => time()
		);
		header('location: player.php');
		
	break;
	
	case 'connectToPlayer':
		$_SESSION['actionPasTag'] = false;
		if(isset($_POST['tag']))
			$p = $_POST['tag'];
		if($db->playerExists($p)) {
			$_SESSION['actionPasTag'] = true;
			$_SESSION['client']['tag'] = $p;
		}
		else if(isset($_SESSION['client'])) unset($_SESSION['client']);
		header('location: client.php');
		
	break;
	
	case 'refreshPlayer':
		if(isset($_SESSION['player']['tag'])) {
			if($db->refreshPlayer($_SESSION['player']['tag']))
				echo $_SESSION['player']['tag'];
		} else echo -1;
	break;
	
	
	
	case 'getPlayerStatus':
		
		$status = 0;
		$ret = array(
			'items' => NULL,
			'now_playing' => NULL
		);
		
		if($db->playerExists($tag)) {
			$status = 1;
			$items = mysqli_fetch_all($db->getQueue($tag), MYSQLI_ASSOC);
			$nowPlaying = $db->getNowPlaying($tag);
			$ret['items'] = $items;
			$ret['now_playing'] = $nowPlaying;
		}
		
		echo json_encode(array('status' => $status) + $ret);
		
	break;
	
	case 'getVidInfo':
		
		$response['success'] = false;
		if(!isset($_POST['yt_id'])) return json_encode($response);
		else $yt_id = $_POST['yt_id'];
		
		$api_key = 'AIzaSyCmwdlIwDwEULCCLevMYJpKPGxP4NuhZMw';
		$vid_info = @json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&id=$yt_id&key=$api_key"), true);
		//$vid_info = @json_decode(file_get_contents("http://date.jsontest.com/"), true);
		
		if($vid_info !== NULL || $vid_info['pageInfo']['totalResults'] == 1) {
			$response['vid'] = $vid_info;
			$response['success'] = true;
		}
		
		echo json_encode($response);
		
	break;
	
	
	// \/ \/ \/ \/ \/ TO TRZEBA ZMIENIĆ \/ \/ \/ \/ \/
	
	case 'addToQueue':
		$r = 1;
		if(!isset($_POST['id']))
			$r = -2;
		if($r) {
			if($db->playerExists($tag))
				$r = $db->addToQueue($tag, $_POST['id']);
			else
				$r = -1;
		}
		
		echo $r;
		
	break;
	
	case 'deleteFromQueue':
		
		$r = 1;
		if(!isset($_POST['queue_pos']))
			$r = -2;
		if($r) {
			if($tag != '')
				$r = $db->deleteFromQueue($tag, $_POST['queue_pos']);
			else
				$r = -1;
		}
		
		echo $r;
		
	break;
	
	case 'changeVid':
		
		$r = 1;
		if(!isset($_POST['queue_pos']))
			$r = -2;
		if($r) {
			if($tag != '')
				$r = $db->changeVid($tag, $_POST['queue_pos']);
			else
				$r = -1;
		}
		
		echo $r;
		
	break;
	
}