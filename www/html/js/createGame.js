function createGame(){
	sendAjax(function(output){
		var results = JSON.parse(output);
		
		$("#loading").hide();
		
		if(results["success"] == 0 ){
			alert(results["error"]);
			return;
		}
		
		
		var game_code = results["game_id"];
		var user_id = results["userid"];
		
		$("#result").show();
		$("#input").hide();
		
		var personal_link = "/play.php?id="+game_code+"&userid="+user_id;
		var join_link = "/joinGame.php?id="+game_code;
		
		$("#p_link").prop("href", personal_link);
		$("#j_link").prop("href", join_link);
	
		$('#game_code').text("Game Code: ".concat(game_code));
		$('#p_link').text(personal_link);
		$('#j_link').text(join_link);
		
		addGameToCookie(game_code, user_id);
	});
}
	
function sendAjax(handleData) {
	$.ajax({
		url:"createGame.php",
		type:'POST',
		data:
		{
			name:document.getElementById("name").value,
		},beforeSend: function() {
			$("#loading").show();
		},success:function(data) {
			handleData(data); 
		},error: function(){
			$("#loading").hide();
			alert("Request Failed");
		},
		timeout: 5000
	});
}