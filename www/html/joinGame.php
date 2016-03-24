<!DOCTYPE HTML>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/defaultStyle.css">
	<!-- <script src="js/zepto.js"></script> -->
</head>
<body>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<div class="center">
		<h1 class="title">Join Game</h1>
		
		<form action="demo_form.asp" method="get">
			Game ID: <input type="text" name="id" value="<?php require_once('../cleanData.php'); echo cleanData_Alphanumeric($_GET["id"],4);?>"><br>
			Name: <input type="text" name="name"><br>
			<input type="submit" formmethod="get" formaction="join.php" value="Join">
		</form>
	</div>
</meta>
</body>
</html>