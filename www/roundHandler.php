<?php
	//Will return $numb amount of rounds from $file
	function getRounds($file, $numb){
		$file = "/games/".$file.".txt";
		if(!file_exists($file)){
			error_log("Could not find file ".$file);
			return null;
		}
		
		$roundFile = fopen($file, "r") or die("Unable to open file");
		$results = substr(fread($roundFile,filesize($file), -$numb));
		fclose($roundFile);
		
		return $results;
	}
		//The results should look like this  'rp' p1=rock, p2=paper
	function addRound($file, $results, $playerpos){
		
		if(($playerpos!=1 && $playerpos!=2) || (preg_match($results, "([^rpsn]+)|.{2,}"))){
			error_log("addRound recieved invalid data");
			exit("Error");
		}
		
		$file = "/games/".$file.".txt";
		
		if(!file_exists($file)){
			error_log("Could not find file ".$file);
			exit("Error");
		}
		
		$roundFile = fopen($file, "r+") or die("Unable to open file");
		
		$contents = fread($roundFile, filesize($file));
		$lastTwo = substr($contents, -2); //Gets last 2 chars
		$contents = substr($contents, 0, -2);
			//Removes the last 2 chars
		echo $contents;
		
		$p1_move;
		$p2_move;
		
		if($playerpos==1){
			$p1_move = $results;
			$p2_move = $lastTwo[1];
		}else{
			$p1_move = $lastTwo[0];
			$p2_move = $results;
		}
		
		$contents = $contents.$p1_move.$p2_move;
		
		if(!$p1_move || !$p2_move){
			error_log("p1_move or p2_move are not set! roundHandler");
			exit("Error");
		}
		
		if($p1_move != 'n' && $p2_move != 'n'){
			fwrite($roundFile, $contents."nn");
		}else{
			fwrite($roundFile, $contents);
		}
		
		fclose($roundFile);
	}
	
	function createRoundFile($file){
		$file = "/games/".$file.".txt";
		
		$roundFile = fopen($file, "w") or die("Unable to open file");
		fwrite($roundFile, "nn");
		fclose($roundFile);
	}
	
	function fileExists($file){
		$file = "/games/".$file.".txt";
		return file_exists($file);
	}
?>