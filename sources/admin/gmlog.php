<?php 
if($_SESSION['gm'] || $_SESSION['admin']){
	if(isset($_POST['search'])){$search = $mysqli->real_escape_string($_POST['search']);} else {$search = "";}
	$query = $mysqli->query("SELECT characters.id AS cid, characters.name AS cname, gmlog.cid AS cid, gmlog.command AS command, gmlog.when AS 'when' FROM characters,gmlog WHERE characters.id = gmlog.cid ORDER BY gmlog.when DESC LIMIT 200");
	echo "<table class=\"table table-hover\">";
	echo "<thead>
			<tr>
				<th>Character</th>
				<th>Command</th>
				<th>When</th>
			</tr>
		</thead>
	<tbody>";
	$commands = array();
	while($row = $query->fetch_assoc()) {
		$commands[] = $row['command'];
		$warp = '!warp';
		$kill = '!kill';
		$dc = '!dc';
	  if(strpos($row['command'], $warp) === false) {$warp_class = "";}
		else {$warp_class= "info";}
	  if(strpos($row['command'], $dc) === false){$dc_class = "";}
		else {$dc_class= "error";}
	  if(strpos($row['command'], $kill) === false){$jail_class = "";}
		else {$jail_class= "warning";}
		echo "<h5><tr class=".$jail_class."".$warp_class."".$dc_class."><td>&nbsp;&nbsp;";
		echo $row['cname'];
		echo "&nbsp;</td><td>&nbsp;";
		echo $row['command'];
		echo "&nbsp;</td><td>";
		echo $row['when'];
		echo "</td></tr></h5>";
	}
		echo "</tbody></table>";
} else{
	redirect("?base");
}
?>