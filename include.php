<?php if(!isset($BASE)) { http_response_code(403); exit('403 Forbidden'); }

session_start();

require_once 'db.php';

function err($name, $clear = true) {
	$retval = false;
	if(isset($_SESSION['error']['home'][$name])) {
		if($_SESSION['error']['home'][$name] != false)
			$retval = $_SESSION['error']['home'][$name];
		else
			$retval = 'ERROR DESCRIPTION';
		if($clear)
			unset($_SESSION['error']['home'][$name]);
	}
	return $retval;
}