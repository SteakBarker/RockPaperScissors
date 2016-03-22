<?php
require_once('../mysql_connect.php');
require_once('../cleanData.php');
require_once('../ranString.php');

function createRounds(){
	$dbc = createDefaultConnection('games');
	$stmt_rounds = $dbc->prepare('INSERT INTO rounds(id, r1, r2, r3, r4, r5) VALUES(NULL,NULL,NULL,NULL,NULL,NULL)');
	$stmt_rounds->execute();
	return $dbc->insert_id;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
	
	//First we gotta create player 1
	require_once('../createPlayer.php');
	$name = cleanData_Alphanumeric($_POST["name"]);
	
	if(empty($name)){
		exit('Invalid data');
	}
	
	$player_data = createPlayer($name);
		//TODO check to make sure its not null
	
	$dbc = createDefaultConnection('games');
	
	$rounds_id = createRounds();
	
	//Lets now create a uniqe id.
	
	$query = "SELECT * FROM game WHERE id=?";
	$stmt = $dbc->stmt_init();
	$id = 0;
	for ($i = 0; $i<=100; $i++) {
		if(!$stmt->prepare($query)){
			$dbc->close();
			exit("Statement failed to prepare!");
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
			$stmt->close();
			$dbc->close();
			exit("Could not find uniqe code");
		}
	}
	
	$stmt = $dbc->prepare('INSERT INTO game (id, p1_id, p2_id, rounds_id, filled, currentRound, date) VALUES(?,?,NULL,?,0,?,NULL)');
	
	$cround = 1;
	$stmt->bind_param('iiii',$id, $player_data["id"], $rounds_id, $cround);
	
	$worked = $stmt->execute();
	
	if($worked){
		$gameLink = "play.php?id=".$id."&userid=".$player_data["login_id"];
		
		echo "<h1>Game Created Successfully </h1>
			<p> Game Code: ".$id."</p>
			<p> Your Link: <a href=".$gameLink.">".$gameLink."</a><p>
			<p> Give your Game Code to your friends so they can battle you! Also, KEEP YOUR GAME LINK PRIVATE";
	}else{
		echo "<h1>Game Creation Falied!</h1>";
	}
		
	$stmt->close();
	$dbc->close();
}else{
	//The request type wasn't POST
	echo 'Invalid request';
}
?>