<?php 
if($_SESSION['id']){
	if($_SESSION['admin']){
		if(isset($_GET['name'])){
			$name = $mysqli->real_escape_string($_GET['name']);
			$ga = $mysqli->query("SELECT * FROM `accounts` WHERE `id` LIKE '%".getInfo('accid', $name, 'profilename')."%'") or die();
			$a = $ga->fetch_assoc();
			echo "
				<h2 class=\"text-left\">Mute User - ".$name."</h2><hr/>
		";
			if(!isset($_POST['mute'])){
				echo "
		
				<div class=\"alert alert-info\"><a href=\"?base=admin&page=muteuser\">Search for another user &raquo;</a></div>
				<form method=\"post\" action=''>
				<b>Username:</b> ".$a['name']."<br/>
				<b>Profile name:</b> ".$name."
				<hr/>
				<input type=\"submit\" name=\"mute\" value=\"Mute &raquo;\" class=\"btn btn-warning\"/>
						";
			}else{
				if($a['gm']=="1"){
					echo "<div class=\"alert alert-error\"><b>Error!</b> User is a GM, and was <b>not</b> muted.</div>
				<a href=\"?base=admin&page=muteuser&amp;name=".$_GET['name']."\">Back</a>
		";
				}else{
					$u = $mysqli->query("UPDATE `accounts` SET `mute`='1' WHERE `id`='".getInfo('accid', $name, 'profilename')."'") or die();
					echo "
				<div class=\"alert alert-success\"><b>Success!</b> ".$name." has been muted.</div>
				<a href=\"?base=admin&page=muteuser\">Mute another user</a>
			";
				}
			}
		}else{
			echo "
			<h2 class=\"text-left\">Mute User</h2><hr/>
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
					$ga = $mysqli->query("SELECT * FROM ".$prefix."profile WHERE `name` LIKE '%".$search."%' ORDER BY `name` ASC") or die();
					while($a = $ga->fetch_assoc()){
						echo "
		
				<a href=\"?base=admin&amp;page=muteuser&amp;name=".$a['name']."\">".$a['name']."</a><br />
			";
					}
				}
			}
	echo "
	<br/><br/>
	<form method=\"post\" action=''>
		<input type=\"text\" name=\"name\" placeholder=\"Username\" required class=\"form-control\"\"/> 
		<br/><input type=\"submit\" name=\"search\" class=\"btn btn-primary\" value=\"Search &raquo;\" />
	</form>
	";
		}
	}else{
		redirect("?base");
	}
}else{
	redirect("?base");
}
?>