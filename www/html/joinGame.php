<!DOCTYPE HTML>

<html>
<head>
<script src="js/jquery-1.12.0.js"></script>
</head>
</body>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<div class="container">
		<h1>Join Game</h1>
		
		<form action="demo_form.asp" method="get">
			Game ID: <input type="text" name="id" value="<?php require_once('../cleanData.php'); echo cleanData_Alphanumeric($_GET["id"]);?>"><br>
			Name: <input type="text" name="name"><br>
			<input type="submit" formmethod="get" formaction="join.php" value="Join">
		</form>
	</div>
</meta>
</body>
</html>