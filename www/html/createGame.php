<?php
require_once('../mysql_connect.php');
require_once('../cleanData.php');

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
	
	require_once('../mysql_connect.php');
	$dbc = createDefaultConnection('games');
	
	$rounds_id = createRounds();
	
	$stmt = $dbc->prepare('INSERT INTO game (id, p1_id, p2_id, rounds_id, filled, currentRound, date) VALUES(NULL,?,NULL,?,0,?,NULL)');
	
	$cround = 1;
	$stmt->bind_param('iii',$player_data["id"], $rounds_id, $cround);
	
	$worked = $stmt->execute();
	$id = $dbc->insert_id;
	
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