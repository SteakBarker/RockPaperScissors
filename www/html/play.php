<!DOCTYPE HTML>
<html>
<head>
	<script src="js/zepto.js"></script>
	<link rel="stylesheet" type="text/css" href="css/playStyle.css">
</head>
<body>
	<div class="menu">
		<input type="submit" name="submit" value = "Home" onclick="goHome()"/>
		<input type="submit" name="submit" value = "Refresh" onclick="getData()"/>
	</div>
	<div class="center">
		<h1 id="result">LOADING</h1>
	</div>
	
	<div class="center">
		<p id="yourName" class="inline">[NAME]</p>
		<h3 id="vs" class="inline"> VS </h3>
		<p id="theirName" class="inline">[NAME]</p>
	</div>
	
	<div id="table_div">
		<table id="table" style="width:100%" border="1">
			<tr>
				<td>Your Move</td>
				<td>Result</td>
				<td>Their Move</td>
			</tr>
		</table>
	</div>
	
	<div id="moves" class="center" style="display:none;">
		<input type="submit" name="submit" value = "ROCK" onclick="play('r')"/>
		<input type="submit" name="submit" value = "PAPER" onclick="play('p')"/>
		<input type="submit" name="submit" value = "SCISSORS" onclick="play('s')"/>
	</div>
</body>

<script>
	var p_id = '<?php require_once('../cleanData.php'); echo cleanData_Alphanumeric($_GET["userid"],5);?>';
	var id = '<?php require_once('../cleanData.php'); echo cleanData_Alphanumeric($_GET["id"],4);?>';
</script>
<script src="js/play.js"></script>

</html>