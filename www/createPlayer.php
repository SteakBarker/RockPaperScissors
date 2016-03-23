<?php
	require_once('cleanData.php');
	require_once('ranString.php');

	function createPlayer($name){
		$name = cleanData_Alphanumeric($name);
		
		if(empty($name)){
			return null;
		}else{
			require_once('mysql_connect.php');
			$dbc = createDefaultConnection('games');
			
			$query = "SELECT * FROM player WHERE id=?";
			$id = 0;
			$stmt = $dbc->stmt_init();
			for ($i = 0; $i<=100; $i++) {
				if(!$stmt->prepare($query)){
					error_log("createPlayer statment failed to prepare - ".$stmt->error,0);
					$dbc->close();$stmt->close();
					return null;
				}
				$id = randomString_Alphanumeric(5);
				$stmt->bind_param("s", cleanData_Alphanumeric($id));
				$stmt->execute();

				$result = $stmt->get_result();
				$row = $result->fetch_array();
				if($row === null){
					//The ID has not been used
					break;
				}
				if($i >= 25){ // We will only give it 25 tries
					error_log("It seems the impossible has happend -- createPlayer was unable to find a unique ID ", 0);
					$dbc->close();$stmt->close();
					return null;
				}
			}
			$stmt->close();
			
			$stmt = $dbc->prepare('INSERT INTO player (id, name, seen) VALUES(?, ?, 1)');
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
?>