<?php
require_once('../mysql_connect.php');
require_once('../cleanData.php');
require_once('../ranString.php');

	//This function we create the rounds. Remember, rounds is a file that hold all the history/moves the player has made
function createRounds(){
	require_once('../roundHandler.php');
	$random_id;
	while(true){
		$random_id = randomString_Alphanumeric(5);
		
		if(!fileExists($random_id)){
			//That file does not exists. We can use it as our ID
			break;
		}
	}
	
	createRoundFile($random_id);
		//now we call roundsHandler.createRoundFile(); This will create the file
	return $random_id; //return our new ID
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
	
	if(empty($_POST["name"])){
		exit('Invalid data');
	}
	
	require_once('../createPlayer.php');
	$name = cleanData_Alphanumeric($_POST["name"]);
		//Their name is cleaned in the createPlaer function, but oh-well
	
	$dbc = createDefaultConnection('games');
	
	$rounds_id = createRounds();
		//Now we create our round file
	
		//Used to find a unique ID
	$query = "SELECT * FROM game WHERE id=?";
	$stmt = $dbc->stmt_init();
	$id = 0;
	while(true) {
		if(!$stmt->prepare($query)){
			error_log("createGame statment failed to prepare - ".$stmt->error,0);
			$dbc->close(); $stmt->close();
			exit("Error creating game");
		}
		$id = randomString_Numeric(4);
			//Get a random 4 digit ID.
		$stmt->bind_param("s", cleanData_Alphanumeric($id));
		$stmt->execute();
		
		$result = $stmt->get_result();
		$row = $result->fetch_array();
		
		if($row === null){
			//The ID has not been used
			break;
		}
	}
		//That we we have an ID for our game, and ID for our rounds, we just need to make player 1
	$player_id = createPlayer($name);
	
	if(!$player_id){
		error_log("For whatever reason, playerID is not set. createGame",0);
		exit("Error creating game");
	}
	
	$stmt->close(); //Go ahead and close the statement. We are going to make a new one.
	$stmt = $dbc->prepare('INSERT INTO game (id, p1_id, p2_id, rounds_id, filled, currentRound, date) VALUES(?,?,NULL,?,0,1,NULL)');
	
	$stmt->bind_param('sss',$id, $player_id, $rounds_id);
	
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