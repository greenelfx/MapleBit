<?php 
if($_SESSION['admin']){
	$result = $mysqli->query("SELECT name, banreason, ip FROM accounts WHERE banned = 1") or die();
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
	<tbody>";
	while($row = $result->fetch_assoc()){
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
	echo "</tbody>
	</table>
";
}
?>