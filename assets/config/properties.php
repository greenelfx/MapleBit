<?php 
/*
    Copyright (C) 2009  Murad <Murawd>
						Josh L. <Josho192837>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

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
	/* Vote Link */
	$vote = $prop['vote'];
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
	/* Player Cap */
	$pcap = $prop['pcap'];
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