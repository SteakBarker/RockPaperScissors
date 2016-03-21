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
	
	//TODO
	//This is where we should check if the game is full, and if it's not, allow the user to join
	
	//NO IDEA IF THIS WILL WORK. NO WAY TO TEST IT RIGHT NOW
	
	if($result == '0'){
		//The game is not filled. We can allow them to join
		
		require_once('../createPlayer.php');
		$player_data = createPlayer($name);
			//Player data is an arary["id","login_id]
			
		//TODO add the new player to the table, then set fileld to true (1)
		
		$query = "update game set p1_id=?, filled=1";
		$stmt = $dbc->stmt_init();
		if(!$stmt->prepare($query)){
			echo "Statement failed to prepare!";
		}else{
			$stmt->bind_param("s", $player_data["id"]);
			$worked = $stmt->execute();
			
			if($worked){
				echo "You've been added";
				//TODO: Create them a link to join
			}
		}
}

$stmt->execute();
$result = $stmt->get_result();
?>