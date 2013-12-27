<?php 
if($_SESSION['id']){
	if($_SESSION['admin']){
		if(isset($_GET['name'])){
			$name = $mysqli->real_escape_string($_GET['name']);
			$ga = $mysqli->query("SELECT * FROM `accounts` WHERE `id` LIKE '%".getInfo('accid', $name, 'profilename')."%'") or die();
			$a = $ga->fetch_assoc();
			echo "
			<h2 class=\"text-left\">Unmute A User From Posting - ".$name."</h2><hr/>
		";
			if(!isset($_POST['unmute'])){
				echo "
				<div class=\"alert alert-info\"><a href=\"?base=admin&page=unmuteuser\">Search for another user &raquo;</a></div>
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
				<div class=\"alert alert-info\"><a href=\"?base=admin&page=unmuteuser\">Unmute another user &raquo;</a></div>
			";
				
			}
		}else{
			echo "
			<h2 class=\"text-left\">Unmute User</h2><hr/>";
			$gm = $mysqli->query("SELECT * FROM `accounts` WHERE `mute`='1' ORDER BY `name` ASC") or die();
			$countmuted = $gm->num_rows;
			if($countmuted < 1){
				echo "<div class=\"alert alert-info\"><a href=\"?base=admin&page=muteuser\">No users muted! Mute user &raquo;</a></div>";
			}
			else {
				echo "Select User:<br/>";
				while($m = $gm->fetch_assoc()){
					$gp = $mysqli->query("SELECT * FROM ".$prefix."profile WHERE `accountid`='".$m['id']."'") or die();
					$p = $gp->fetch_assoc();
						echo "<a href=\"?base=admin&amp;page=unmuteuser&amp;name=".$p['name']."\">".$p['name']."</a>";
				}
			}
		}
	}else{
		redirect("?base");
	}
}else{
	redirect("?base");
}
?>