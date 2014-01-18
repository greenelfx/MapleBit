<?php
	include_once('assets/config/database.php');
	$admin = $mysqli->real_escape_string($_POST['admin_id']);
	if($admin == 1) {
		$getpost = $mysqli->real_escape_string($_POST['content']);
		$mysqli->query("UPDATE bit_properties SET homecontent = '$getpost'");
	}
?>
