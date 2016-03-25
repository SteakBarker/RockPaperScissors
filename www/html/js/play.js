function getData(){
	sendAjax(function(output){
		var results = JSON.parse(output);
			//First we get the return, and turn it into an array
		
		var past_games = results["history"];
		var playerPos = results["ppos"];
		var canPlay = results["canPlay"];
		var p1Name = results["p1Name"];
		var p2Name = results["p2Name"];
			
		if(canPlay){
			$("#moves").show();
			
			//If we are player one, then we know the last move we made is the last ODD number from past_games
			//If we are player two, we know the last move we made is the last even char from past_games.
			//we can use that here. Also, its length-2 because ABC.length=3, yet index[3] is an error
			if(playerPos == 1){
				document.getElementById("result").innerHTML = didWin([past_games.charAt(past_games.length-2),past_games.charAt(past_games.length-1)]).toUpperCase();
			}else{
				document.getElementById("result").innerHTML = didWin([past_games.charAt(past_games.length-1),past_games.charAt(past_games.length-2)]).toUpperCase();
			}
			
		}else{
			$("#moves").hide();
			
			document.getElementById("result").innerHTML = "PENDING";
		}
		
		
			//Now we overwrite the table. We are going to be overwriting it from scratch
		document.getElementById("table").innerHTML = "<tr><td>Your Move</td><td>Their Move</td><td>Did you win?</td></tr>";
		
			//Okay. Lets start overwriting the table
		for (var i = 0; i < past_games.length-1; i+=2){
				//We increment by two since there 2 char per round. Example round: "rs"
			
			var yourMove;
			var theirMove;
			
			if(playerPos == 1){
				yourMove=past_games[i]; //Right, if we are player one, we know that past_games[i] is going to be our move since all
									//all odd (and 0) indexs will be ours
				theirMove=past_games[i+1]; //Right, now we get the next char, which will always be even since we increment by two
				document.getElementById("yourName").innerHTML = p1Name;
				document.getElementById("theirName").innerHTML = p2Name;
			}else{
				yourMove=past_games[i+1]; //Same idea here. We are player two, so we know our move is going to be an even index
				theirMove=past_games[i]; //And their index must be the odd one
				document.getElementById("yourName").innerHTML = p2Name;
				document.getElementById("theirName").innerHTML = p1Name;
			}
			var table = document.getElementById("table");
			var row = table.insertRow(1);
			
			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);
			
			cell1.innerHTML = yourMove;
			cell2.innerHTML = didWin([yourMove,theirMove]);
			cell3.innerHTML = theirMove;
			
		}
	});
}
		
function sendAjax(handleData) {
	$.ajax({
		url:"gameData.php",
		type:'POST',
		data:
		{
			id:id,
			userid:p_id,
		},
			success:function(data) {
			handleData(data);
			$('#message').text(output);
		}
	});
}

function play(move){
	$.ajax({
		url:"makeMove.php",
		type:'POST',
		data:
		{
			id:id,
			userid:p_id,
			move:move,
		},
		success:function(data) {
			getData();
		}
	});
}
	
function didWin(moves){
	//This might be confusing. Its best to think about RPS as a circle
	//Rock -> Paper -> Scissors
	//Rock is beaten by paper is beaten by scissors.
	
	//Moves[0] = player 1, [1] = player 2
	
	//Now we want to turn those chars into something we can understand: numbers
	for(var i = 0; i < 2; i++){
		switch(moves[i]){
			case "r":
				moves[i]=0;
				break;
			case "p":
				moves[i]=1;
				break;
			case "s":
				moves[i]=2;
				break;
		}
	}
	
	if(moves[0]===moves[1]){
		//If the numbers are the same, it's a tie
		return "tie";
	}else if(moves[0]+1 === moves[1] || (moves[0]==2 && moves[1]==0)){
		//Our move+1 will give us the move that beats us. Think about it as a cirlce!
		//So, if our move+1 is the same as the others move, they played the thing that beat us.
		//There is an exception, or course. Scissors(2)+1 is 3, but the next one in the cirlce is rock, which is 0
		//Because of that we need to see if our move is scissors, and their move is rock
		return "lost";
	}else{
		return "won";
	}
}

function goHome(){
	window.location = "index.html";
}

$( document ).ready(function() {
	getData();
});