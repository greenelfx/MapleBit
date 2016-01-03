<?php 
session_start();
# Disable Notices

# Is MapleBit installed?
if(!file_exists('assets/config/install/installdone.txt')){
	header("Location: assets/config/install/install.php");
	exit;
} else {
	# Get Database Information
	require_once("assets/config/database.php");

	# Import Essential Files
	require_once("assets/config/properties.php");
	require_once("assets/config/afuncs.php");
	# Define $getbase variable
	$getbase = isset($_GET['base']) ? $_GET['base'] : "";

	switch($getbase){
		case NULL:
			header('Location: ?base=main');
			break;
		case "main":
			$getslug = $mysqli->query("SELECT slug, title, visible from ".$prefix."pages");
			while($fetchslug = $getslug->fetch_assoc()) {
				$slugs[] = $fetchslug['slug'];
				$slugarray[] = array($fetchslug['slug'], $fetchslug['title'], $fetchslug['visible']);
			}
			include("sources/structure/header.php");
			include("sources/public/main.php");
			include("sources/structure/footer.php");
			break;
		case "ucp":
			$getslug = $mysqli->query("SELECT slug, title, visible from ".$prefix."pages");
			while($fetchslug = $getslug->fetch_assoc()) {
				$slugarray[] = array($fetchslug['slug'], $fetchslug['title'], $fetchslug['visible']);
			}
			include("sources/structure/header.php");
			include("sources/ucp/main.php");
			include("sources/structure/footer.php");
			break;
		case "admin":
			include("sources/structure/admin/header.php");
			include("sources/admin/main.php");
			break;
		case "gmcp":
			$getslug = $mysqli->query("SELECT slug, title, visible from ".$prefix."pages");
			while($fetchslug = $getslug->fetch_assoc()) {
				$slugarray[] = array($fetchslug['slug'], $fetchslug['title'], $fetchslug['visible']);
			}
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