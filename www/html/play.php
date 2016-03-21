<?php

	function cleanData($data){
		
		$data = preg_replace('/([^A-Za-z0-9])*/', "", $data);
		$data = trim($data);
		$data = strip_tags($data);
		$data = stripcslashes($data);
		return htmlentities($data);
	}

	if(empty($_GET["id"]) || empty($_GET["userid"])){
		exit("Invalid data");
	}
	$id = cleanData($_GET["id"]);
	$login_id = cleanData($_GET["userid"]);
	
	require_once('../mysql_connect.php');
	$dbc = createDefaultConnection('games');
	
	$stmt = $dbc->prepare('select * from game where id = ?');
	
	$stmt->bind_param('s', $id);
	$stmt->execute();
	
	$result = $stmt->get_result();
	echo $result;
	echo "test";
	
	$stmt->close();
	$dbc->close();
?>