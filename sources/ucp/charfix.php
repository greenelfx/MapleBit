<?php
if(basename($_SERVER["PHP_SELF"]) == "charfix.php") {
	die("403 - Access Forbidden");
}
if(isset($_GET['fix']) && $_GET['fix'] === "unstuck") {
	echo "<h2 class=\"text-left\">Move Character</h2><hr/>";
	if(!isset($_POST['unstuck'])) {
		$seekmaps = $mysqli->query("SELECT jailmaps FROM ".$prefix."properties");
		$getmaps = $seekmaps->fetch_assoc(); // Get array
		if(empty($getmaps['jailmaps'])) {
			$avail_chars = $mysqli->query("SELECT * FROM characters WHERE accountid='".$_SESSION['id']."' ORDER BY id ASC") or die();
		}
		else {
			$avail_chars = $mysqli->query("SELECT * FROM characters WHERE accountid='".$_SESSION['id']."' AND map NOT IN (".$getmaps['jailmaps'].") ORDER BY id ASC") or die();
		}
		$count_avail_chars = $avail_chars->num_rows;
		if($count_avail_chars >= 1) {
			echo "
				Is your character stuck at a bugged map, and everytime you login you get kicked back to your desktop?<br />
				Fill out this form below, and your character will be warped to Henesys, and you should be able to log back in!
				<hr/>
				<form method=\"post\">
					<b>Select character:</b><br/>
					<select name=\"char\" class=\"form-control\">
			";
			while($c = $avail_chars->fetch_assoc()) {
				echo "<option value=\"".$c['id']."\">".$c['name']."</option>";
			}
			echo "
				</select><br/>
				<b>Spawn point:</b><br/>
				<select name=\"map\" class=\"form-control\">
					<option value=\"100000000\">Henesys</option>
				</select><br/>
				<input type=\"submit\" name=\"unstuck\" value=\"Unstuck &raquo;\" class=\"btn btn-info\"/>
				</form><br/>
			";
		} else {
			echo "<div class=\"alert alert-danger\">You don't have any available characters!</div>";
		}
	}
	else {
		$name = $_SESSION['name'];
		$queryAccount = $mysqli->query("SELECT * FROM `accounts` WHERE `name`='".$name."'") or die();
		$getAccount = $queryAccount->fetch_assoc();
		$inputCharacter = $mysqli->real_escape_string($_POST['char']);
		$queryCharacter = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$inputCharacter."'") or die();
		$getCharacter = $queryCharacter->fetch_assoc();
		$moveMap = 100000000;

		if($getAccount['id'] == $getCharacter['accountid']) {
			$m = $mysqli->query("UPDATE characters SET map='".$moveMap."' WHERE id='".$inputCharacter."'") or die();
			echo "<div class=\"alert alert-success\"><b>Fix succesful.</b> Your character will now spawn at Henesys.</div>";
		}
		else {
			echo "<div class=\"alert alert-danger\"><b>Error.</b> Insufficient Permissions.</div>";
		}

	}
}
elseif(isset($_GET['fix']) && $_GET['fix'] == "dc") {
	$name = $_SESSION['name'];
	$acc = $mysqli->query("SELECT * FROM `accounts` WHERE `name`='".$name."'")->fetch_assoc();
	if($acc['loggedin']=="0") {
		$text = "<div class=\"alert alert-info\">You are already logged out in-game.</div>";
	}
	else {
		$s = $mysqli->query("UPDATE accounts SET loggedin='0' WHERE name='".$_SESSION['name']."'") or die();
		$text = "<div class=\"alert alert-success\">Your account has been fixed! You should be able to log in normally now.</div>";
	}
	echo "<h2 class=\"text-left\">Disconnect your Account</h2><hr/>";
	echo $text;
}
else {
	echo "
		<h2 class=\"text-left\">Character Fixes</h2><hr/>
		<a href=\"?base=ucp&amp;page=charfix&amp;fix=unstuck\">Move Character &raquo;</a><br/>
		<a href=\"?base=ucp&amp;page=charfix&amp;fix=dc\">Unstuck Account &raquo;</a><br/>
	";
}