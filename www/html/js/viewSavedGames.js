function loadTable(){var a=getGames().split(",");for(var i=0;i<a.length-1;i++){var b=a[i].split(":");var c=document.getElementById("table");var d=c.insertRow(1);var e="/play.php?id="+b[0]+"&userid="+b[1];var f=d.insertCell(0);var g=d.insertCell(1);f.innerHTML=b[0];g.innerHTML="<a href='"+e+"'>"+"http://myrps.info"+e+"</a>"}}$(document).ready(function(){loadTable()});