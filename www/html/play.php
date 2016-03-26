<!DOCTYPE HTML>
<html>
<head>
	<script src="js/zepto.js"></script>
	<link rel="stylesheet" type="text/css" href="css/playStyle.css">
	<link rel="stylesheet" type="text/css" href="css/defaultStyle.css">
	<link rel="stylesheet" type="text/css" href="css/tableStyle.css">
</head>
<body>
	<div class="menu">
		<input type="submit" name="submit" value = "Home" onclick="window.location='index.html'"/>
		<input type="submit" name="submit" value = "Refresh" onclick="getData()"/>
	</div>
	
	<div class="container" style="max-width:1000px">
		<h1 id="result" class="title">LOADING</h1>
		
		<div id="vs_div">
			<p id="yourName">[NAME]</p>
			<h3> VS </h3>
			<p id="theirName">[NAME]</p>
		</div>
		
		<div class="tableContainer" style="width:70%">
			<table id="table">
				<tr>
					<td>Your Move</td>
					<td>Result</td>
					<td>Their Move</td>
				</tr>
			</table>
		</div>
		
		<div id="moves" class="buttons" style="display:show;">
			<input type="submit" name="submit" value = "ROCK" onclick="play('r')"/>
			<input type="submit" name="submit" value = "PAPER" onclick="play('p')"/>
			<input type="submit" name="submit" value = "SCISSORS" onclick="play('s')"/>
		</div>
	</div>
	<div id="loading" class="overlay"></div>
</body>
<script>
	var p_id = '<?php require_once('../cleanData.php'); echo cleanData_Alphanumeric($_GET["userid"],5);?>';
	var id = '<?php require_once('../cleanData.php'); echo cleanData_Alphanumeric($_GET["id"],4);?>';
</script>
<script src="js/play.js"></script>

</html>