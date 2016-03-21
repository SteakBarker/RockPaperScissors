<?php
	require_once('../cleanData.php');
	
	if(empty($_GET["id"]) || empty($_GET["userid"])){
		exit("Invalid data");
	}
	$id = cleanData_Alphanumeric($_GET["id"]);
	$login_id = cleanData_Alphanumeric($_GET["userid"]);
	
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