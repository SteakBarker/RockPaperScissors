function loadTable(){
	
	var games = getGames().split(",");
	
	for (var i = 0; i < games.length-1; i++){
			//We increment by two since there 2 char per round. Example round: "rs"
			
		var data = games[i].split(":");
		
		
		var table = document.getElementById("table");
		var row = table.insertRow(1);
		
		var personal_link = "/play.php?id="+data[0]+"&userid="+data[1];
		
		var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(1);
		
		cell1.innerHTML = data[0];
		cell2.innerHTML = "<a href='"+personal_link+"'>" +"http://myrps.info"+ personal_link + "</a>";
	}
}

$( document ).ready(function() {
	loadTable();
});