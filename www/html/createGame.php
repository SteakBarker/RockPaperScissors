<?php
require_once('../mysql_connect.php');
require_once('../cleanData.php');
require_once('../ranString.php');

function createRounds(){
	$dbc = createDefaultConnection('games');
	$stmt_rounds = $dbc->prepare('INSERT INTO rounds(id, r1, r2, r3, r4, r5) VALUES(NULL,NULL,NULL,NULL,NULL,NULL)');
	$worked = $stmt_rounds->execute();
	
	if($worked){
		return $dbc->insert_id;	
	}else{
		error_log("Unable to create new rounds! (createGame) - ".$stmt->error,0);
		exit("Error creating game");
	}
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
	
	if(empty($_POST["name"])){
		exit('Invalid data');
	}
	
	//First we gotta create player 1
	require_once('../createPlayer.php');
	$name = cleanData_Alphanumeric($_POST["name"]);
	
	$dbc = createDefaultConnection('games');
	
	$rounds_id = createRounds();
	
	//Lets now create a uniqe id.
	
	$query = "SELECT * FROM game WHERE id=?";
	$stmt = $dbc->stmt_init();
	$id = 0;
	for ($i = 0; $i<=100; $i++) {
		if(!$stmt->prepare($query)){
			error_log("createGame statment failed to prepare - ".$stmt->error,0);
			$dbc->close(); $stmt->close();
			exit("Error creating game");
		}
		$id = randomString_Numeric(4);
		$stmt->bind_param("s", cleanData_Alphanumeric($id));
		$stmt->execute();

		$result = $stmt->get_result();
		$row = $result->fetch_array();
		if($row === null){
			//The ID has not been used
			break;
		}
		if($i >= 15){ // We will only give it 15 tries
			error_log("It seems the impossible has happend -- createGame was unable to find a unique ID ", 0);
			$dbc->close();$stmt->close();
			exit("Error creating game");
		}
	}
	
	$player_id = createPlayer($name);
	
	if($player_id === null){
		exit("Error creating game");
	}
	
	$stmt->close();
	$stmt = $dbc->prepare('INSERT INTO game (id, p1_id, p2_id, rounds_id, filled, currentRound, date) VALUES(?,?,NULL,?,0,1,NULL)');
	
	$stmt->bind_param('ssi',$id, $player_id, $rounds_id);
	
	$worked = $stmt->execute();
	
	if($worked){
		$dbc->close();$stmt->close();
		
		$gameLink = "play.php?id=".$id."&userid=".$player_id;
		$joinLink = "joinGame.php?id=".$id;
		
		$arr = array('game_id' => $id, 'p_link' => $gameLink, 'j_link' => $joinLink);
		echo json_encode($arr);
	}else{
		error_log("Unable to create game - ".$stmt->error, 0);
		$dbc->close();$stmt->close();
		exit("Error creating game");
	}
}else{
	exit("Invalid request");
}
?>