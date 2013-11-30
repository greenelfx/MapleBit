<?php 
session_start();
# Disable Notices


# Is Cype installed?
if(!file_exists('assets/config/install/installdone.txt')){
	header("Location: assets/config/install/install.php");
	exit;
} else {
	# Get Database Information
	require_once("assets/config/database.php");

	# Import Essential Files
	require_once("assets/config/properties.php");
	require_once("assets/config/afuncs.php");

	# Define $getcype variable
	$getcype = isset($_GET['cype']) ? $_GET['cype'] : "";

	switch($getcype){
		case NULL:
			header('Location: ?cype=main');
			break;
		case "main":
			include("sources/structure/header.php");
			include("sources/public/main.php");
			include("sources/structure/footer.php");
			break;
		case "ucp":
			include("sources/structure/header.php");
			include("sources/ucp/main.php");
			include("sources/structure/footer.php");
			break;
		case "admin":
		    //include("sources/structure/header.php");
			include("sources/admin/main.php");
			//include("sources/structure/footer.php");
			break;
		case "gmcp":
			include("sources/structure/header.php");
			include("sources/gmcp/main.php");
			include("sources/structure/footer.php");
			break;
		case "misc":
			include("sources/misc/main.php");
			break;
		default:
			include("sources/structure/header.php");
			include("sources/public/main.php");
			include("sources/structure/footer.php");
			break;
	}
}

$mysqli->close();
?>