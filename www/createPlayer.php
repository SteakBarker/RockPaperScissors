<?php
	require_once('cleanData.php');
	
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	function createPlayer($name){
		$name = cleanData_Alphanumeric($name);
		
		if(empty($name)){
			return "No Data";
		}else{
			require_once('mysql_connect.php');
			$dbc = createDefaultConnection('games');
			
			$stmt = $dbc->prepare('INSERT INTO player (id, name, seen, login_id) VALUES(NULL, ?, 1, ?)');
			$login_id = generateRandomString(5);
			$stmt->bind_param('ss', $name, $login_id);
			
			$worked = $stmt->execute();
			
			if($worked){
				$id = $dbc->insert_id;
				
				$array["id"] = $id;
				$array["login_id"] = $login_id;
				return $array;	
			}else{
				return "Error";
			}
		}
	}
?>