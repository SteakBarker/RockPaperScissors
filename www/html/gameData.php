<?php
//This will return an array (JSON encoded) about data from the players game.
	//History:Last 10 rounds, ppos: Player pos (1, 2), canplay:can they make their next move
if($_SERVER['REQUEST_METHOD'] === 'POST'){
	if(empty($_POST["id"]) || empty($_POST["userid"])){
		exit('Invalid data');
	}
	
	require_once('../cleanData.php');
	$id = cleanData_Alphanumeric($_POST["id"], 4);
	$uid = cleanData_Alphanumeric($_POST["userid"], 5);
	
	require_once('../mysql_connect.php');
	$dbc = createDefaultConnection('games');
	
	$query = "SELECT p1_id, p2_id, rounds_id FROM game WHERE id=? and (p1_id=? or p2_id=?)";
		//Used to see if their is a game with that ID that contains a user with that ID
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
		exit("You're not a valid player");
	}
	
	$rounds_id = $row["rounds_id"];
	
	require_once('../createPlayer.php');
	$p1Name = cleanData_Alphanumeric(getPlayerName($row["p1_id"]), 10);
	$p2Name = cleanData_Alphanumeric(getPlayerName($row["p2_id"]), 10);
	
	$dbc->close(); $stmt->close();
	//So now we know the player is valid!
	
	require_once('../roundHandler.php');
	
	$history = getRounds($rounds_id, 10);
	$history = substr($history, 0, -2);
	
	$canMove = canPlayerMove($rounds_id, $ppos);
	
	$array = array('history' => $history, 'ppos' => $ppos, 'canPlay' => $canMove, 'p1Name' => $p1Name, 'p2Name' => $p2Name);
	echo json_encode($array);
}else{
	exit("Invalid request type");
}
?>