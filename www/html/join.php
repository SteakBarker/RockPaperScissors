<?php

function cleanData($data){
		
	$data = preg_replace('/([^A-Za-z0-9])*/', "", $data);
	$data = trim($data);
	$data = strip_tags($data);
	$data = stripcslashes($data);
	return htmlentities($data);
}

if(empty($_GET["id"]) || empty($_GET["name"])){
	exit("Invalid data");
}
$id = cleanData($_GET["id"]);
$name = cleanData($_GET["name"]);

//This method to read from the DB is ripped from the PHP doc

require_once('../mysql_connect.php');
$dbc = createDefaultConnection('games');


$query = "SELECT filled FROM game WHERE id=?";
$stmt = $dbc->stmt_init();

if(!$stmt->prepare($query))
{
	echo "Statement failed to prepare!";
}else{
	$stmt->bind_param("s", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	
	if(empty($result)){
			//Eh. Good enough for now.
		exit("Could not find game with that ID");
	}
	
	//TODO
	//This is where we should check if the game is full, and if it's not, allow the user to join
	
	//NO IDEA IF THIS WILL WORK. NO WAY TO TEST IT RIGHT NOW
	
	if($result == '0'){
		//The game is not filled. We can allow them to join
		
		require_once('../createPlayer.php');
		$player_data = createPlayer($name);
			//Player data is an arary["id","login_id]
			
		$query = "update game set p1_id=?, filled=1";
		$stmt = $dbc->stmt_init();
		if(!$stmt->prepare($query)){
			echo "Statement failed to prepare!";
		}else{
			$stmt->bind_param("s", $player_data["id"]);
			$worked = $stmt->execute();
			
			if($worked){
				echo "You've been added";
				$gameLink = "play.php?id=".$id."&userid=".$player_data["login_id"];
				
				echo "<h1>Game Joined Successfully </h1>
					<p> Your Link: <a href=".$gameLink.">".$gameLink."</a><p>
					<p> Use your game link to play. Keep it private";
			}
		}
	}
}

$stmt->execute();
$result = $stmt->get_result();
?>