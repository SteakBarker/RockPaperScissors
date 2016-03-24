<!DOCTYPE HTML>

<html>
<head>

</head>
<body>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<div class="container">
		<h1> Open Games </h1>
		
		<table class="table" style="width:100%" border="1">
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
						echo "<td> <a href='$join_url'>" . $join_url . '</a></td>';
						
						echo '</tr>';
					}
					mysqli_free_result($result);
				}
				$dbc->close();
			?>
		</table>
	</div>
</meta>

</body>
</html>