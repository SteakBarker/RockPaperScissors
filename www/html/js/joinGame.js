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
	
		$('#p_link').text(personal_link);
	});
}
	
function sendAjax(handleData) {
	$.ajax({
		url:"join.php",
		type:'POST',
		data:
		{
			game_id:document.getElementById("game_id").value,
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