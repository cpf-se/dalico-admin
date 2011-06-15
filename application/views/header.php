<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="/cpf.css" media="all" />
		<title>DALICO</title>
	</head>
<?php
$CI = &get_instance();
$CI->load->model('UserDataModel');
$userdata = $CI->UserDataModel->get_user_data($CI->tank_auth->get_user_id());
?>
	<body>
		<div id="head"><img src="/logo_head_dalby.jpg" /></div>
		<div id="topright">
		<strong><?=$userdata['firstname']?>&nbsp;<?=$userdata['lastname']?></strong>&nbsp;&lt;<?=$userdata['email']?>&gt;<br />
			<a href="/">Hem</a> -
			<a href="/auth/logout">Logga ut</a> -
			<a href="/auth/change_password">Byt lösenord</a> -
			<a href="/auth/change_email">Byt emailadress</a><!-- -
			<a href="/bugs">Buggar</a-->
		</div>
