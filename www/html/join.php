<?php

function cleanData($data){
		
	$data = preg_replace('/([^A-Za-z0-9])*/', "", $data);
	$data = trim($data);
	$data = strip_tags($data);
	$data = stripcslashes($data);
	return htmlentities($data);
}

if(empty($_GET["id"])){
	exit("Invalid data");
}
$id = cleanData($_GET["id"]);

//This method to read from the DB is ripped from the PHP doc

require_once('../mysql_connect.php');
$dbc = createDefaultConnection('games');


$query = "SELECT * FROM game WHERE id=?";
$stmt = $dbc->stmt_init();

if(!$stmt->prepare($query))
{
	echo "Statement failed to prepare!";
}
else
{
	$stmt->bind_param("s", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	
	//TODO
	//This is where we should check if the game is full, and if it's not, allow the user to join
	
	echo $result;
}

$stmt->execute();
$result = $stmt->get_result();
?>