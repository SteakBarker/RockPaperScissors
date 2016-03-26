function addGameToCookie(game_id, user_id){
	
	var games = getGames();
	var string = games+game_id+":"+user_id+",";
	
	createCookie('games', string);
}

function getGames(){
	var string = decodeURIComponent(getCookie('games'));
	if(!string){
		return "";
	}else{
		return string;
	}
}

var createCookie = function(name, value) {
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) +"; path=/";
}
function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
}