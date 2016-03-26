<!DOCTYPE HTML>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/defaultStyle.css">
	<script src="js/zepto.js"></script>
	<script src="js/joinGame.js"></script>
</head>
<body>
	<div class="menu">
		<input type="submit" name="submit" value = "Home" onclick="window.location='index.html'"/>
	</div>
	<div class="container" style="max-width:700px">
		<h1 class="title">Join Game</h1>
		
		<div id="input" class="buttons">
			<p>Game ID: <input id = "game_id" type="text" name="game_id" value = "<?php require_once('../cleanData.php'); echo cleanData_Alphanumeric($_GET["id"],4);?>"/></p>
			<p>Your Name: <input id = "name" type="text" name="name" value = ""/></p>
			<input type="submit" name="submit" value = "Join Game" onclick="createGame()"/>
		</div>
		
		<div id="result" class="buttons" style="display:none;">
			<h1 class="title">Joined Successfully</h1>
			<p>Personal Link: <a href="" id="p_link"></a><p>
			<br><p>Use your personal link to join the game. Don't lose your Personal link!</p>
		</div>
	</div>
	
	<div id="loading" class="overlay" style="display:none;"></div>
</body>
</html>