<?php
	//This handles the round (or results) file.
	//A round file might look like this: rpprsn
	//Every odd index is player 1, every even is player 2
	
	//Rememer, a 'rounds' file is a file that stores the history and moves the players have made
	
	//Will return $numb amount of rounds from $file
	function getRounds($file, $numb){
		$file = "/games/".$file.".txt";
		
		if(!is_readable($file)){
			error_log("Could not find file ".$file);
			return null;
		}
		
			//Here we have a resouce for reading the file
		$roundFile = fopen($file, "r") or die("Unable to open file");
			//Now we read the file, and get its contents
		$filecontents = fread($roundFile,filesize($file));
			//What substr("test", -2) would do is return 'st', or the last 2 chars in the string.
		$results = substr($filecontents, -($numb*2)); //What this does is get the last numb*2 because every round has two moves; one for each player
		fclose($roundFile); //Now we close the file -- we no longer need it.
		
		return $results;
	}
	
	//This will replace the last odd char with player ones move, or the last even with player 2 move
	//If both players have made their move, it add another set of N rounds.
	//EXAMPLE: rrnn -> ("r", 1) -> rrrn
		//rrrn -> ("p",2) -> rrrpnn
	function addRound($file, $results, $playerpos){
		
			//There are only 2 players: 1, and 2.
		if(($playerpos!=1 && $playerpos!=2) || (preg_match("/([^rpsn]+)|.{2,}/", $results))){
			error_log("addRound recieved invalid data");
			exit("Error");
		}
		
		$file = "/games/".$file.".txt";
		
		if(!file_exists($file)){
			error_log("Could not find file ".$file);
			exit("Error");
		}
			//We create our file thingy
		$roundFile = fopen($file, "r") or die("Unable to open file");
		
			//File_con = file contents.
		$file_con = fread($roundFile, filesize($file));
		$lastTwo = substr($file_con, -2); //This gets the last two chars from the contents. 
			//[0] = player ones last move; [1] = player 2 last move
		$contents = substr($file_con, 0, -2);
			//And now, we remove the last two char because we want to overwrite them with the new ones
		
		$p1_move;
		$p2_move;
		
		if($playerpos==1){ //If you're player one, we know your move. Player 2s move should be he last one they made
			$p1_move = $results;
			$p2_move = $lastTwo[1];
		}else{
			$p1_move = $lastTwo[0];
			$p2_move = $results;
		}
		
		if(!$p1_move || !$p2_move){
			//For some reason, player one or two dont have a value. Lets set the emtpy ones to N
			error_log("p1_move or p2_move are not set! Setting them to N");
			if(!$p1_move){
				$p1_move = 'n';
			}
			if(!$p2_move){
				$p2_move = 'n';
			}
		}
		
		fclose($roundFile); //We can close the READER, then open up a WRITER.
				//WARNING: w+ does not work for some reason. I spent the last 2 hours trying to solve that problem
		$roundFile = fopen($file, "w") or die("Unable to open file");
		
		$contents = $contents.$p1_move.$p2_move;
			//Now we add the previous moves, and then the players new move;
				//example: file:rrnn -> (s, 1) -> rr + s + n
		
		if($p1_move != 'n' && $p2_move != 'n'){
				//If both players have made their move, lets add the next round (nn)
			fwrite($roundFile, $contents."nn");
		}else{
			fwrite($roundFile, $contents);
		}
		
		fclose($roundFile);
	}
	
		//This function does as it says: Creates a rounds file.
	function createRoundFile($file){
		$file = "/games/".$file.".txt";
			
			//This statement will create the file if it doesn't exists
		$roundFile = fopen($file, "w") or die("Unable to open file");
		fwrite($roundFile, "nn"); //Then we add our fist round 'nn'
		fclose($roundFile);	
		if(!file_exists($file)){
			//If for what ever reason the file doesn't exist, we should log it.
			//Really just left over from the 3 hours debugging session I had last night
			error_log("Create round was unable to create the file!");
		}
	}
	
		//Will return true if the player has not made their move, false if they have
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
				//playerpos -1 because [0] == player 1;
				//If the players move was eqal to N, then they have not made theri move.
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