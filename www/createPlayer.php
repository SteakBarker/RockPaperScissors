<?php
	require_once('cleanData.php');
	require_once('ranString.php');
	
		//This function does as it says
		//It will create a player, give it a random ID, and return that ID
	function createPlayer($name){
		$name = cleanData_Alphanumeric($name, 10);
			//First we clean their name
		
		if(empty($name)){
			return null;
		}else{
			require_once('mysql_connect.php');
			$dbc = createDefaultConnection('games');
			
				//We use this query to see if that random ID we made is taken
			$query = "SELECT * FROM player WHERE id=?";
			$id = 0;
			$stmt = $dbc->stmt_init();
			while(true) {
				if(!$stmt->prepare($query)){
					error_log("createPlayer statment failed to prepare - ".$stmt->error,0);
					$dbc->close();$stmt->close();
					return null;
				}
				
				$id = randomString_Alphanumeric(5); //Create a random 5 char string
				$stmt->bind_param("s", $id); //Bind it to our query
				$stmt->execute();

				$result = $stmt->get_result();
				$row = $result->fetch_array();
				if($row === null){
					//If the row is empty, then there is no player with that ID -- its not taken
					break;
				}
			}
			$stmt->close();	//We can close our stmt, we are done with it, and making a new one
			
			$stmt = $dbc->prepare('INSERT INTO player (id, name) VALUES(?, ?)');
			$stmt->bind_param('ss',$id, $name);
			
			$worked = $stmt->execute();
			
			$dbc->close();
			
			if($worked){
				$stmt->close();
				return $id;	
			}else{
				error_log("Unable to insert new player into table - ".$stmt->error, 0);
				$stmt->close(); //We should close the statement after we get the error
				return null;
			}
		}
	}
	
	function getPlayerName($id){
		require_once('../cleanData.php');
		$id = cleanData_Alphanumeric($id, 5);
		
		require_once('../mysql_connect.php');
		$dbc = createDefaultConnection('games');
		
		$query = "SELECT name FROM player WHERE id=?";
		$stmt = $dbc->stmt_init();
		
		if(!$stmt->prepare($query)){
			error_log("createPlayer statment failed to prepare - ".$stmt->error,0);
			$dbc->close(); $stmt->close();
			exit("Error");
		}
			
		$stmt->bind_param("s",$id);
		$worked = $stmt->execute();
		
		if(!$worked){
			error_log("create statement failed - ".$stmt->error,0);
			$dbc->close(); $stmt->close();
			exit("Error");
		}
		$result = $stmt->get_result();
		$row = $result->fetch_array();
		
		$dbc->close(); $stmt->close();
		
		if($row["name"] === null){
			error_log("No player with ID ".$id,0);
			return null;
		}else{
			return $row["name"];
		}
	}
?>