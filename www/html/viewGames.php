<!DOCTYPE HTML>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/defaultStyle.css">
	<link rel="stylesheet" type="text/css" href="css/tableStyle.css">
</head>
<body>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<div class="menu">
		<input type="submit" name="submit" value = "Home" onclick="window.location='index.html'"/>
	</div>
	
	<div class="container" style="max-width:900px;">
	
		<h1 class="title"> Open Games </h1>
		<hr>
		<div class="tableContainer">
			<table class="table">
				<tr>
					<td>Player</td>
					<td>Join URL</td>
				</tr>
				<?php
					require_once("../mysql_connect.php");
					$dbc = createDefaultConnection("games");
					
					$sql="SELECT id, p1_id FROM game where filled=0";
					
					if ($result=@mysqli_query($dbc,$sql)){
						//Gets one row
						require_once('../cleanData.php');
						while($row=mysqli_fetch_row($result)){
							
							$join_url = "/joinGame.php?id=".$row[0];
												//Even though I know for a fact the user could not have set their ID, I'd still like to clean it			
							$sql_name="SELECT name FROM player where id='".$row[1]."'";
							
							$p1_name = cleanData_Alphanumeric(mysqli_fetch_row(@mysqli_query($dbc,$sql_name))[0], 15);
							
							echo '<tr>';
							
							echo '<td>' . $p1_name . '</td>';
							echo "<td> <a href='$join_url'>" . "http://myrps.info". $join_url . '</a></td>';
							
							echo '</tr>';
						}
						mysqli_free_result($result);
					}
					$dbc->close();
				?>
			</table>
		</div>
	</div>
</meta>

</body>
</html>