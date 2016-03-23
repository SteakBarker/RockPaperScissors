<?php
	function createDefaultConnection($dbname){
		
		$servername = "localhost";
		$username = "Web";
		$password = "Web_avPpsGK";
		
		$dbc = new mysqli($servername, $username, $password, $dbname);
		
		if ($dbc->connect_error) {
			error_log("Could not create DBConnection! " . $dbc->connect_error, 0);
			echo "An error occured!";
			exit();
		}
		return $dbc;
	}
	
	function createConnection($servername, $username, $password, $dbname){
		$dbc = new mysqli($servername, $username, $password, $dbname);
		
		if ($dbc->connect_error) {
			error_log("Could not create DBConnection! " . $dbc->connect_error, 0);
			echo "An error occured!";
			exit();
		}
		return $dbc;
	}
?>
