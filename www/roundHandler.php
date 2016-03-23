<?php
	//Will return $numb amount of rounds from $file
	function getRounds($file, $numb){
		$file = "/var/www/games/".$file;
		if(!file_exists($file)){
			error_log("Could not find file ".$file);
			return null;
		}
		
		$roundFile fopen($file, "r") or die("Unable to open file");
		$results = substr(fread($roundFile,filesize($file), -$numb);
		fclose($roundFile);
		
		return $results;
	}
		//The results should look like this  'rp' p1=rock, p2=paper
	function addRound($file, $results){
		$file = "/var/www/games/".$file;
		
		if(!file_exists($file)){
			error_log("Could not find file ".$file);
		}
		
		$roundFile fopen($file, "a") or die("Unable to open file");
		fwrite($roundFile, $results);
		fclose($roundFile);
	}
	
	function fileExists($file){
		$file = "/var/www/games/".$file;
		return file_exists($file);
	}
?>