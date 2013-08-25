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

if($_SESSION['id']){
	if($_SESSION['admin']){
		if(isset($_GET['name'])){
			$name = $mysqli->real_escape_string($_GET['name']);
			$ga = $mysqli->query("SELECT * FROM `accounts` WHERE `id` LIKE '%".getInfo('accid', $name, 'profilename')."%'") or die();
			$a = $ga->fetch_assoc();
			echo "
			<legend>Unmute A User From Posting - ".$name."</legend>
		";
			if(!$_POST['unmute']){
				echo "
				<div class=\"alert alert-info\"><a href=\"?cype=admin&page=unmuteuser\">Search for another user &raquo;</a></div>
				<form method=\"post\" action=''>
				<b>Username:</b> ".$a['name']."<br/>
				<b>Profile name:</b> ".$name."
				<hr/>
				<input type=\"submit\" name=\"unmute\" value=\"Unmute &raquo;\" class=\"btn btn-warning\"/>
				";
			}else{
				$u = $mysqli->query("UPDATE `accounts` SET `mute`='0' WHERE `id`='".getInfo('accid', $name, 'profilename')."'") or die();
				echo "
				<div class=\"alert alert-success\"><b>Success!</b> ".$name." has been unmuted.</div>
				<div class=\"alert alert-info\"><a href=\"?cype=admin&page=unmuteuser\">Unmute another user &raquo;</a></div>
			";
				
			}
		}else{
			echo "
			<legend>Unmute User</legend>";
			$gm = $mysqli->query("SELECT * FROM `accounts` WHERE `mute`='1' ORDER BY `name` ASC") or die();
			$countmuted = $gm->num_rows;
			if($countmuted < 1){
				echo "<div class=\"alert alert-info\"><a href=\"?cype=admin&page=muteuser\">No users muted! Mute user &raquo;</a></div>";
			}
			else {
				echo "Select User:<br/>";
				while($m = $gm->fetch_assoc()){
					$gp = $mysqli->query("SELECT * FROM `cype_profile` WHERE `accountid`='".$m['id']."'") or die();
					$p = $gp->fetch_assoc();
						echo "<a href=\"?cype=admin&amp;page=unmuteuser&amp;name=".$p['name']."\">".$p['name']."</a>";
				}
			}
		}
	}else{
		include('sources/public/accessdenied.php');
	}
}else{
	echo "You must log in to use this feature.";
}
?>