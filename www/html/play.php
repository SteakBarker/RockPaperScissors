<!DOCTYPE HTML>
<html>
<head>
	<script src="js/zepto.js"></script>
	<link rel="stylesheet" type="text/css" href="css/playStyle.css">
</head>
<body>
	<div class="center">
		<h1>PENDING</h1>
	</div>
	
	<div class="center">
		<p class="inline">[NAME]</p>
		<h3 class="inline"> VS </h3>
		<p class="inline">[NAME]</p>
	</div>
	
	<div id="table_div">
		<table id="table" style="width:100%" border="1">
			<tr>
				<td>Your Move</td>
				<td>Their Move</td>
				<td>Did you win?</td>
			</tr>
		</table>
	</div>
	
	<div id="moves" class="center" style="display:none;">
		<input type="submit" name="submit" value = "ROCK" onclick="play('r')"/>
		<input type="submit" name="submit" value = "PAPER" onclick="play('p')"/>
		<input type="submit" name="submit" value = "SCISSORS" onclick="play('s')"/>
	</div>
	
	<p id="message"></p>
</body>

<script>
	var p_id = '<?php require_once('../cleanData.php'); echo cleanData_Alphanumeric($_GET["userid"],5);?>';
	var id = '<?php require_once('../cleanData.php'); echo cleanData_Alphanumeric($_GET["id"],4);?>';
	
		function getData(){
		sendAjax(function(output){
			var results = JSON.parse(output);
			
			var history = results["history"];
			var playerPos = results["ppos"];
			var canPlay = results["canPlay"];
			
			if(false){//!history || !playerPos || !canPlay){
				$('#message').text("Error (Something is null): "+output);
			}else{
				if(canPlay){
					$("#moves").show();
				}else{
					$("#moves").hide();
				}
				document.getElementById("table").innerHTML = "<tr><td>Your Move</td><td>Their Move</td><td>Did you win?</td></tr>";
				
				for (var i = 0; i < history.length-1; i+=2) {
					var yourMove;
					var theirMove;
					if(playerPos == 1){
						yourMove=history[i]
						theirMove=history[i+1]
					}else{
						yourMove=history[i+1]
						theirMove=history[i]
					}
					var table = document.getElementById("table");
					var row = table.insertRow(1);
					
					var cell1 = row.insertCell(0);
					var cell2 = row.insertCell(1);
					var cell3 = row.insertCell(2);
					
					cell1.innerHTML = yourMove;
					cell2.innerHTML = theirMove;
					cell3.innerHTML = "TODO";
				}
			}
		});
	}
		
	function sendAjax(handleData) {
		$.ajax({
			url:"gameData.php",
			type:'POST',
			data:
			{
				id:id,
				userid:p_id,
			},
			success:function(data) {
				handleData(data);
				$('#message').text(output);
			}
		});
	}
	
	function play(move){
		$.ajax({
			url:"makeMove.php",
			type:'POST',
			data:
			{
				id:id,
				userid:p_id,
				move:move,
			},
			success:function(data) {
				getData();
				
			}
		});
	}
	
	$( document ).ready(function() {
		getData();
	});
</script>

</html>