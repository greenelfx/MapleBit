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

$name = sql_sanitize($_GET['name']);
$check = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".getInfo('accid', $name, 'profilename')."'") or die();
$real = $check->num_rows;;
if($real > 0){
	$name = $_GET['name'];
} else {
	if($_SESSION){
		$name = $_SESSION['name'];
	} else{
		$name = "";
		echo "<meta http-equiv=refresh content=\"0; url=?cype=main\">";
		exit();
	}
}

if(@$_GET['name']){
	// Display profile
	if(@$_GET['p']==""){
		$ga = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".getInfo('accid', $name, 'profilename')."'") or die();
		$a = $ga->fetch_assoc();
		if($a['loggedin'] == "0"){
			$status = "<img src='assets/img/offline.png' alt='Offline' />";
		}else{
			$status = "<img src='assets/img/online.png' alt='Offline' />";
		}
		$gp = $mysqli->query("SELECT * FROM `cype_profile` WHERE `name`='".$name."'") or die();
		$p = $gp->fetch_assoc();
		$mc = $p['mainchar'];
		$gmc = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$mc."'") or die();
		$m = $gmc->fetch_assoc();
		if($m['name'] == "") {
			$m['name'] = "Not set";
			$stats = "";
		}
		else {$stats = "View Stats";}
		if(empty($p['realname'])){$p['realname'] = "Not Set";}
		if(empty($p['country'])){$p['country'] = "Not Set";}
		if(empty($p['motto'])){$p['motto'] = "Not Set";}
		if(empty($p['age'])){$p['age'] = "Not Set";}
		if(empty($p['job'])){$p['job'] = "Not Set";}
		if(empty($p['text'])){$p['text'] = "Not Set";}
		echo "
			<legend>".$name."'s Profile (".$p['realname'].")</legend>
			Game :".$status."<br/>
			Site :".onlineCheck(getInfo('accid', $name, 'profilename'))."<br/><br/>
			<b>Main Character:</b> ".$m['name']. "&nbsp;" .$stats ."<br/><br/>
			<b>Motto:</b> ".$p['motto']."<br/><br/>
			<b>Age:</b> ".$p['age']."<br/><br/>
			<b>Country: </b>".$p['country']."<br/><br/>
			<b>Favorite Job: </b>".$p['favjob']."<br/><hr/>
			";
		echo "	
			<b>About Me:</b>
			".nl2br(stripslashes($p['text']))."<br/>
			<hr/>
			<a href=\"?cype=ucp&amp;page=mail&amp;uc=$name\">Send me Mail!</a>
			";
}
}elseif(@$_GET['action']=="search"){
	if($_POST['search']){
		$name = $mysqli->real_escape_string($_POST['name']);
		$gs = $mysqli->query("SELECT * FROM `cype_profile` WHERE `name` LIKE '%".$name."%' ORDER BY `name` ASC") or die();
		echo "
	<fieldset>
		<legend>
			<b>Search result:</b>
		</legend>";
		while($s = $gs->fetch_assoc()){
			echo "
			<a href=\"?cype=main&amp;page=members&amp;name=".$s['name']."\">".$s['name']."</a><br />";
		}
		echo "
	</fieldset>";
	}
}else{
	echo "
	<fieldset>
		<legend>
			<b>Members List</b>
		</legend>";
	echo "
		Here's the full list of the members of the <b>".$servername."</b> community. 
		You can select one to visit their profile or you can search for an user.<hr />
		<center>
			<form method=\"post\" action=\"?cype=main&amp;page=members&amp;action=search\">
				<input type=\"text\" name=\"name\" placeholder=\"Profile Name\" required/> 
				<input type=\"submit\" name=\"search\" value=\"Search\" class=\"btn btn-primary\" style=\"margin-top:-10px;\"/>
			</form>
		</center>
	";
	$gp = $mysqli->query("SELECT * FROM `cype_profile` WHERE `name` != 'NULL' ORDER BY `name` ASC") or die();
	echo "
		<table border=\"0\">";
	while($p = $gp->fetch_assoc()){
		echo "
			<tr>
				<td>".onlineCheck($p['accountid'])."</td>
				<td>
					<a href=\"?cype=main&amp;page=members&amp;name=".$p['name']."\">".$p['name']."</a>
				</td>
			</tr>";
	}
	echo "
		</table>
	</fieldset>
	";
}
?>