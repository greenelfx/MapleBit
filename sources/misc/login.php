<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$is_ajax = $_REQUEST['is_ajax'];
	if(isset($is_ajax) && $is_ajax) {
		if(isset($_COOKIE["block"])) {
			$cookie = json_decode($_COOKIE['block']);
			$time = $cookie->expiry - time();
			echo "wait%" . $time;
			return;
		}
		else {
			if(!isset($_SESSION['attempts'])) {
				$_SESSION['attempts'] = 1;
			}
			else {
				if($_SESSION['attempts'] >= 3) {
					$expiry = time() + 60;
					$cookieData = array("data" => 1, "expiry" => $expiry);
					setcookie("block", json_encode($cookieData), $expiry);
					$_SESSION['attempts'] = 1;
					$time = $expiry - time();
					echo "wait%" . $time;
					return;
				}
				else {
					$_SESSION['attempts']++;
				}
			}
		}
		$u = $mysqli->real_escape_string($_REQUEST['username']);
		$p = $_REQUEST['password'];
		$s = $mysqli->query("SELECT * FROM `accounts` WHERE `name`='".$u."'") or die();
		$i = $s->fetch_assoc();
		if($i['password'] == hash('sha512',$p.$i['salt']) || sha1($p) == $i['password']) {
			#echo "SELECT * FROM `accounts` WHERE `name`='".$i['name']."' AND `password`='".$i['password']."'";
			$userz = $mysqli->query("SELECT * FROM `accounts` WHERE `name`='".$i['name']."' AND `password`='".$i['password']."'") or die();
			$auser = $userz->fetch_assoc();
			$checkpname = $mysqli->query("SELECT * FROM ".$prefix."profile WHERE accountid=".$auser['id']."");
			$countcheckpname = $checkpname->num_rows;
			$checkprofile = $checkpname->fetch_assoc();
			$_SESSION['id'] = $auser['id'];
			$_SESSION['name'] = $auser['name'];
			$_SESSION['mute'] = $auser['mute'];
			$_SESSION['email'] = $auser['email'];
			if($countcheckpname == 1) {
				$_SESSION['pname'] =  $checkprofile['name'];
			}
			else {$_SESSION['pname'] = "checkpname";}
			if($auser['webadmin'] == "1") {
				$_SESSION['admin'] = $auser['webadmin'];
			}
			if(isset($auser['gm']) && $auser['gm'] >= $gmlevel) { // Make sure that the gm column exists. If it does, check if gmLevel is above
				$_SESSION['gm'] = $auser['gm'];
			}
			echo "success";
		}
		else {
			// echo "bad password";
		}
	}
	else {
		// echo "not ajax";
	}
}