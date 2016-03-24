<!DOCTYPE HTML>
<html>
<head>
	<script src="js/zepto.js"></script>
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
	<input type="submit" name="submit" value = "Test" onclick="getData()"/>
	<p id="message">loading<p>
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
			
			if(!history || !playerPos || !canPlay){
				$('#message').text("Error: "+output);
			}else{
				//$('#message').text("");
				//$("#p_link").prop("href", personal_link);
				//$("#j_link").prop("href", join_link);
			
				//$('#game_code').text("Game Code: ".concat(game_code));
				//$('#p_link').text(personal_link);
				//$('#j_link').text(join_link);
				alert(results.toString());
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
</script>

</html>