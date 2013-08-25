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
error_reporting(0);
if(isset($_SESSION['id'])){
		echo "
	<h3>Control Panel</h3>
	<ul class=\"unstyled\">
	<li><a href=\"?cype=ucp\">Control Panel</a></li>
	";
	if(isset($_SESSION['admin'])){
		echo "
		<li><a href=\"?cype=admin\">Admin Panel</a></li>
		";
	}
	if(isset($_SESSION['gm'])){
		echo "
		<li><a href=\"?cype=gmcp\">GM Panel</a></li>
		";
	}
	if(@$_SESSION['pname'] == NULL){
		echo "
		<li><a href=\"?cype=ucp&amp;page=profname\">Set Profile Name</a></li>
		";
	}else{
		echo "
		<li><a href=\"?cype=main&amp;page=members&amp;name=".$_SESSION['pname']."\">Your Profile</a></li>
		";
	}
	echo "
		<li><a href=\"?cype=main&amp;page=members\">Members List</a></li>
		<li><a href=\"?cype=misc&amp;script=logout\">Log Out</a></li>
		";
		} else {
	if(isset($_POST['login'])) {
		$u = sql_sanitize($_POST['username']);
		$p = sql_sanitize($_POST['password']);
		$s = $mysqli->query("SELECT * FROM `accounts` WHERE `name`='".$u."'") or die(mysql_error());
		$i = $s->fetch_assoc();
		
		if($i['password'] == hash('sha512',$p.$i['salt']) || sha1($p) == $i['password']){
			$userz = $mysqli->query("SELECT * FROM `accounts` WHERE `name`='".$i['name']."' AND `password`='".$i['password']."'") or die(mysql_error());
			$auser = $userz->fetch_assoc();
			$_SESSION['id'] = $auser['id'];
			$_SESSION['name'] = $auser['name'];
			$_SESSION['mute'] = $auser['mute'];
			if($auser['webadmin'] == "1"){
				$_SESSION['admin'] = $auser['webadmin'];
			}
			if($auser['gm'] == $gmlevel){
				$_SESSION['gm'] = $auser['gm'];
			}
			$name = $mysqli->query("SELECT * FROM `cype_profile` WHERE `accountid`='".$auser['id']."'") or die(mysql_error());
			$pname = $name->fetch_assoc();
			if($pname['name'] == NULL){
				$_SESSION['pname'] = NULL;
			}else{
				$_SESSION['pname'] = $pname['name'];
			}
			$return = "<script> location.reload();</script>";
		} else {
			$return = "<br/><div class=\"alert alert-error\">Invalid username or password.</div>";
		}
	}
		echo "
			<h3>Login Panel</h3>
			<form method=\"post\" style=\"text-align:center;\">
				<input type=\"text\" name=\"username\" maxlength=\"12\" style=\"width:90%;\" placeholder=\"Username\" required/>
				<input type=\"password\" name=\"password\" maxlength=\"12\" style=\"width:90%;\" placeholder=\"Password\" required/>
		<br/>
		<span style=\"margin:0;padding:0;display:inline-block;\">
			<input type=\"submit\" class=\"btn\" name=\"login\" value=\"Login\"/>
			<input type=\"button\" class=\"btn btn-info\" value=\"Register\" onclick=\"location.href='?cype=main&amp;page=register';\"/>
		</span>
			".$return."
			</form>
		<br />";
}
?>