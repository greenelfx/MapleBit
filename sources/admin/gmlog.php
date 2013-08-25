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

if($_SESSION['gm'] || $_SESSION['admin']){
	$order = $mysqli->real_escape_string($_POST['order']);
	$search = $mysqli->real_escape_string($_POST['search']);
	$page = $mysqli->real_escape_string($_POST['page']);
	$limit = $page*15;
	$limit2 = $limit+15;
	$rank = $page*15;
	$rank2 = 0;
	$orderby = "cid DESC";

	echo "
		<legend>GM Log</legend>
			<form method='POST' action='?cype=admin&amp;page=gmlog' class=\"pull-right\">
			<div class=\"input-append\">
				<input type='text' name='search' value='$search' id='appendedInputButton' required/>
				<input type='submit' value='Search' id='go' alt='Search' title='Search' class='btn'/>
			</div>
			</form>
		<table class=\"table table-bordered table-hover table-striped\">
		<thead>
			<tr>
				<th>
					Name
				</td>
				<th>
					Command
				</td>
				<th>
					Time
				</td>
			</tr></thead>";
if($search == ""){
	$limit = $limit+1;
	$log = $mysqli->query("SELECT * FROM gmlog WHERE command LIKE '!' order by $orderby LIMIT $limit, 15");
	}else{
	$log = $mysqli->query("SELECT * FROM gmlog WHERE command LIKE '%$search%' AND command LIKE '!' order by $orderby LIMIT $limit,15");
	}
while($player = $log->fetch_assoc()){
	echo "
			<tr>
				<td>
					".$player['charname']."
				</td>
				<td>
					".$player['command']."
				</td>
				<td align='center'>
					".$player['when']."
				</td>
			</tr>";
}
	echo "
		</table>
		";
}
?>