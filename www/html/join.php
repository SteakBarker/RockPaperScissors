<?php
if($_SERVER['REQUEST_METHOD'] === 'POST'){
	require_once('../cleanData.php');

	if(empty($_POST["game_id"]) || empty($_POST["name"])){
		echo json_encode(array('success' => 0, 'error' => 'Error'));
		exit();
	}

	$id = cleanData_Alphanumeric($_POST["game_id"], 4);
	$name = cleanData_Alphanumeric($_POST["name"], 10);

	require_once('../mysql_connect.php');
	$dbc = createDefaultConnection('games');

		//This is our querry that will see if there is any game with that ID
	$query = "SELECT filled FROM game WHERE id=?";
	$stmt = $dbc->stmt_init();

	if(!$stmt->prepare($query)){
		error_log("join statment failed to prepare - ".$stmt->error,0);
		$dbc->close(); $stmt->close();
		echo json_encode(array('success' => 0, 'error' => 'Error'));
		exit();
	}

	$stmt->bind_param("s", $id);
	$stmt->execute();

	$result = $stmt->get_result();
	$row = $result->fetch_array();

	if($row["filled"] === null){
			//If row[filled] is null, then there isn't a game with that ID
		$dbc->close(); $stmt->close();
		echo json_encode(array('success' => 0, 'error' => 'Game does not exist, or has been filled'));
		exit();
	}

	if($row["filled"] == 0){
		//If filled is 0, then the game is not filled. Lets fill it.
		
		require_once('../createPlayer.php');
		$player_id = createPlayer($name);
			//Lets create a new player
		if($player_id == null){
			$dbc->close(); $stmt->close();
			echo json_encode(array('success' => 0, 'error' => 'Error'));
			exit();
		}
				
		$stmt->close();	
		$query = "update game set p2_id=?, filled=1 where id=?";
		$stmt = $dbc->stmt_init();
		if(!$stmt->prepare($query)){
			error_log("join statment failed to prepare - ".$stmt->error,0);
			$dbc->close(); $stmt->close();
			echo json_encode(array('success' => 0, 'error' => 'Error'));
			exit();
		}else{
			$stmt->bind_param("ss", $player_id, $id);
			$worked = $stmt->execute();
			$dbc->close();
			
			if($worked){
				$stmt->close();
				
				$gameLink = "play.php?id=".$id."&userid=".$player_id;
					
				$arr = array('success' => 1, 'p_link' => $gameLink);
				echo json_encode($arr);
			}else{
				error_log("join statment failed to work - ".$stmt->error,0);
				$stmt->close();
				echo json_encode(array('success' => 0, 'error' => 'Error'));
				exit();
			}
		}
	}else{
		$dbc->close(); $stmt->close();
		echo json_encode(array('success' => 0, 'error' => 'Game does not exist, or has been filled'));
		exit();
	}
}else{
	exit("Invalid request");
}
?>