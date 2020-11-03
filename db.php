<?php if(!isset($BASE)) { http_response_code(403); exit('403 Forbidden'); }

class db {
	
	private $connect;
	
	function __construct() {
		$this->connect = mysqli_connect('localhost', 'root', '', 'ytqueue');
		$this->deleteUnused();
	}
	
	function deleteUnused() {
		mysqli_query($this->connect, 'DELETE FROM `players` WHERE TIMESTAMPDIFF(SQL_TSI_SECOND, lastSeen, CURRENT_TIMESTAMP)>20');
		mysqli_query($this->connect, 'DELETE FROM `queue` WHERE `playerTag` NOT IN (SELECT playerTag FROM `players`)');
	}
	
	public function createPlayer($playerTag) {
		if(!$this->playerExists($playerTag)) {
			$playerTag = mysqli_real_escape_string($this->connect, $playerTag);
			$sql = sprintf('INSERT INTO `players` (`ID`, `playerTag`, `nowPlaying`, `lastSeen`) VALUES (NULL, "%s", NULL, CURRENT_TIMESTAMP)', $playerTag);
			return mysqli_query($this->connect, $sql);
		}
		else return false;
	}
	
	public function playerExists($playerTag) {
		$playerTag = mysqli_real_escape_string($this->connect, $playerTag);
		$sql = sprintf('SELECT COUNT(ID) FROM `players` WHERE `playerTag`="%s"', $playerTag);
		return mysqli_fetch_array(mysqli_query($this->connect, $sql))[0];
	}
	
	public function getQueue($playerTag) {
		$playerTag = mysqli_real_escape_string($this->connect, $playerTag);
		$sql = sprintf('SELECT `queue`.`yt_id`, `queue`.`queue_pos` FROM `queue` WHERE `queue`.`playerTag`="%s" ORDER BY `queue`.`queue_pos`', $playerTag);
		return mysqli_query($this->connect, $sql);
	}
	
	public function getNowPlaying($playerTag) {
		$playerTag = mysqli_real_escape_string($this->connect, $playerTag);
		$sql = sprintf('SELECT `players`.`nowPlaying` FROM `players` WHERE `players`.`playerTag`="%s"', $playerTag);
		return mysqli_fetch_array(mysqli_query($this->connect, $sql))[0];
	}
	
	public function refreshPlayer($playerTag) {
		if(!$this->playerExists($playerTag)) return 0;
		$playerTag = mysqli_real_escape_string($this->connect, $playerTag);
		$sql = sprintf('UPDATE `players` SET `players`.`lastSeen`=CURRENT_TIMESTAMP WHERE `players`.`playerTag`="%s"', $playerTag);
		mysqli_query($this->connect, $sql);
		return 1;
	}
	
	public function deletePlayer($playerTag) {
		$playerTag = mysqli_real_escape_string($this->connect, $playerTag);
		$sql = sprintf('DELETE FROM `players` WHERE `players`.`playerTag`="%s"', $playerTag);
		mysqli_query($this->connect, $sql);
	}
	
	
	public function addToQueue($playerTag, $yt_id) {
		$yt_id = mysqli_real_escape_string($this->connect, $yt_id);
		$playerTag = mysqli_real_escape_string($this->connect, $playerTag);
		$count = mysqli_fetch_array(mysqli_query($this->connect, sprintf('SELECT COUNT(ID) FROM `queue` WHERE `queue`.`playerTag`="%s"', $playerTag)))[0];
		$sql = sprintf('INSERT INTO `queue` (`ID`, `playerTag`, `yt_id`, `queue_pos`) VALUES (NULL, "%s", "%s", "%u");', $playerTag, $yt_id, $count);
		return mysqli_query($this->connect, $sql);
	}
	
	public function deleteFromQueue($playerTag, $queue_pos) {
		if(!is_numeric($queue_pos)) return false;
		$playerTag = mysqli_real_escape_string($this->connect, $playerTag);
		$sql = sprintf('DELETE FROM `queue` WHERE `playerTag`="%s" AND `queue_pos`=%u;
		UPDATE `queue` SET `queue_pos`=(`queue_pos`-1) WHERE `playerTag`="%s" AND `queue_pos`>%u;',$playerTag, $queue_pos, $playerTag, $queue_pos);
		return mysqli_multi_query($this->connect, $sql);
		//echo $sql;
	}
	
	public function changeVid($playerTag, $queue_pos) {
		if(!is_numeric($queue_pos)) return false;
		$playerTag = mysqli_real_escape_string($this->connect, $playerTag);
		$count_query = sprintf('SELECT COUNT(ID) FROM `queue` WHERE `queue`.playerTag="%s"', $playerTag);
		$count = mysqli_fetch_array(mysqli_query($this->connect, $count_query))[0];
		if(!($queue_pos >= 0 && $queue_pos < $count)) return false;
		$sql = sprintf('UPDATE `players` SET `nowPlaying` = %u WHERE `players`.`playerTag` = "%s"', $queue_pos, $playerTag);
		return mysqli_query($this->connect, $sql);
	}
	
}

$db = new db();

$DB_INCLUDE = true;