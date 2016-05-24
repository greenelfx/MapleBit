<?php 
if (basename($_SERVER["PHP_SELF"]) == "logout.php") {
	die("403 - Access Forbidden");
}
if ($_SESSION['id']) {
	session_destroy();
	$_SESSION = array();
	include('sources/public/main.php');
	redirect("?base=main");
} else {
	redirect("?base=main");
}
?>