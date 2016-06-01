<?php 
if(basename($_SERVER["PHP_SELF"]) == "main.php") {
	die("403 - Access Forbidden");
}

if(isset($_GET['script'])) {
	$script = $_GET['script'];
}

if($script === "login") {
	include('sources/misc/login.php');
}
elseif($script === "logout") {
	include('sources/misc/logout.php');
}
elseif($script === "home") {
	include('sources/misc/home.php');
}
else {
	header("Location: ?base=main");
}