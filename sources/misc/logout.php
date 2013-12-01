<?php 
if($_SESSION['id']){
	$logouttime = 100;
	$date = date();
	$timenow = time();
	$loggedtime = $timenow - $logouttime;
	$query = $mysqli->query("UPDATE `accounts` SET `sitelogged` = '".$loggedtime."' WHERE `id`='".$_SESSION['id']."'") or die(mysql_error());
	session_destroy();
	$_SESSION = array();
	include('sources/public/main.php');
	echo "<meta http-equiv=refresh content=\"0; url=?cype=main\">";
}else{
	echo "<meta http-equiv=refresh content=\"0; url=?cype=main\">";
}
?>