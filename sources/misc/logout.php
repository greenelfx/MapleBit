<?php 
if(basename($_SERVER["PHP_SELF"]) == "logout.php"){
    die("403 - Access Forbidden");
}
if($_SESSION['id']){
	$logouttime = 100;
	$date = date();
	$timenow = time();
	$loggedtime = $timenow - $logouttime;
	$query = $mysqli->query("UPDATE `accounts` SET `sitelogged` = '".$loggedtime."' WHERE `id`='".$_SESSION['id']."'") or die(mysql_error());
	session_destroy();
	$_SESSION = array();
	include('sources/public/main.php');
	redirect("?cype=main");
}else{
	redirect("?cype=main");
}
?>