<!DOCTYPE HTML>
<html>
<head>
	<script src="js/zepto.js"></script>
	<link rel="stylesheet" type="text/css" href="css/defaultStyle.css">
</head>
<body>
	<h1> History </h1>
	<table id="table" style="width:100%" border="1">
		<tr>
			<td>Your Move</td>
			<td>Their Move</td>
			<td>Did you win?</td>
		</tr>
	</table>
	
	<div id="moves" class="center" style="display:none;">
		<input type="submit" name="submit" value = "ROCK" onclick="play('r')"/>
		<input type="submit" name="submit" value = "PAPER" onclick="play('p')"/>
		<input type="submit" name="submit" value = "SCISSORS" onclick="play('s')"/>
	</div>
	
	<p id="message"></p>
</body>

<script>
	var p_id = '<?php require_once('../cleanData.php'); echo cleanData_Alphanumeric($_GET["userid"]);?>';
	var id = '<?php require_once('../cleanData.php'); echo cleanData_Alphanumeric($_GET["id"]);?>';
	
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
				}
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
				alert(data);
			}
		});
	}
	
	$( document ).ready(function() {
		getData();
	});
</script>

</html>