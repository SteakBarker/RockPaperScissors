<?php
require_once('../cleanData.php');
if(empty($_GET["id"]) || empty($_GET["userid"])){
	exit("Invalid data");
}

$id = cleanData_Alphanumeric($_GET["id"]);
$user_id = cleanData_Alphanumeric($_GET["userid"]);

require_once('../mysql_connect.php');
$dbc = createDefaultConnection('games');
$stmt = $dbc->stmt_init();

$query="SELECT p1_id, p2_id FROM game where id=?";
if(!$stmt->prepare($query)){
	$dbc->close();
	exit("Statement failed to prepare!");
}

$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

$query="SELECT id, login_id FROM player where id=?";
if(!$stmt->prepare($query)){
	$dbc->close();
	exit("Statement failed to prepare!");
}
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row_player = $result->fetch_array();

if($user_id == $row_player["login_id"] && $row["p1_id"] == row_player["id"]){
	echo "You're player 1";
}else if($user_id == $row_player["login_id"] && $row["p2_id"] == row_player["id"]){
	echo "You're player 2";
}

?>

<!DOCTYPE HTML>
<html>
<head>
<script src="js/jquery-1.12.0.js"></script>
</head>
</body>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<div class="container">
		<h1> Rock Paper Scissors </h1>
		
		
		
		<input type="submit" name="submit" value = "Rock" onclick="addCat()"/>
		<input type="submit" name="submit" value = "Paper" onclick="addCat()"/>
		<input type="submit" name="submit" value = "Scissors" onclick="addCat()"/>
	</div>
</meta>
	
<script>
	function createGame(){
		sendAjax(function(output){
			alert(output+"");
		});
	}
		
	function sendAjax(handleData) {
		$.ajax({
			url:"creatGame.php",
			type:'POST',
			data:
			{
				p_name:document.getElementById("p_name").value,
			},
			success:function(data) {
				handleData(data); 
			}
		});
	}
</script>

</body>
</html>