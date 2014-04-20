<?php 
if(basename($_SERVER["PHP_SELF"]) == "update.php"){
    die("403 - Access Forbidden");
}
if(isset($_SESSION['id'])){
	if(isset($_SESSION['admin'])){
		
	} else {
		redirect("?base");
	}
} else{
	redirect("?base");
}
?>