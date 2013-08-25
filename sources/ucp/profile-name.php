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
	if($_SESSION['pname'] == NULL){
		echo "
		<legend>Set a Profile Name</legend>
		Once you've created a profile, other people can view your biography, character, and so on. Note that none of your private information will be shown.<br />
		Please pick a name <i>other</i> than your LoginID!<br/><br/>
		
		<b>Steps:</b><br />
		<b>1.</b> Insert your desired profile name and click submit.<br />
		<b>2.</b> If the name is taken, you will be notified. If not, your profile will be created.<br />
		<b>3.</b> Afterwards you can go to the community menu and change your profile informations.<br /><br />

		<form method=\"post\" action=''>
			<input type=\"text\" name=\"name\" placeholder=\"Profile Name\" required><br/><input type=\"submit\" name=\"create\" class=\"btn\" value=\"Submit\" />
		</form>";

		if($_POST['create']){
			$name = $mysqli->real_escape_string($_POST['name']);
			$pcheck = $mysqli->query("SELECT * FROM `cype_profile` WHERE `name`='".$name."'") or die();
			if($countpcheck = $pcheck->num_rows >= 1){
				echo "<div class=\"alert alert-error\">The profile name entered is already in use. Please select another one.</div>";
			}elseif($name == ""){
				echo "<div class=\"alert\">Please enter a profile name.</div>";
			}elseif(strlen($name) > 16){
				echo "<div class=\"alert\">The profile name must be between 4 and 16 characters.</div>";
			}elseif(strlen($name) < 4){
				echo "<div class=\"alert\">The profile name must be between 4 and 16 characters.</div>";
			}elseif(ereg('[^A-Za-z0-9]', $name)) {
				echo "<div class=\"alert alert-error\">Special characters are not allowed.</div>";
			}else{
				$i = $mysqli->query("INSERT INTO `cype_profile` (`accountid`,`name`) VALUES ('".$_SESSION['id']."','".$name."')") or die();
				echo "<div class=\"alert alert-success\">The profile name has been created! You can now go to the community page and edit your public profile.</div>";
				$_SESSION['pname'] = $name;
			}
		};
	}else{
		echo "<div class=\"alert alert-error\"><h4>Oops!</h4>Looks like you already have a profile name!</div>";
	}
}
?>