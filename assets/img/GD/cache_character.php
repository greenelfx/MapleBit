<?php
error_reporting(0);
#+----------------------------------------------------------+
#|                   Made by HoltHelper                   	|
#|                                                        	|
#| Please give proper credits when using this code since  	|
#| It took me over a couple of months to finish this code 	|
#|                                                        	|
#| Updated by Basis and Limecat: 							|
#| + Fixed Dual Bow Gun display (Basis)						|
#| + Fixed Knuckler display	(Limecat)						|
#| + Added caching of character image						|
#+----------------------------------------------------------+

//$name = (string) mysql_real_escape_string($_GET['name']);
function createChar($name, $rootfolder){
require_once("assets/config/database.php");
global $mysqli, $prefix;
$init = $mysqli->query("SELECT `id`, `job`, `skincolor`, `hair`, `face` FROM `characters` WHERE `name` = '".$name."'");
$field = $init->fetch_assoc();
$getipos = $mysqli->query("SELECT `itemid`, `position` FROM `inventoryitems` WHERE `characterid` = '".$field['id']."' AND inventorytype = '-1' ORDER BY position DESC", 0);

$job = (int) $field['job'];
$skin = (int) $field['skincolor'] + 2000;
$face = (int) $field['face'];
$hair = (int) $field['hair'];

while($gotAcc = $getipos->fetch_assoc()) {
	switch($gotAcc['position']) {
		case ($gotAcc['position'] == "-1" || $gotAcc['position'] == "-101"):
			if($gotAcc['itemid'] != 1002186)
				$hat = $gotAcc['itemid'];
			else
				$hat = NULL;
		break;
		case ($gotAcc['position'] == "-2" || $gotAcc['position'] == "-102"):$mask=$gotAcc['itemid'];break;
		case ($gotAcc['position'] == "-3" || $gotAcc['position'] == "-103"):$eyes=$gotAcc['itemid'];break;
		case ($gotAcc['position'] == "-4" || $gotAcc['position'] == "-104"):$ears=$gotAcc['itemid'];break;
		case ($gotAcc['position'] == "-5" || $gotAcc['position'] == "-105"):
			if(substr($gotAcc['itemid'], 0, 3) == 105) {
				$overAll = $gotAcc['itemid'];
			} else {
				$top = $gotAcc['itemid'];
			}
			if($gotAcc['position'] == "-105"){
				$overAll = $gotAcc['itemid'];
				$top = $gotAcc['itemid'];
			}
		break;
		case ($gotAcc['position'] == "-6" || $gotAcc['position'] == "-106"):
			if(substr($gotAcc['itemid'], 0, 3) == 106) {
				$overAll = NULL;
			} else {
				$pants = $gotAcc['itemid'];
			}
			if($gotAcc['position'] == "-106"){
				$overAll = NULL;
				$pants = $gotAcc['itemid'];
			}
		break;
		case ($gotAcc['position'] == "-7" || $gotAcc['position'] == "-107"):$shoe=$gotAcc['itemid'];break;
		case ($gotAcc['position'] == "-8" || $gotAcc['position'] == "-108"):$glove=$gotAcc['itemid'];break;
		case ($gotAcc['position'] == "-9" || $gotAcc['position'] == "-109"):$cape=$gotAcc['itemid'];break;
		case ($gotAcc['position'] == "-10" || $gotAcc['position'] == "-110"):$shield=$gotAcc['itemid'];break;
		case "-11":
			$wep = $gotAcc['itemid'];
			$wepType = substr($wep, 0, 3);
			switch($wepType) {
				case ($wepType >= 130 && $wepType <= 138):
				case 145:
				case ($wepType >= 147 && $wepType <= 149):
				case 121:
				case 122:
				case 139:
				case 152:
				case 153:
				case 154:
				case 155:
					$stand = 1;
				break;
				case ($wepType >= 140 && $wepType <= 143):
				case 146:
					$stand = 2;
				break;
				case 144:
					$location = $rootfolder."/GD/Weapon/0{$wep}.img/stand1.0.weapon.png";
					file_exists($location)?$stand = 1:$stand = 2;
				break;
			}
		break;
		case "-111":
			$nxWep = $gotAcc['itemid'];
			$int = 29;
			$found = false;
			while($int <= 46 && !$found) {
				$int++;
				if(file_exists($rootfolder."/GD/Weapon/0{$nxWep}.img/{$int}.stand{$stand}.0.weapon.png")) {
					$found	= true;
					$wepNum = $int;
				}
			}
		break;
	}
}

$weapon = !$nxWep?$wep:$nxWep;

$hash = md5($name.$skin.$face.$hair.$hat.$mask.$eyes.$ears.$overAll.$top.$pants.$shoe.$glove.$cape.$shield.$weapon.$bgtype);
$shash = $mysqli->query("SELECT hash FROM ".$prefix."gdcache WHERE name = '".$name."'", 0);
if($shash->num_rows != 0){
	$field = $shash->fetch_assoc();
	if($hash != $field['hash']){
	    unlink($rootfolder."/GD/Characters/".$field['hash'].".png");
		$update = $mysqli->query("UPDATE ".$prefix."gdcache SET hash = '".$hash."' WHERE name = '".$name."'", 0);
		$Image = new Image;
		$Image->rootfolder = $rootfolder;
		$Image->setName($hash);
		$Image->setSkin($skin);
		$Image->setHair($hair,NULL);
		$Image->setStand(!$wep?1:$stand);
		$Image->setWepNum($wepNum);
		//$weapon = !$nxWep?$wep:$nxWep;
		$Image->setBG($bgtype);
		$Image->setWeapon($weapon,'weaponBelowBody');
		$Image->setCape($cape,'cape');
		$Image->setHat($hat,'acHat');
		$Image->setShield($shield);
		$Image->createBody('body');
		$Image->setShoes($shoe);
		$Image->setGlove($glove,'leftGlove');
		//if($wepType == 148){
			$Image->setKnuckler($weapon, 'weaponOverBody');
		//}
		//if($wepType == 152){
			$Image->setDualGun($weapon,'weaponL');
		//}
		$Image->setPants($pants);
		$Image->setTop($top,'top');
		$Image->setOverAll($overAll,'overall');
		$Image->setWeapon($weapon,'armBelowHeadOverMailChest');
		$Image->createBody('head');
		$Image->setAccessory($ears,'accessory');
		$Image->setHair($hair,'hairShade');
		$Image->setAccessory($mask,'mask');
		$Image->setFace(!$face?20000:$face);
		//$Image->setAccessory($mask,'mask');
		$Image->setAccessory($eyes,'accessory');
		$Image->setHair(!$hair?30030:$hair,'hair');
		$Image->setHat($hat,'regular');
		if($job == 2300 || $job == 2310 || $job == 2311 || $job == 2312) {
			$Image->createBody('mercedesEars');
		} else {
			$Image->createBody('');
		}
		if($stand == 2) {
			$Image->createBody('arm');
			$Image->setTop($top,'topArm');
			$Image->setOverAll($overAll,'overallArm');
			$Image->setCape($cape,'capeArm');
			$Image->setWeapon($weapon,'weaponOverArm');
			$Image->createBody('hand');
			$Image->setGlove($glove,'leftGlove');
			$Image->setGlove($glove,'rightGlove');
			$Image->setGlove($glove,'middleGlove');
			$Image->setWeapon($weapon,'weaponOverGlove');
		} else {
			//if($wepType == 152){
				$Image->setDualGun($weapon,'weaponR');
			//}
			$Image->setWeapon($weapon,'weapon');
			$Image->createBody('arm');
			$Image->setWeapon($weapon,'weaponOverArm');
			$Image->setTop($top,'topArm');
			$Image->setOverAll($overAll,'overallArm');
			$Image->setCape($cape,'capeArm');
			$Image->setGlove($glove,'rightGlove');
			$Image->setWeapon($weapon,'weaponOverGlove');
			$Image->setWeapon($weapon,'weaponBelowArm');
			$Image->setWeapon($weapon,'weaponOverHand');
			//if($wepType == 148){
				$Image->setKnuckler($weapon, 'weaponOverArm');
			//}
		}
	}
} else {
	$mysqli->query("INSERT INTO ".$prefix."gdcache (`hash`, `name`) VALUES ('".$hash."','".$name."')");
	$Image = new Image;
	$Image->rootfolder = $rootfolder;
	$Image->setName($hash);
	$Image->setSkin($skin);
	$Image->setHair($hair,NULL);
	$Image->setStand(!$wep?1:$stand);
	$Image->setWepNum($wepNum);
	//$weapon = !$nxWep?$wep:$nxWep;
	
	$Image->setWeapon($weapon,'weaponBelowBody');
	$Image->setCape($cape,'cape');
	$Image->setHat($hat,'acHat');
	$Image->setShield($shield);
	$Image->createBody('body');
	$Image->setShoes($shoe);
	$Image->setGlove($glove,'leftGlove');
	//if($wepType == 148){
		$Image->setKnuckler($weapon, 'weaponOverBody');
	//}
	//if($wepType == 152){
		$Image->setDualGun($weapon,'weaponL');
	//}
	$Image->setPants($pants);
	$Image->setTop($top,'top');
	$Image->setOverAll($overAll,'overall');
	$Image->setWeapon($weapon,'armBelowHeadOverMailChest');
	$Image->createBody('head');
	$Image->setAccessory($ears,'accessory');
	$Image->setHair($hair,'hairShade');
	$Image->setAccessory($mask,'mask');
	$Image->setFace(!$face?20000:$face);
	//$Image->setAccessory($mask,'mask');
	$Image->setAccessory($eyes,'accessory');
	$Image->setHair(!$hair?30030:$hair,'hair');
	$Image->setHat($hat,'regular');
		if($job == 2300 || $job == 2310 || $job == 2311 || $job == 2312) {
			$Image->createBody('mercedesEars');
		} else {
			$Image->createBody('');
		}
	if($stand == 2) {
		$Image->createBody('arm');
		$Image->setTop($top,'topArm');
		$Image->setOverAll($overAll,'overallArm');
		$Image->setCape($cape,'capeArm');
		$Image->setWeapon($weapon,'weaponOverArm');
		$Image->createBody('hand');
		$Image->setGlove($glove,'leftGlove');
		$Image->setGlove($glove,'rightGlove');
		$Image->setGlove($glove,'middleGlove');
		$Image->setWeapon($weapon,'weaponOverGlove');
	} else {
		//if($wepType == 152){
			$Image->setDualGun($weapon,'weaponR');
		//}
		$Image->setWeapon($weapon,'weapon');
		$Image->createBody('arm');
		$Image->setWeapon($weapon,'weaponOverArm');
		$Image->setTop($top,'topArm');
		$Image->setOverAll($overAll,'overallArm');
		$Image->setCape($cape,'capeArm');
		$Image->setGlove($glove,'rightGlove');
		$Image->setWeapon($weapon,'weaponOverGlove');
		$Image->setWeapon($weapon,'weaponBelowArm');
		$Image->setWeapon($weapon,'weaponOverHand');
		//if($wepType == 148){
			$Image->setKnuckler($weapon, 'weaponOverArm');
		//}
	}
}
}
?>