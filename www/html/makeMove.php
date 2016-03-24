<?php
//This file will take in 3 inputs: gam id, user Id, and user move and see if that game exists, and if they can make their move, then make the move

if($_SERVER['REQUEST_METHOD'] === 'POST'){
	
	if(empty($_POST["id"]) || empty($_POST["userid"] || empty($_POST["move"]))){
		exit('Invalid data');
	}
	
	require_once('../cleanData.php');
	$id = cleanData_Alphanumeric($_POST["id"]);
	$uid = cleanData_Alphanumeric($_POST["userid"]);
	$move = cleanData_Alphanumeric($_POST["move"]);
	
	require_once('../mysql_connect.php');
	$dbc = createDefaultConnection('games');
	
	$query = "SELECT p1_id, p2_id, rounds_id FROM game WHERE id=? and (p1_id=? or p2_id=?)";
		//This querry just sees if there is a game with that player in it.
	
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
		//This means there is no game with that ID
		$dbc->close(); $stmt->close();
		exit("Invalid");
	}
	
	$ppos; //Player pos. Playe 1 or player 2
	if($row["p1_id"] == $uid){
		$ppos = 1;
	}else if($row["p2_id"] == $uid){
		$ppos = 2;
	}else{
		//This means there is no user with that id
		$dbc->close(); $stmt->close();
		exit("Invalid");
	}
	
	$rounds_id = $row["rounds_id"];
	
	$dbc->close(); $stmt->close();
		//we close these. Don't need them anymore.
	
	require_once('../roundHandler.php');
	$canMove = canPlayerMove($rounds_id, $ppos);
	
	if($canMove){
		addRound($rounds_id, $move, $ppos);
	}else{
		echo "You cannot make your throw twice";
	}
}else{
	exit("Invalid request");
}
?>