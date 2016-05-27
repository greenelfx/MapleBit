<?php
if(basename($_SERVER["PHP_SELF"]) == "guildlist.php") {
	die("403 - Access Forbidden");
}
?>
<h2 class="text-left">Guild List</h2><hr/>
<table class="table table-bordered table-hover">
<thead>
	<tr>
		<th>Rank</th>
		<th>Name</th>
		<th>Leader</th>
		<th>Guild Points</th>
	</tr>
</thead>
<tbody>
<?php 
$n = 0;
$query = $mysqli->query("SELECT guilds.leader, guilds.GP, guilds.name, characters.id, characters.name AS cname FROM guilds, characters WHERE characters.id = guilds.leader ORDER BY guilds.GP DESC LIMIT 20");
while ($row = $query->fetch_assoc()) {
?>
	<tr>
		<td><?php echo ++$n; ?></td>
		<td><?php if(array_key_exists('name', $row)) { echo $row['name']; } else { echo "Unknown";} ?></td>
		<td><?php if(array_key_exists('cname', $row)) { echo $row['cname']; } else { echo "Unknown";} ?></td>
		<td><?php if(array_key_exists('GP', $row)) { echo $row['GP']; } else { echo "Unknown";} ?></td>
	</tr>
<?php
}
?>
</tbody>
</table>