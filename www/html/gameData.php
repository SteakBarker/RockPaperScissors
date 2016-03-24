<?php
if($_SERVER['REQUEST_METHOD'] === 'POST'){
	if(empty($_POST["id"]) || empty($_POST["userid"])){
		exit('Invalid data');
	}
	
	require_once('../createPlayer.php');
	$id = cleanData_Alphanumeric($_POST["id"]);
	$uid = cleanData_Alphanumeric($_POST["userid"]);
	
	require_once('../mysql_connect.php');
	$dbc = createDefaultConnection('games');
	
	$query = "SELECT p1_id, p2_id, rounds_id FROM game WHERE id=? and (p1_id=? or p2_id=?)";
	
	$stmt = $dbc->stmt_init();
	
	if(!$stmt->prepare($query)){
		error_log("gameData statment failed to prepare - ".$stmt->error,0);
		$dbc->close(); $stmt->close();
		exit("Error");
	}
		
	$stmt->bind_param("sss",$id, $uid, $uid);
	$worked = $stmt->execute();
	
	if(!$worked){
		error_log("gameData statement failed - ".$stmt->error,0);
		$dbc->close(); $stmt->close();
		exit("Error");
	}
	$result = $stmt->get_result();
	$row = $result->fetch_array();
	
	if(!$row){
		$dbc->close(); $stmt->close();
		exit("Cannot find valid game");
	}
	
	$ppos; //Player pos. Playe 1 or player 2
	if($row["p1_id"] == $uid){
		$ppos = 1;
	}else if($row["p2_id"] == $uid){
		$ppos = 2;
	}else{
			//This might be dead code because of the if(!row) above
		$dbc->close(); $stmt->close();
		
		exit("You're not a valid player". $uid . " " . $row["p1_id"] . " " . $row["p2_id"]);
	}
	$rounds_id = $row["rounds_id"];
	
	$dbc->close(); $stmt->close();
	//So now we know the player is valid!
	
	require_once('../roundHandler.php');
	
	$history = getRounds($rounds_id, $uid, 10);
	
	$canMove = canPlayerMove($rounds_id, $ppos);
	
	$array = array('history' => $history, 'ppos' => $ppos, 'canPlay' => $canMove);
	echo json_encode($array);
}else{
	exit("Invalid request type");
}
?>