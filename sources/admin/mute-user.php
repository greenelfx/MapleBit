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
				<legend>Mute User - ".$name."</legend>
		";
			if(!isset($_POST['mute'])){
				echo "
		
				<div class=\"alert alert-info\"><a href=\"?cype=admin&page=muteuser\">Search for another user &raquo;</a></div>
				<form method=\"post\" action=''>
				<b>Username:</b> ".$a['name']."<br/>
				<b>Profile name:</b> ".$name."
				<hr/>
				<input type=\"submit\" name=\"mute\" value=\"Mute &raquo;\" class=\"btn btn-warning\"/>
						";
			}else{
				if($a['gm']=="1"){
					echo "<div class=\"alert alert-error\"><b>Error!</b> User is a GM, and was <b>not</b> muted.</div>
				<a href=\"?cype=admin&page=muteuser&amp;name=".$_GET['name']."\">Back</a>
		";
				}else{
					$u = $mysqli->query("UPDATE `accounts` SET `mute`='1' WHERE `id`='".getInfo('accid', $name, 'profilename')."'") or die();
					echo "
				<div class=\"alert alert-success\"><b>Success!</b> ".$name." has been muted.</div>
				<a href=\"?cype=admin&page=muteuser\">Mute another user</a>
			";
				}
			}
		}else{
			echo "
			<legend>Mute User</legend>
		";
		if(!isset($_POST['search'])){
				echo "
		
				Search for the profile name you wish to mute
		";
			}else{
				$search = $mysqli->real_escape_string($_POST['name']);
				if($search == ""){
					echo "
				<div class=\"alert alert-error\">You cannot leave the search field blank.</div>
			";
				}else{
					$ga = $mysqli->query("SELECT * FROM `cype_profile` WHERE `name` LIKE '%".$search."%' ORDER BY `name` ASC") or die();
					while($a = $ga->fetch_assoc()){
						echo "
		
				<a href=\"?cype=admin&amp;page=muteuser&amp;name=".$a['name']."\">".$a['name']."</a><br />
			";
					}
				}
			}
	echo "
	<br/><br/>
	<form method=\"post\" action=''>
		<input type=\"text\" name=\"name\" placeholder=\"Username\" required class=\"form-control\" style=\"width:50%;\"/> 
		<br/><input type=\"submit\" name=\"search\" class=\"btn btn-primary\" value=\"Search &raquo;\" />
	</form>
	";
		}
	}else{
		include('sources/public/accessdenied.php');
	}
}else{
	echo "Please log in to use this feature.";
}
?>