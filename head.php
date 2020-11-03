<?php if(!isset($BASE)) { http_response_code(403); exit('403 Forbidden'); }?><!DOCTYPE html>

<html lang="pl">

<head>

	<title></title>
	
	<meta charset="utf-8" />
	
	<meta name="description" content="YT QUEUE" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
	<meta name="author" content="Odccjishie" />
	<link rel="shortcut icon" type="image/x-icon" href="" />
	
	<link rel="stylesheet" href="css/main.css" />

	<script src="/lib/jquery.js"></script>
	<script src="https://www.youtube.com/iframe_api"></script>
	<?php if($type != 'home'): ?><script src="js/main.js"></script><?php endif; ?>
	<script src="js/<?=$type?>.js"></script>
	
</head>