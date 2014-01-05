<?php 
if(basename($_SERVER["PHP_SELF"]) == "properties.php"){
	die("403 - Access Forbidden");
}
/* Site Controls */
$properties = $mysqli->query("SELECT * FROM ".$prefix."properties");
$prop = $properties->fetch_assoc();
$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

/* Name of server */
$servername = $prop['name'];
/* Site title */
$sitetitle = $prop['name'];
$pb = " (Powered by MapleBit)";
$siteurl = $prop['siteurl'];
$banner = $prop['banner'];
$background = $prop['background'];
$bgcolor = $prop['bgcolor'];
$bgrepeat = $prop['bgrepeat'];
$bgcenter = $prop['bgcenter'];
$bgfixed = $prop['bgfixed'];
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
$pcap = $prop['pcap'];
/* Flood Interval */
$basefloodint = $prop['floodint'];
/* Level for GMs and up */
$gmlevel = $prop['gmlevel'];
/* Accounts Per IP */
$MaxAcc = $prop['maxaccounts'];
/* Get Theme */
$theme = $prop['theme'];
$getdarkhemes = array("cyborg", "slate", "amelia");
if (in_array($theme, $getdarkhemes)) {
    $themetype = "dark";
} else{
	$themetype = "light";
}
/*Get Vote Config*/
$vlink = $prop['vlink'];
$gnx = $prop['gnx'];
$gvp = $prop['gvp'];
$colnx = $prop['colnx'];
$colvp = $prop['colvp'];
$vtime = $prop['vtime'];
	
$censored = array("fuck","dick","fail","suck","cock","bitch","ass","cunt","vagina","penis","cunt");
?>