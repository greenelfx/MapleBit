<?php 
session_start();
# Disable Notices

# Is MapleBit installed?
if(!file_exists('assets/config/install/installdone.txt')) {
	header("Location: assets/config/install/install.php");
	exit;
} else {
	# Get Database Information
	require_once("assets/config/database.php");

	# Import Essential Files
	require_once("assets/config/properties.php");
	require_once("assets/config/funcs.php");
	
	# Define $getbase variable
	$getbase = isset($_GET['base']) ? $_GET['base'] : "";

	$getslug = $mysqli->query("SELECT slug, title, visible from ".$prefix."pages");
	while($fetchslug = $getslug->fetch_assoc()) {
		$slugs[] = $fetchslug['slug'];
		$slugarray[] = array($fetchslug['slug'], $fetchslug['title'], $fetchslug['visible']);
	}

	switch($getbase) {
		case NULL:
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
			include("sources/structure/admin/header.php");
			include("sources/admin/main.php");
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