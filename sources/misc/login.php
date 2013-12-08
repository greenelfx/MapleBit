<?php
include_once('assets/config/database.php');
$is_ajax = $_REQUEST['is_ajax'];
if(isset($is_ajax) && $is_ajax) {

    $u = $mysqli->real_escape_string($_REQUEST['username']);
    $p = $mysqli->real_escape_string($_REQUEST['password']);
	$s = $mysqli->query("SELECT * FROM `accounts` WHERE `name`='".$u."'") or die();
	$i = $s->fetch_assoc();
	if($i['password'] == hash('sha512',$p.$i['salt']) || sha1($p) == $i['password']){
		#echo "SELECT * FROM `accounts` WHERE `name`='".$i['name']."' AND `password`='".$i['password']."'";
		$userz = $mysqli->query("SELECT * FROM `accounts` WHERE `name`='".$i['name']."' AND `password`='".$i['password']."'") or die();
		$auser = $userz->fetch_assoc();
		$checkpname = $mysqli->query("SELECT * FROM ".$prefix."profile WHERE accountid=".$auser['id']."");
		$countcheckpname = $checkpname->num_rows;
		$checkprofile = $checkpname->fetch_assoc();
		$_SESSION['id'] = $auser['id'];
		$_SESSION['name'] = $auser['name'];	
		$_SESSION['mute'] = $auser['mute'];	
		if($countcheckpname == 1){
			$_SESSION['pname'] =  $checkprofile['name'];
		}
		else {$_SESSION['pname'] = "checkpname";}
		if($auser['webadmin'] == "1"){
			$_SESSION['admin'] = $auser['webadmin'];
		}
		if($auser['gm'] >= 3){
			$_SESSION['gm'] = $auser['gm'];
		}
        echo "success";
	}
}
?>