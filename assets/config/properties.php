<?php 
if(basename($_SERVER["PHP_SELF"]) == "properties.php"){
	die("403 - Access Forbidden");
}
/* Site Controls */
$properties = $mysqli->query("SELECT * FROM cype_properties");
$prop = $properties->fetch_assoc();
$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

/* Name of server */
$servername = $prop['name'];
/* Site title */
$sitetitle = $prop['name'];
$pb = " (Powered by Cype)";
/* Download link for client */
$client = $prop['client'];
/* Server Version */
$version = $prop['version'];
/* Forum url*/
$forumurl = $prop['forumurl'];
/* Server Rates */
$exprate = $prop['exprate'];
$mesorate = $prop['mesorate'];
$droprate = $prop['droprate'];
/* Flood Prevention */
$cypeflood = $prop['flood'];
/* Flood Interval */
$cypefloodint = $prop['floodint'];
/* Level for GMs and up */
$gmlevel = $prop['gmlevel'];
/* Accounts Per IP */
$MaxAcc = $prop['maxaccounts'];
/* Get Theme */
$theme = $prop['theme'];
/*Get Vote Config*/
$vlink = $prop['vlink'];
$gnx = $prop['gnx'];
$gvp = $prop['gvp'];
$colnx = $prop['colnx'];
$colvp = $prop['colvp'];
$vtime = $prop['vtime'];
	
$censored = array("fuck","dick","fail","suck","cock","bitch","ass","cunt","vagina","penis","cunt");
?>