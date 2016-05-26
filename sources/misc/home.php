<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$is_ajax = $_REQUEST['is_ajax'];
	if(isset($is_ajax) && $is_ajax) {
		include_once('assets/config/database.php');
		if($_SESSION['admin'] == 1) {
			$getpost = $mysqli->real_escape_string($_REQUEST['content']);
			$mysqli->query("UPDATE ".$prefix."properties SET homecontent = '$getpost'");
		}
	}
}