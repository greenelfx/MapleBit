<?php
if(basename($_SERVER["PHP_SELF"]) == "properties.php") {
	die("403 - Access Forbidden");
}
try {
	/* Site Controls */
	$properties = $mysqli->query("SELECT * FROM ".$prefix."properties");
	if (!$properties)
        throw new Exception($mysqli->error);
	$prop = $properties->fetch_assoc();
	$themetype = "light";
	$nav = "navbar navbar-default";
	$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

	/* Name of server */
	$servername = $prop['name'];
	$siteurl = $prop['siteurl'];
	$banner = $prop['banner'];
	$background = $prop['background'];
	$bgcolor = $prop['bgcolor'];
	$bgrepeat = $prop['bgrepeat'];
	$bgcenter = $prop['bgcenter'];
	$bgfixed = $prop['bgfixed'];
	$bgcover = $prop['bgcover'];
	/* Download link for client */
	$client = $prop['client'];
	$server = $prop['server'];
	/* Server Version and Type*/
	$version = (float)$prop['version'];
	$servertype = $prop['type'];
	/* Forum url*/
	$forumurl = $prop['forumurl'];
	/* Server Rates */
	$exprate = $prop['exprate'];
	$mesorate = $prop['mesorate'];
	$droprate = $prop['droprate'];
	/* Flood Prevention */
	$baseflood = $prop['flood'];
	/* Flood Interval */
	$basefloodint = $prop['floodint'];
	/* Level for GMs and up */
	$gmlevel = $prop['gmlevel'];
	/* Get Theme */
	$theme = $prop['theme'];
	$getdarkhemes = array("cyborg", "slate", "superhero", "darkly");
	if (in_array($theme, $getdarkhemes)) {
	    $themetype = "dark";
	}
	/*Get Vote Config*/
	$colnx = $prop['colnx'];
	$colvp = $prop['colvp'];

	if ($prop['nav']) {
		$nav = "navbar navbar-default navbar-inverse";
	}
} catch(Exception $e) {
	print("Unable to load MapleBit configuration. Perhaps MapleBit has not been installed yet.<br/>To resolve this, delete <b>assets/config/install/installdone.txt</b> and reload this page.");
	exit();
}
?>