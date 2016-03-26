function createGame(){
	sendAjax(function(output){
		var results = JSON.parse(output);
		
		$("#loading").hide();
		
		if(results["success"] == 0 ){
			alert(results["error"]);
			return;
		}
		
		
		var game_code = results["game_id"];
		var personal_link = results["p_link"];
		var join_link = results["j_link"];
		
		$("#result").show();
		$("#input").hide();
		
		$("#p_link").prop("href", personal_link);
		$("#j_link").prop("href", join_link);
	
		$('#game_code').text("Game Code: ".concat(game_code));
		$('#p_link').text(personal_link);
		$('#j_link').text(join_link);
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