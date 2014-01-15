<?php 
if(isset($_SESSION['id'])){
	if(isset($_SESSION['admin'])){
		
	} else {
		redirect("?base");
	}
} else{
	redirect("?base");
}
?>