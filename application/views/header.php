<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="/cpf.css" media="all" />
		<title>DALICO</title>
	</head>

	<body>
		<div id="head"><img src="/logo_head_dalby.jpg" /></div>
		<div id="topright">
		<strong><?=$userdata['firstname']?>&nbsp;<?=$userdata['lastname']?></strong>&nbsp;&lt;<?=$userdata['email']?>&gt;<br />
		<a href="/">Hem</a>&nbsp;&nbsp;&nbsp;<a href="/auth/logout">Logga ut</a>&nbsp;&nbsp;&nbsp;<a href="/auth/change_password">Byt lösenord</a>&nbsp;&nbsp;&nbsp;<a href="/auth/change_email">Byt emailadress</a>&nbsp;&nbsp;&nbsp;<a href="/bugs">Buggar</a>
		</div>
