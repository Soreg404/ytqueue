<?php if(!isset($BASE)) { http_response_code(403); exit('403 Forbidden'); }

session_start();

require_once 'db.php';

function err($name) {
	$retval = '';
	if(isset($_SESSION['error']['home'][$name])) {
		if($_SESSION['error']['home'][$name] != false)
			$retval = $_SESSION['error']['home'][$name];
		else
			$retval = '';
		unset($_SESSION['error']['home'][$name]);
	}
	return $retval;
}