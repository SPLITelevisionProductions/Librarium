<!DOCTYPE html>
<html lang="en" class="<?php if(isset($pageclass)){ echo $pageclass; }?>">
	<head>
		<meta charset="utf-8">
		<title>Librarium – <?php if(isset($title)){ echo $title; }?></title>
		<script type="text/javascript" src="/resources/js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="/resources/js/jscolor.min.js"></script>
		<link rel="stylesheet" href="/resources/css/<? if (isset($_COOKIE['theme'])) { echo($_COOKIE['theme']); } else { echo('niagara'); }?>.css">
		<link  href="/resources/js/cropperjs/cropper.css" rel="stylesheet">
		<script src="/resources/js/cropperjs/cropper.js"></script>
		<script type="text/javascript" src="/resources/js/bookmanagement.js"></script>
		<!-- Web App Mode -->
		<meta name="viewport" content="width=device-width, user-scalable=no">
		<meta name="apple-mobile-web-app-title" content="Librarium.">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<link rel="apple-touch-icon" sizes="57x57" href="/resources/images/webapp/icon-57.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/resources/images/webapp/icon-72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/resources/images/webapp/icon-76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/resources/images/webapp/icon-114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/resources/images/webapp/icon-120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/resources/images/webapp/icon-144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/resources/images/webapp/icon-152.png">
		<link rel="apple-touch-icon" sizes="167x167" href="/resources/images/webapp/icon-167.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/resources/images/webapp/icon-180.png">
		<link rel="manifest" href="/resources/js/appmode.json">
		<meta name="theme-color" content="#a91034">
	</head>
	<? if($pagetype == 'coll'){?>
	<body onload="getBooks('curr')">
	<? } elseif($pagetype == 'pubcoll') { ?>
	<body onload="getBooks(<?=$getID?>)">
	<? } else {?>
	<body>
	<? } ?>
