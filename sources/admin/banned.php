<?php
if(basename($_SERVER["PHP_SELF"]) == "banned.php") {
	die("403 - Access Forbidden");
}

try {
	$result = $mysqli->query("SELECT name, banreason, ip FROM accounts WHERE banned >= 1");
	if (!$result)
		throw new Exception($mysqli->error);

	echo "
		<h2 class=\"text-left\">Banned Members</h2>
		<hr/>
		<table class=\"table table-bordered table-hover table-striped\">
			<thead>
				<tr>
					<th>Account ID</th>
					<th>Reason</th>
					<th>IP</th>
				</tr>
			</thead>
		<tbody>
	";
	while($row = $result->fetch_assoc()) {
		if(!array_key_exists('banreason', $row) || $row['banreason'] == "") {$row['banreason'] = "Unknown";}
		if(!array_key_exists('ip', $row) || $row['ip'] == "") {$row['ip'] = "Unknown";}
		echo "
			<tr>
				<td>
					".$row['name']."
				</td>
				<td>
					".$row['banreason']."
				</td>
				<td>
					".$row['ip']."
				</td>
			</tr>";
	}
	echo "
		</tbody>
		</table>
	";
}
catch (Exception $e) {
	echo "<h2 class=\"text-left\">Banned Members</h2><hr/>
	<div class=\"alert alert-danger\">Could not look up banned records.</div>";
}