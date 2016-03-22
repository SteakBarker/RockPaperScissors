<?php
	require_once('cleanData.php');
	require_once('ranString.php');

	function createPlayer($name){
		$name = cleanData_Alphanumeric($name);
		
		if(empty($name)){
			return "No Data";
		}else{
			require_once('mysql_connect.php');
			$dbc = createDefaultConnection('games');
			
			$query = "SELECT * FROM player WHERE id=?";
			$id = 0;
			$stmt = $dbc->stmt_init();
			for ($i = 0; $i<=100; $i++) {
				if(!$stmt->prepare($query)){
					$dbc->close();
					exit("Statement failed to prepare!");
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
					$stmt->close();
					$dbc->close();
					exit("Could not find uniqe code");
				}
			}
			
			$stmt = $dbc->prepare('INSERT INTO player (id, name, seen) VALUES(?, ?, 1)');
			$stmt->bind_param('ss',$id, $name);
			
			$worked = $stmt->execute();
			
			if($worked){
				return $id;	
			}else{
				return "Error";
			}
		}
	}
?>