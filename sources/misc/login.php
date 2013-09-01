<?php
$is_ajax = $_REQUEST['is_ajax'];
if(isset($is_ajax) && $is_ajax) {

    $u = sql_sanitize($_REQUEST['username']);
    $p = sql_sanitize($_REQUEST['password']);
	$s = $mysqli->query("SELECT * FROM `accounts` WHERE `name`='".$u."'") or die();
	$i = $s->fetch_assoc();
	if($i['password'] == hash('sha512',$p.$i['salt']) || sha1($p) == $i['password']){
		$userz = $mysqli->query("SELECT * FROM `accounts` WHERE `name`='".$i['name']."' AND `password`='".$i['password']."'") or die();
		$auser = $userz->fetch_assoc();
		$_SESSION['id'] = $auser['id'];
		$_SESSION['name'] = $auser['name'];
		$_SESSION['mute'] = $auser['mute'];
		if($auser['webadmin'] == "1"){
			$_SESSION['admin'] = $auser['webadmin'];
		}
		if($auser['gm'] == $gmlevel){
			$_SESSION['gm'] = $auser['gm'];
		}
		$name = $mysqli->query("SELECT * FROM `cype_profile` WHERE `accountid`='".$auser['id']."'") or die();
		$pname = $name->fetch_assoc();
		if($pname['name'] == NULL){
			$_SESSION['pname'] = NULL;
		}else{
			$_SESSION['pname'] = $pname['name'];
		}
        echo "success";
	}
}
?>