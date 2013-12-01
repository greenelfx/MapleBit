<?php 
if(isset($_GET['script'])){
	$script = $_GET['script'];
}else{
	$script = "";
}
if($getcype == "misc"){
	if($script == ""){
		header("Location: ?cype=main");
	}elseif($script == "login"){
		include('sources/misc/login.php');
	}elseif($script == "logout"){
		include('sources/misc/logout.php');
	}else{
		header("Location: ?cype=main");
	}
}
?>