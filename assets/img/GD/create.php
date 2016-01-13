<?php
error_reporting(0);
/*
	+--------------------------------------------------------+
	|                   Made by HoltHelper                   |
	|			 Edited by LittleClgt@RaGEZONE.Com			 |
	|                                                        |
	| Please give proper credits when using this code since  |
	| It took me over a couple of months to finish this code |
	|                                                        |
	+--------------------------------------------------------+

	Added Ian edits
*/
require_once('../../config/database.php');
require_once('coordinates.php');
if(!empty($_GET['name'])) {
	$name = $mysqli->real_escape_string($_GET['name']);
	if($mysqli->query("SELECT name FROM characters WHERE name = '$name'")->num_rows == 1) {
	$Image  = new Character;
	$cache = "Characters/".$name.".png";

	if(file_exists($cache) && (time() - (43200) < filemtime($cache))) {
		$Image->charType('use', $name);
	} else {
		if($gotChar = mysqli_fetch_row($mysqli->query("SELECT `id`, `skincolor`, `gender`, `hair`, `face` FROM `characters` WHERE `name` = '$name' LIMIT 1"))) {
			$getAcc = $mysqli->query("SELECT `itemid`, `position` FROM `inventoryitems` WHERE `characterid` = '{$gotChar[0]}' AND `inventorytype` = '-1' ORDER BY `position` DESC");
			$oHash  = mysqli_fetch_row($mysqli->query("SELECT `hash` FROM `bit_gdcache` WHERE `id` = '{$gotChar[0]}' LIMIT 1"));
			$cap    = $mask = $eyes = $ears = $coat = $pants = $shoes = $glove = $cape = $shield = $weapon = NULL; // ANTI rapage

			while($gotAcc = mysqli_fetch_row($getAcc)) {
				switch($gotAcc[1]) {
					case -1: case -101:$cap    = $gotAcc[0];break;
					case -2: case -102:$mask   = $gotAcc[0];break;
					case -3: case -103:$eyes   = $gotAcc[0];break;
					case -4: case -104:$ears   = $gotAcc[0];break;
					case -5: case -105:$coat   = $gotAcc[0];break;
					case -6: case -106:$pants  = $gotAcc[0];break;
					case -7: case -107:$shoes  = $gotAcc[0];break;
					case -8: case -108:$glove  = $gotAcc[0];break;
					case -9: case -109:$cape   = $gotAcc[0];break;
					case -10:case -110:$shield = $gotAcc[0];break;
					case -11:case -111:
						$weapon = $gotAcc[0];
						$Image->setWepInfo($weapon);
					break;
				}
			}
			if ($weapon == null){
				$weapon = 1;
				$Image->setWepInfo($weapon);
			}
			$nHash = hash("sha1", $cap.$mask.$eyes.$ears.$coat.$pants.$shoes.$glove.$cape.$shield.$weapon);

			if($nHash === $oHash[0]) {
				$Image->charType('use', $name);
				touch($cache);
			} else {
				$Image->setVaribles(array(
					"Skin"   => $gotChar[1],
					"Gender" => $gotChar[2],
					"Hair"   => $gotChar[3],
					"Face"   => $gotChar[4],
					"Cap"    => $cap,
					"Mask"   => $mask,
					"Eyes"   => $eyes,
					"Ears"   => $ears,
					"Coat"   => $coat,
					"Pants"  => $pants,
					"Shoes"  => $shoes,
					"Glove"  => $glove,
					"Cape"   => $cape,
					"Shield" => $shield,
					"Weapon" => $weapon
				));
				$Image->setWeapon('weaponBelowBody');
				$Image->setCap('capeBelowBody');
				$Image->setCap('capBelowHead');
				$Image->setCap('capAccessoryBelowBody');
				$Image->setCape('cape');
				$Image->setCape('backWing');
				$Image->setCap('backCap');
				$Image->setCape('capeBelowBody');
				$Image->setCap('capeBelowBody');
				$Image->setShield();
				$Image->setHair('hairBelowBody');
				$Image->setShoes('capAccessoryBelowBody');
				$Image->setWeapon('weaponOverArmBelowHead');
				$Image->createBody('body');
				$Image->setShoes('shoes');
				$Image->setShoes('weaponOverBody');
				$Image->setGlove('l', 1);
				$Image->setWeapon('weaponOverBody');
				$Image->setPants();
				$Image->setCoat('mail');
				$Image->setShoes('shoesTop');
				$Image->setShoes('shoesOverPants');
				$Image->setShoes('pantsOverMailChest');
				$Image->setShoes('gloveWristBelowMailArm');
				$Image->setWeapon('armBelowHeadOverMailChest');
				$Image->setHair('hairBelowHead');
				$Image->setCap('capBelowHead');
				$Image->createBody('head');
				$Image->setAccessory('Ears', 'accessoryEar');
				$Image->setCap('backHairOverCape');
				$Image->setCap('backHair');
				$Image->setHair('hairShade');
				$Image->setCap('capAccessoryBelowAccFace');
				$Image->setAccessory('Mask', 'accessoryFaceBelowFace');
				$Image->setAccessory('Eyes', 'accessoryEyeBelowFace');
				$Image->setFace();
				$Image->setAccessory('Mask', 'accessoryFace');
				$Image->setCap('accessoryEyeOverCap');
				$Image->setAccessory('Eyes', 'accessoryEye');
				$Image->setCap('accessoryEar');
				$Image->setHair('hair');
				$Image->setHair('hairOverHead');
				$Image->setAccessory('Ears', 'accessoryEarOverHair');
				$Image->setAccessory('Eyes', 'accessoryOverHair');
				$Image->setAccessory('Eyes', 'hairOverHead');
				$Image->setCap('capBelowAccessory');
				$Image->setCap('0');
				$Image->setCap('cap');
				$Image->setCap('body');
				$Image->setCap('capOverHair');
				$Image->setAccessory('Mask', 'capOverHair');
				$Image->setAccessory('Eyes', 'accessoryEyeOverCap');
				$Image->setAccessory('Mask', 'capeOverHead');
				$Image->setCape('capeOverHead');
				$Image->setCape('capOverHair');
				$Image->setWeapon('weapon');
				$Image->createBody('arm');
				$Image->setShield('weaponOverArmBelowHead');
				$Image->setWeapon('weaponBelowArm');
				$Image->setCoat('mailArm');
				$Image->setCape('capeArm');
				$Image->setWeapon('weaponOverArm');
				$Image->createBody('hand');
				$Image->setGlove('l', 2);
				$Image->setGlove('r');
				$Image->setWeapon('weaponOverHand');
				$Image->setWeapon('weaponOverGlove');
				$Image->setWeapon('weaponWristOverGlove');
				$Image->setWeapon('emotionOverBody');
				$Image->setWeapon('characterEnd');


				$Image->charType('create', $name);
			}
		} else
			$Image->charType('use', 'faek');
	}
}
}
?>