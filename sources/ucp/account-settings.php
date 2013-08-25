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
	echo "
		<legend>
			Account Settings
		</legend>";
	if(!isset($_POST['modify'])){
		$query = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$_SESSION['id']."'") or die(mysql_error());
		$row = $query->fetch_assoc();
		echo "
		<div class=\"well\">If you want to keep your password, leave the field blank!</div>
		<form method=\"post\" action='' role=\"form\">
			<b><abbr title=\"You can't change this!\">Username</abbr></b>
				".$row['name']."
		<div class=\"form-group\">
			<label for=\"cPassword\">Current Password</label>
			<input type=\"password\" class=\"form-control\" id=\"cPassword\" placeholder=\"Current Password\" maxlength=\"12\" name=\"current\" />
		</div>
		<div class=\"form-group\">
			<label for=\"nPassword\">New Password</label>
			<input type=\"password\" class=\"form-control\" id=\"nPassword\" placeholder=\"New Password\" maxlength=\"12\" name=\"password\" />
		</div>
		<div class=\"form-group\">
			<label for=\"coPassword\">Confirm Password</label>
			<input type=\"password\" class=\"form-control\" id=\"coPassword\" placeholder=\"Confirm Password\" maxlength=\"12\" name=\"copassword\" />
		</div>
		<div class=\"form-group\">
			<label for=\"Email\">Email</label>
			<input type=\"email\" class=\"form-control\" id=\"Email\" placeholder=\"email@dot.com\" maxlength=\"12\" name=\"email\" value=\"".$row['email']."\" />
		</div>
		<div class=\"form-group\">
			<label for=\"Birthday\">Birthday</label>
			<input type=\"text\" class=\"form-control\" id=\"Birthday\" placeholder=\"1990-01-01\" name=\"birth\" value=\"".$row['birthday']."\" />
		</div>
			<input type=\"submit\" name=\"modify\" class=\"btn btn-primary btn-lg\" value=\"Modify &raquo;\" />
		</form>";

	}else{
		$u = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$_SESSION['id']."'") or die(mysql_error());
		$userz = $u->fetch_assoc();
		$current = sql_sanitize($_POST['current']);
		$pass = sql_sanitize($_POST['password']);
		$cpass = sql_sanitize($_POST['copassword']);
		$email = sql_sanitize($_POST['email']);
		$birth = sql_sanitize($_POST['birth']);
		
		if($current){
			if($userz['password'] == hash('sha512',$current.$userz['salt']) || sha1($current) == $userz['password']){
				if($pass != $cpass){
					echo "Passwords do not match.";
				}else{
					if(strlen($pass) < 6){
						echo "Your password must be between 6 and 12 characters.";
					}elseif(strlen($pass) > 12){
						echo "Your password must be between 6 and 12 characters.";
					}else{
						$u = $mysqli->query("UPDATE `accounts` SET `password`='".sha1($pass)."',`salt`=NULL WHERE `name`='".$userz['name']."'") or die(mysql_error());
						echo "Your changes have successfully been saved.";
					}
				}
			}else{
				echo "The password you have entered is incorrect.";
			}
		}elseif($email == ""){
			echo "Please supply an email address.";
		}else{
			$u = $mysqli->query("UPDATE `accounts` SET `email`='".$email."',`birthday`='".$birth."' WHERE `name`='".$userz['name']."'") or die(mysql_error());
			echo "Your changes have successfully been saved.";
		}
	};
}else{
	include('sources/public/login.php');
}
?>
