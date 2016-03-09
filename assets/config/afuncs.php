<?php
if(basename($_SERVER["PHP_SELF"]) == "afuncs.php"){
	die("403 - Access Forbidden");
}

include_once('database.php');
/* Logged in time Handler - Do not touch unless you know what you're doing */
if(isset($_SESSION['id'])){
	global $mysqli, $prefix;
	$logouttime = 300;
	$timenow = time();
	$loggedtime = $timenow - $logouttime;
	$query = $mysqli->query("UPDATE `accounts` SET `sitelogged` = '".$timenow."' WHERE `id`='".$_SESSION['id']."'") or die(mysql_error());
	$retrieve = $mysqli->query("SELECT * FROM `accounts` WHERE `sitelogged` >= '".$loggedtime."'") or die(mysql_error());
	$online = $retrieve->fetch_assoc();
}

/* Functions for Cype */

function getOnline(){
	global $mysqli, $prefix;
	$logouttime = 300;
	$timenow = time();
	$loggedtime = $timenow - $logouttime;
	$a = $mysqli->query("SELECT * FROM `accounts` WHERE `sitelogged` >= '".$loggedtime."'") or die();
	$b = $a->num_rows;
	return $b;
}
function onlineCheck($string){
	global $mysqli, $prefix;
	$logouttime = 300;
	$timenow = time();
	$loggedtime = $timenow - $logouttime;
	$a = $mysqli->query("SELECT * FROM `accounts` WHERE `sitelogged` >= '".$loggedtime."' AND `id`='".$string."'") or die();
	$b = $a->fetch_assoc();
	if($b['sitelogged'] >= $loggedtime){
		$check = "<span class=\"label label-success\">Online</span>";
	}else{
		$check = "<span class=\"label label-danger\">Offline</span>";
	}
	return $check;
}
function isProfile( $type, $string ){
	global $mysqli, $prefix;
	if ($type == 'charname') {
		$a = $mysqli->query("SELECT * FROM `characters` WHERE `name`='".$string."'") or die();
		$b = $a->fetch_assoc();
		$c = $b['accountid'];
		$d = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$c."'") or die();
		$e = $d->num_rows;
		if ($e > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	else if ($type == 'charid') {
		$a = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$string."'") or die();
		$b = $a->fetch_assoc();
		$c = $b['accountid'];
		$d = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$c."'") or die();
		$e = $d->num_rows;
		if ($e > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	else if ($type == 'accname') {
		$a = $mysqli->query("SELECT * FROM `accounts` WHERE `name`='".$string."'") or die();
		$b = $a->fetch_assoc();
		$c = $b['id'];
		$d = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$c."'") or die();
		$e = $d->num_rows;
		if ($e > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	else if ($type == 'accid') {
		$a = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$string."'") or die();
		$b = $a->num_rows;
		if ($a > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	else {
		$a = "Cype Error: Parameters for isProfile() are incorrect";
		return $a;
	}
}
function getInfo( $type, $string, $how ){
	global $mysqli, $prefix;
	if ($type == 'charname') {
		if ($how == 'charid') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['name'];
		}
		else if ($how == 'rank') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `rank`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['name'];
		}
		else if ($how == 'accid') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `accountid`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['name'];
		}
		else if ($how == 'profilename') {
			$a = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `characters` WHERE `accountid`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else {
			$a = "Cype Error: Parameters for getInfo() are incorrect";
			return $a;
		}
	}
	else if ($type == 'accname') {
		if ($how == 'charid') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else if ($how == 'charname') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else if ($how == 'accid') {
			if ($string = '".$prefix."session') {
				$a = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$_SESSION['id']."'") or die();
				$b = $a->fetch_assoc();
				return $b['name'];
			} else {
				$a = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$string."'") or die();
				$b = $a->fetch_assoc();
				return $b['name'];
			}
		}
		else if ($how == 'profilename') {
			$a = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else if ($how == 'rank') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `rank`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else {
			$a = "Cype Error: Parameters for getInfo() are incorrect";
			return $a;
		}
	}
	else if ($type == 'charid') {
		if ($how == 'charname') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['id'];
		}
		if ($how == 'rank') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `rank`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['id'];
		}
		else if ($how == 'accid') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `accountid`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['id'];
		}
		else if ($how == 'profilename') {
			$a = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `characters` WHERE `accountid`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['id'];
		}
		else {
			$a = "Cype Error: Parameters for getInfo() are incorrect";
			return $a;
		}
	}
	else if ($type == 'accid') {
		if ($how == 'charid') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['accountid'];
		}
		else if ($how == 'charname') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['accountid'];
		}
		else if ($how == 'accname') {
			$a = $mysqli->query("SELECT * FROM `accounts` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['id'];
		}
		else if ($how == 'profilename') {
			$a = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['accountid'];
		}
		else if ($how == 'rank') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `rank`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['accountid'];
		}
		else {
			$a = "Cype Error: Parameters for getInfo() are incorrect";
			return $a;
		}
	}
	else if ($type == 'profilename') {
		if ($how == 'charid') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else if ($how == 'charname') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else if ($how == 'accname') {
			$a = $mysqli->query("SELECT * FROM `accounts` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['id'];
			$d = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else if ($how == 'accname') {
			$a = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['name'];
		}
		else if ($how == 'rank') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `rank`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else {
			$a = "Cype Error: Parameters for getInfo() are incorrect";
			return $a;
		}
	}
	else {
		$a = "Cype Error: Parameters for getInfo() are incorrect";
		return $a;
	}
}

function shortTitle($title){
	$maxlength = 30;
	$title = $title." ";
	$title = substr($title, 0, $maxlength);
	$title = substr($title, 0, strrpos($title,' '));
	$title = $title."...";
	return $title;
}

function unSolved($type){
	global $mysqli, $prefix;
	if($type == "ticket"){
		$GrabTickets = $mysqli->query("SELECT * FROM ".$prefix."tickets WHERE status = 1");
		$counttick = $GrabTickets->num_rows;
		if($counttick == 1){
			$tickquant = "is";
			$tickplural = "";
		}else{
			$tickquant = "are";
			$tickplural = "s";
		}
		return "There ".$tickquant." <a href=\"?base=admin&amp;page=ticket\"><u><b>".$counttick." unsolved ticket".$tickplural."</b></u></a>.";
	}
}

//This function is for the "BuyNX" page in the UCP
function buyNX($char, $info, $pack){
	global $mysqli, $prefix;
	//If the character is not yet selected to pay for NX
	if($char == "" && $info == ""){
		$getchars = $mysqli->query("SELECT * FROM `characters` WHERE `accountid`='".$_SESSION['id']."'") or die();
		if($numchars = $getchars->num_rows <= 0){
			return "You have not created any characters.";
		}
		else{
			while($chars = $getchars->fetch_assoc()){
				echo "
					<tr>
						<td class='regtext'>
							- <a href=\"?cype=ucp&amp;page=buynx&c=".$chars['id']."\">".$chars['name']."</a>
						</td>
					</tr>
				";
			}
		}
	}
	//If the value comes as a real number, it goes here
	elseif($char && $info == ""){
		$character = $mysqli->real_escape_string($_GET['c']);
		$checkid = $mysqli->query("SELECT * FROM `characters` WHERE `accountid`='".$_SESSION['id']."' AND `id`='".$character."'") or die();
		$c = $checkid->fetch_assoc();
			//Check to see if the Character ID is the same as the id of your account's character ID
		if($character == $c['id']){
			return $character = "yes";
		}
		else{
			return $character = "no";
		}
	}
	elseif($char == "info"){
		$character = $mysqli->real_escape_string($_GET['c']);
		$mesos = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$character."'") or die();
		$rmesos = $mesos->fetch_assoc();
		$getnx = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$rmesos['accountid']."'") or die();
		$rnx = $getnx->fetch_assoc();
		if($info == "char"){
			//return the characters name
			return $rmesos['name'];
		}
		elseif($info == "meso"){
			//return the amount of Mesos
			return number_format($rmesos['meso']);
		}
		elseif($info == "nx"){
			//return the amount of NX
			return number_format($rnx['paypalNX']);
		}
	}

	//When a Package is selected, this will activate

	elseif($char && $info == "package"){

		$m = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$char."'");
		$rm = $m->fetch_assoc();
		$nx = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$rm['accountid']."'") or die();
		$rx = $nx->fetch_assoc();
		$package = $mysqli->real_escape_string($_POST['nx']);

		//If the the user is logged in, It will execute this.
		if($rx['loggedin'] > 0){
			return "You cannot continue because you are already logged on to the game. Please log off and try again.";
		}
		//This is what package the user has selected
		elseif($pack == "1"){
			if($rm['meso'] > $pack1){
				$NXreset = $mysqli->query("UPDATE `accounts` SET `paypalNX`= paypalNX + 5000 WHERE `id`='".$rm['accountid']."'") or die();
				$MesoReset = $mysqli->query("UPDATE `characters` SET `meso`= meso - '".$pack1."' WHERE `id`='".$char."'") or die();
				return "Transaction complete! You can now spend your NX in the Cash Shop.";
			}else{
				return "You do not have enough mesos for this package.";
			}
		}elseif($pack == "2"){
			if($rm['meso'] > $pack2){
				$NXreset = $mysqli->query("UPDATE `accounts` SET `paypalNX`= paypalNX + 10000 WHERE `id`='".$rm['accountid']."'") or die();
				$MesoReset = $mysqli->query("UPDATE `characters` SET `meso`= meso - '".$pack2."' WHERE `id`='".$char."'") or die();
				echo "Transaction complete! You can now spend your NX in the Cash Shop.";
			}else{
				return "You do not have enough mesos for this package.";
			}
		}elseif($pack == "3"){
			if($rm['meso'] > $pack3){
				$NXreset = $mysqli->query("UPDATE `accounts` SET `paypalNX`= paypalNX + 30000 WHERE `id`='".$rm['accountid']."'") or die();
				$MesoReset = $mysqli->query("UPDATE `characters` SET `meso`= meso - '".$pack3."' WHERE `id`='".$char."'") or die();
				echo "Transaction complete! You can now spend your NX in the Cash Shop.";
			}else{
				return "You do not have enough mesos for this package.";
			}
		}else{
			return "An error has occured! Please try again.";
		}
	}
}

function getRealIpAddr() //for Registration
{
  if (!empty($_SERVER['HTTP_CLIENT_IP']))
  //check ip from share internet
  {
    $ip=$_SERVER['HTTP_CLIENT_IP'];
  }
  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
  //to check ip is pass from proxy
  {
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
  }
  else
  {
    $ip=$_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

function redirect($url)
{
    if (!headers_sent())
    {
        header('Location: '.$url);
        exit;
        }
    else
        {
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; exit;
    }
}
function redirect_wait5($url) {
	echo '<meta http-equiv="refresh" content="5;url='.$url.'" />';
	exit;
}

function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&amp;d=identicon&amp;r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}

function ago($time) {
   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
   $lengths = array("60","60","24","7","4.35","12","10");

   $now = time();

       $difference     = $now - $time;
       $tense         = "ago";

   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
   }

   $difference = round($difference);

   if($difference != 1) {
       $periods[$j].= "s";
   }

   return "$difference $periods[$j] ago";
}
?>