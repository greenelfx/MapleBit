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
if($_SESSION['admin']){
$result = $mysqli->query("SELECT name, banreason FROM accounts WHERE banned = 1")
or die();
	echo "
		<legend>Banned</legend>
			<table class=\"table table-bordered table-hover table-striped\">
			<thead>
				<tr>
					<th>Account ID</th>
					<th>Reason</th>
				</tr>
			</thead>";
while($row = $result->fetch_assoc()){
	echo "
				<tr>
					<td>
						".$row['name']."
					</td>
					<td>
						".$row['banreason']."
					</td>
				</tr>";
}
echo "
	</table>
	";
}
?>