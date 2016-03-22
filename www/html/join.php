<?php
require_once('../cleanData.php');

if(empty($_GET["id"]) || empty($_GET["name"])){
	exit("Invalid data");
}

$id = cleanData_Alphanumeric($_GET["id"]);
$name = cleanData_Alphanumeric($_GET["name"]);

//This method to read from the DB is ripped from the PHP doc

require_once('../mysql_connect.php');
$dbc = createDefaultConnection('games');

$query = "SELECT filled FROM game WHERE id=?";
$stmt = $dbc->stmt_init();

if(!$stmt->prepare($query)){
	$dbc->close();
	exit("Statement failed to prepare!");
}

$stmt->bind_param("s", cleanData_Alphanumeric($id));
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_array();

if($row["filled"] === null){
	//Eh. Good enough for now.
	$dbc->close();
	exit("Could not find game with that ID");
}

if($row["filled"] == 0){
	//The game is not filled. We can allow them to join
	
	require_once('../createPlayer.php');
	$player_data = createPlayer($name);
		//Player data is an arary["id","login_id]
			
	$query = "update game set p2_id=?, filled=1 where id=?";
	$stmt = $dbc->stmt_init();
	if(!$stmt->prepare($query)){
		$dbc->close();
		exit("Statement failed to prepare!");
	}else{
		$stmt->bind_param("ss", cleanData_Alphanumeric($player_data["id"]), $id);
		$worked = $stmt->execute();
		
		echo $player_data["id"];
		
		if($worked){
			echo "You've been added";
			$gameLink = "play.php?id=".$id."&userid=".$player_data["login_id"];
			
			echo "<h1>Game Joined Successfully </h1>
				<p> Your Link: <a href=".$gameLink.">".$gameLink."</a><p>
				<p> Use your game link to play. Keep it private";
				$stmt->close();
		}else{
			$stmt->close();
			$dbc->close();
			exit("Failed to work");
		}
	}
}else{
	$dbc->close();
	exit("The game has already been filled");
}

$dbc->close();
?>