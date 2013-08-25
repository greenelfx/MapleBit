<?php 
/*
    Copyright (C) 2009  Murad <Murawd>
						Josh L. <Josho192837>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
?>
<table class="table table-bordered table-hover">
<thead>
	<tr>
		<th>Rank</th>
		<th>Name</th>
		<th>Leader</th>
		<th>Guild Points</th>
	</tr>
</thead>
<?php 
$n = 0;
$query = $mysqli->query("SELECT guilds.leader, guilds.GP, guilds.name, characters.id, characters.name AS cname FROM guilds, characters WHERE characters.id = guilds.leader ORDER BY guilds.GP DESC LIMIT 10");
while ($gg = $query->fetch_assoc()) {
?>
	<tr>
		<td><?php echo ++$n;?></td>
		<td><?php echo $gg['name'];?></td>
		<td><?php echo $gg['cname'];?></td>
		<td><?php echo $gg['GP'];?></td>
	</tr>
<?php  } ?>
</table>