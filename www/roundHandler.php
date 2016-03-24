<?php
	//Will return $numb amount of rounds from $file
	function getRounds($file, $numb){
		$file = "/games/".$file.".txt";
		if(!file_exists($file)){
			error_log("Could not find file ".$file);
			return null;
		}
		
		$roundFile = fopen($file, "r") or die("Unable to open file");
		$filecontents = fread($roundFile,filesize($file));
		$results = substr($filecontents, -($numb*2));
		fclose($roundFile);				//Times 2 because there are always 2 chars per round
		
		return $results;
	}
		//The results should look like this  'rp' p1=rock, p2=paper
	function addRound($file, $results, $playerpos){
		
		if(($playerpos!=1 && $playerpos!=2)){ //|| (preg_match($results, "/([^rpsn]+)|.{2,}/"))){
			error_log("addRound recieved invalid data");
			exit("Error");
		}
		
		$file = "/games/".$file.".txt";
		
		if(!file_exists($file)){
			error_log("Could not find file ".$file);
			exit("Error");
		}
		
		$roundFile = fopen($file, "r") or die("Unable to open file");
		
		$file_con = fread($roundFile, filesize($file));
		$lastTwo = substr($file_con, -2); //Gets last 2 chars
		$contents = substr($file_con, 0, -2);
			//Removes the last 2 chars
			
		error_log($lastTwo ." : ". $contents);
		
		
		$p1_move = 'E';
		$p2_move = 'E';
		
		if($playerpos==1){
			$p1_move = $results;
			$p2_move = $lastTwo[1];
		}else{
			$p1_move = $lastTwo[0];
			$p2_move = $results;
		}
		
		if(!$p1_move || !$p2_move){
			error_log("p1_move or p2_move are not set! Setting them to N");
			if(!$p1_move){
				$p1_move = 'n';
			}
			if(!$p2_move){
				$p2_move = 'n';
			}
		}
		
		fclose($roundFile);
		$roundFile = fopen($file, "w") or die("Unable to open file");
		
		$contents = $contents.$p1_move.$p2_move;
		
		if($p1_move != 'n' && $p2_move != 'n'){
			fwrite($roundFile, $contents."nn");
			//fwrite($roundFile, "rrppssnn") or die('fwrite failed');
		}else{
			fwrite($roundFile, $contents);
			//fwrite($roundFile, "rrppss") or die('fwrite failed');
		}
		
		fclose($roundFile);
	}
	
	function createRoundFile($file){
		$file = "/games/".$file.".txt";
		
		$roundFile = fopen($file, "w") or die("Unable to open file");
		fwrite($roundFile, "nn");
		fclose($roundFile);	
		if(!file_exists($file)){
			error_log("Create round was unable to create the file!");
		}
	}
	
	function canPlayerMove($file, $playerpos){
		$file = "/games/".$file.".txt";
		
		if(!is_readable($file)){
			error_log("Could not read file ".$file);
			exit("Error");
		}
		
		$roundFile = fopen($file, "r") or die("Unable to open file");
		
		$contents = fread($roundFile, filesize($file));
		$lastTwo = substr($contents, -2); //Gets last 2 chars
		
		fclose($roundFile);	
		if($lastTwo[$playerpos-1] == 'n'){
				//Minus one because index[0] = player 1
			return true;
		}else{
			return false;
		}
	}
	
	function fileExists($file){
		$file = "/games/".$file.".txt";
		return file_exists($file);
	}
?>