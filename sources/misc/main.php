<?php 
if(basename($_SERVER["PHP_SELF"]) == "main.php"){
    die("403 - Access Forbidden");
}
if(isset($_GET['script'])){
	$script = $_GET['script'];
}else{
	$script = "";
}
if($getbase == "misc"){
	if($script == ""){
		header("Location: ?base=main");
	}elseif($script == "login"){
		include('sources/misc/login.php');
	}elseif($script == "logout"){
		include('sources/misc/logout.php');
	}elseif($script == "home"){
		include('sources/misc/home.php');
	}else{
		redirect("?base=main");
	}
}
?>