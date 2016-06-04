<?php
if(basename($_SERVER["PHP_SELF"]) == "gmlog.php") {
	die("403 - Access Forbidden");
}
try {
	$query = $mysqli->query("SELECT characters.id AS cid, characters.name AS cname, gmlog.cid AS cid, gmlog.command AS command, gmlog.when AS 'when' FROM characters,gmlog WHERE characters.id = gmlog.cid ORDER BY gmlog.when DESC LIMIT 200");
	if (!$query)
		throw new Exception($mysqli->error);

	echo "
		<h2 class=\"text-left\">GM Logs</h2>
		<hr/>
		<table class=\"table table-bordered table-hover table-striped\">
		<thead>
			<tr>
				<th>Character</th>
				<th>Command</th>
				<th>Timestamp</th>
			</tr>
		</thead>
		<tbody>
	";
	$commands = array();
	while($row = $query->fetch_assoc()) {
		if(!array_key_exists('cname', $row) || $row['cname'] == "") {$row['cname'] = "Unknown";}
		if(!array_key_exists('command', $row) || $row['command'] == "") {$row['command'] = "Unknown";}
		if(!array_key_exists('when', $row) || $row['when'] == "") {$row['when'] = "Unknown";}
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
		echo "<tr class=".$jail_class."".$warp_class."".$dc_class."><td>";
		echo $row['cname'];
		echo "</td><td>";
		echo $row['command'];
		echo "</td><td>";
		echo $row['when'];
		echo "</td></tr>";
	}
	echo "</tbody></table>";
}
catch (Exception $e) {
	echo "
		h2 class=\"text-left\">GM Logs</h2><hr/>
		<div class=\"alert alert-danger\">Could not look up GM logs.</div>
	";
}