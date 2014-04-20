<?php
if(basename($_SERVER["PHP_SELF"]) == "guildlist.php"){
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
		<td><?php echo ++$n;?></td>
		<td><?php echo $row['name'];?></td>
		<td><?php echo $row['cname'];?></td>
		<td><?php echo $row['GP'];?></td>
	</tr>
<?php  } ?>
</tbody>
</table>