<?php
/*
	+--------------------------------------------------------+
	|                   Made by HoltHelper					 |
	|			 Edited by LittleClgt@RaGEZONE.Com			 |
	|                                                        |
	| Please give proper credits when using this code since  |
	| It took me over a couple of months to finish this code |
	|                                                        |
	+--------------------------------------------------------+

	Added Ian edits
*/
class Character extends DOMDocument {

	//Image Coord Variables
	const mainX = 44;
	const mainY = 34;
	const neckY = 65;

	//Default Gender Clothes
	public $default = array(
		0 => array( "coat" => 1040036, "pants" => 1060026),
		1 => array( "coat" => 1041046, "pants" => 1061039)
	);

	function __construct() {
		header('Pragma: public');
		header('Cache-Control: max-age=86400');
		header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
		header('Content-Type: image/png');
		$this->image = ImageCreateTrueColor(96, 96);
		ImageSaveAlpha($this->image, true);
		ImageFill($this->image, 0, 0, ImageColorAllocateAlpha($this->image, 0, 0, 0, 127));
	}

	public function setVaribles($array) {
		foreach( $array as $key => $value ) {
			switch($key) {
				case "Hair":
				case "Face": $value = "00".$value; break;
			}
			$this->$key = array(
				"ID"  => $value,
				"xml" => self::XMLoader($key."/0".$value.".img/")
			);
		}
	}

	public function setWepInfo($weapon) {
		$Location = "Weapon/0".$weapon.".img/";
		if(self::exists($Location."coord.xml")) {
			$xml = self::XMLoader($Location);
			switch($weapon) {
				case ($weapon < 1700000):
					$this->stand = $xml->_info->stand->value;
					break;
			}
		} else {
			$this->stand = 1;
		}
	}

	public function setFace() {
		if(isset($this->Face['ID'])) {
			if (!strpos($this->vSlot, 'Fc')){
				$faceX = self::mainX + $this->Face['xml']->_face->x;
				$faceY = self::mainY + $this->Face['xml']->_face->y;

				self::useImage("Face/0".$this->Face['ID'].".img/default.face.png", $faceX, $faceY);
			}
		}
    }

	public function setHair($z) {
		switch($z) {
			case "hair":
				if (!strpos($this->vSlot, 'H3'))
					self::setAHair($z);
				break;
			case "hairBelowBody":
				if (!strpos($this->vSlot, 'H4'))
					self::setAHair($z);
				break;
			case "hairBelowHead": //H2 or H6 - idk. Need to figure this out
				if (!strpos($this->vSlot, 'H2'))
					self::setAHair($z);
				break;
			case "hairOverHead":
				if (!strpos($this->vSlot, 'H5'))
					self::setAHair($z);
				break;
			case "hairShade":
				if (!strpos($this->vSlot, 'H1'))
					self::setAHair($z);
				break;
			default: //Hf Hs Hb. Wat is dis!!??
				break;
			}
	}

	public function setAHair($z) {
		$zArray = array( // Updated
			"hair"							=> array( "hair" ),
			"hairBelowBody"					=> array( "hairBelowBody", "hairBelowHead" ),
			"hairOverHead"					=> array( "hairOverHead" ),
			//"hairShade"						=> array( "hairShade" ), // manual handle this
			"hairBelowHead"					=> array( "hairBelowHead" )
		);

		if(isset($this->Hair['ID'])) {
			$hair = "_".$z;
			$hairX = self::mainX + $this->Hair['xml']->$hair->_stand1->x;
			$hairY = self::mainY + $this->Hair['xml']->$hair->_stand1->y;
			$vSlotArray = str_split($this->vSlot, 2);

			switch($z) {
				case "hair": //H3
				case "hairBelowBody": //H4
				case "hairBelowHead": //H2 or H6
				case "hairOverHead": //H5
					foreach( $zArray[$z] as $type ) {
						$hair = "_".$type;
						if (property_exists ($this->Hair['xml'] , $hair )) {
							if ($this->Hair['xml']->$hair->_stand1->z == $z) {
								$hairX = self::mainX + $this->Hair['xml']->$hair->_stand1->x;
								$hairY = self::mainY + $this->Hair['xml']->$hair->_stand1->y;
								self::useImage("Hair/0".$this->Hair['ID'].".img/default.".$z.".png", $hairX, $hairY);
							}
						}
					}
					break;
				case "hairShade": //H1
					$sType	= "_".$this->Skin['ID'];
					$sK 	= $this->Skin['ID'];
					if (!property_exists ($this->Hair['xml']->$hair , $sType)) {
						$sType  = "_0";
						$sK 	= 0;
					}
					$shadeHairX = self::mainX + $this->Hair['xml']->$hair->$sType->x;
					$shadeHairY = self::mainY + $this->Hair['xml']->$hair->$sType->y;
					self::useImage("Hair/0".$this->Hair['ID'].".img/default.hairShade.".$sK.".png", $shadeHairX, $shadeHairY);
					break;
			}
		}
	}

	public function setAccessory($aType, $z) {
		$zArray = array( // Updated
			"accessoryEye"						=> array( "default" ),
			"accessoryEyeBelowFace"				=> array( "default" ),
			"accessoryEyeOverCap"				=> array( "default" ),
			"accessoryFace"						=> array( "default", "0" ),
			"accessoryFaceBelowFace"			=> array( "default" ),
			"accessoryFaceOverFaceBelowCap"		=> array( "default" ),
			"accessoryEar"						=> array( "default" ),
			"accessoryEarOverHair"				=> array( "default" ),
			"capOverHair"						=> array( "default" ),
			"hairOverHead"						=> array( "default" ),
			"accessoryOverHair"					=> array( "default" ),
			"capeOverHead"						=> array( "default" )
		);

		//$vArray = array("Pe", "Ae", "Me", "Af", "Si", "Be", "Sh", "Po", "Ba", "Ay");// Updated

		$aType = $this->$aType;
		$Location = "Accessory/0".$aType['ID'].".img/";
		if(self::exists($Location."coord.xml")) {
			$xml = self::XMLoader($Location);
			if (!strpos($this->vSlot, $xml->_info->vslot))
				foreach( $zArray[$z] as $type ) {
					$accessory = "_".$type;
					if (property_exists ($xml , $accessory )) {
						if ($xml->$accessory->_stand1->z == $z) {
							$accessoryX = self::mainX + $xml->$accessory->_stand1->x;
							$accessoryY = self::mainY + $xml->$accessory->_stand1->y;
							self::useImage("Accessory/0".$aType['ID'].".img/default.".$type.".png", $accessoryX, $accessoryY);
						}
					}
				}
		}
	}

	public function setCap($z) {
		$zArray = array( // Updated
			"cap"						=> array( "default", "default1", "default3", "defaultTail", "0" ),
			"body"						=> array( "default" ),
			"capBelowBody"				=> array( "default", "defaultAc" ),
			"accessoryEyeOverCap"		=> array( "default" ),
			"capBelowAccessory"			=> array( "default" ),
			"backHairOverCape"			=> array( "default", "defaultBack" ),
			"capAccessoryBelowAccFace"	=> array( "default", "defaultAc" ),
			"backCap"					=> array( "default", "default1", "default2", "default4", "defaultTail", "defaultBelowBody", "defaultAc", "defaultback", "acc" ),
			"capAccessoryBelowBody"		=> array( "default", "defaultAC", "defaultAc", "defaultBelowBody" ),
			"backHair"					=> array( "default", "defaultAc" ),
			"capBelowHead"				=> array( "default", "defaultBack" ),
			"accessoryEar"				=> array( "default", "defaultB" ), //only 01003108
			"capOverHair"				=> array( "default", "default2", "effect", "0" ),
			"capeBelowBody"				=> array( "default", "defaultacc", "acc" ),
			"0"							=> array( "default" )
		);

		if (isset($this->Cap['xml'])) {
			$this->vSlot = $this->Cap['xml']->_info->vslot;
			foreach( $zArray[$z] as $type ) {
				$cap = "_".$type;
				if (property_exists ($this->Cap['xml'] , $cap )) {
					if ($this->Cap['xml']->$cap->stand1->z == $z) {

						$capX = self::mainX + $this->Cap['xml']->$cap->stand1->x;
						$capY = self::mainY + $this->Cap['xml']->$cap->stand1->y;
						if(self::exists("Cap/0".$this->Cap['ID'].".img/default.".$type.".png"))
							self::useImage("Cap/0".$this->Cap['ID'].".img/default.".$type.".png", $capX, $capY);
						 else if(self::exists("Cap/0".$this->Cap['ID'].".img/stand1.0.".$type.".png"))
							 self::useImage("Cap/0".$this->Cap['ID'].".img/stand1.0.".$type.".png", $capX, $capY);
					}
				}
			}
		}
	}

	public function setCape($z) {
	$zArray = array( // Updated
			"cape"			=> array( "cape", "capeArm", "capeOverHead", "capeOverArm" ),
			"backWing"		=> array( "cape"),
			"capeBelowBody"	=> array( "cape", "capeOverArm" ),
			"capOverHair"	=> array( "cape"),
			"capeOverHead"	=> array( "cape", "capeArm", "capeOverHead", "cape3" )
		);

		if (isset($this->Cape['xml'])) {
			foreach( $zArray[$z] as $type ) {
				$cape = "_".$type;

				if (property_exists ($this->Cape['xml'] , $cape )) {
					if ($this->Cape['xml']->$cape->stand1->z == $z) {
						$capeX = self::mainX + $this->Cape['xml']->$cape->stand1->x;
						$capeY = self::neckY + $this->Cape['xml']->$cape->stand1->y;
						self::useImage("Cape/0".$this->Cape['ID'].".img/stand1.0.".$type.".png", $capeX, $capeY);
					}
				}
			}
		}
	}

	public function setShield($z = NULL) {
		if($this->Shield['ID'] < 1090000) {
			self::setWeapon("weaponOverArmBelowHead");
		} else if ($this->Shield['ID'] > 1340000 && $this->Shield['ID'] < 1360000) { // Secondary weapon
			// not handle secondary weaps by this way anymore
		} else if(isset($this->Shield['xml']) && $z == null) {
			$shieldX = self::mainX + $this->Shield['xml']->_shield->stand1->x;
			$shieldY = self::neckY + $this->Shield['xml']->_shield->stand1->y;

			self::useImage("Shield/0".$this->Shield['ID'].".img/stand1.0.shield.png", $shieldX, $shieldY);
		}
	}

	public function setShoes($z) {
		$zArray = array( // Updated
			"shoes"						=> array( "shoes"),
			"weaponOverBody"			=> array( "shoes"),
			"shoesTop"					=> array( "shoes"),
			"shoesOverPants"			=> array( "shoes"),
			"pantsOverMailChest"		=> array( "shoes"),
			"gloveWristBelowMailArm"	=> array( "shoes"),
			"capAccessoryBelowBody"		=> array( "shoesBack" )
		);

		if (isset($this->Shoes['xml'])) {
			foreach( $zArray[$z] as $type ) {
				$shoes = "_".$type;
				if (property_exists ($this->Shoes['xml'] , $shoes )) {
					if ($this->Shoes['xml']->$shoes->_stand1->z == $z) {
						$shoesX = self::mainX + $this->Shoes['xml']->$shoes->_stand1->x;
						$shoesY = self::neckY + $this->Shoes['xml']->$shoes->_stand1->y;
						self::useImage("Shoes/0".$this->Shoes['ID'].".img/stand1.0.".$type.".png", $shoesX, $shoesY);
					}
				}
			}
		}
	}

	public function setGlove($pos, $stand = NULL) {
		$canvasArray = array(
			"r" => array( "stand1" => array( "rGlove", "rWrist", "gloveOverHead" ), "stand2" => array( "rGlove", "rWrist" ) ),
			"l" => array( "stand1" => array( "lGlove", "lWrist", "gloveOverBody" ), "stand2" => array( "lGlove", "lWrist", "gloveOverHand", "lGlove2") )
		);
		if(isset($this->Glove['xml'])) {
			$snd = "_stand".$this->stand;
			$ss  = "stand".$this->stand;
			if(!($pos == 'l' && $stand == 2 && $this->stand == 1)) { // Check for Stand2 Left Glove
				foreach( $canvasArray[$pos][$ss] as $canvas ) {
					if(self::exists("Glove/0".$this->Glove['ID'].".img/".$ss.".0.".$canvas.".png")) {
						$type   = "_".$canvas;
						$gloveX = self::mainX + $this->Glove['xml']->$type->$snd->x;
						$gloveY = self::neckY + $this->Glove['xml']->$type->$snd->y;

						self::useImage("Glove/0".$this->Glove['ID'].".img/".$ss.".0.".$canvas.".png", $gloveX, $gloveY);
					}
				}
			}
		}
	}

	public function setPants() {
		if(!isset($this->Pants['ID'])) {
			self::useImage("Pants/0{$this->default[$this->Gender['ID']]['pants']}.img/stand1.0.pants.png", self::mainX - 3, self::neckY + 1);
		} elseif($this->Coat['ID'] >= 1050000) {
			return NULL;
		} elseif(isset($this->Pants['xml'])) {
			$snd    = "_stand".$this->stand;
			$pantsX = self::mainX + $this->Pants['xml']->_pants->$snd->x;
			$pantsY = self::neckY + $this->Pants['xml']->_pants->$snd->y;
			imagettftext($this->image, 7, 0, 0, 90, $color, "C:\Windows\Fonts\Arial.ttf", $z);
			if(self::exists("Pants/0".$this->Pants['ID'].".img/stand2.0.pants.png") && $this->stand == 2)
				self::useImage("Pants/0".$this->Pants['ID'].".img/stand2.0.pants.png", $pantsX, $pantsY);
			else
				self::useImage("Pants/0".$this->Pants['ID'].".img/stand1.0.pants.png", $pantsX, $pantsY);
		}
	}

	public function setCoat($type) {
		$Location = ($this->Coat['ID'] >= 1050000 ? "Longcoat" : "Coat")."/0".$this->Coat['ID'].".img/";
		if(!isset($this->Coat['ID'])) {
			self::useImage("Coat/0{$this->default[$this->Gender['ID']]['coat']}.img/stand1.0.mail.png", self::mainX - 3, self::neckY - 9);
		} elseif(self::exists($Location."coord.xml")) {
			$xml = self::XMLoader($Location);
			$snd = "stand".$this->stand;

			switch($type) {
				case "mail":
					$mailX = self::mainX + $xml->_mail->$snd->x;
					$mailY = self::neckY + $xml->_mail->$snd->y;

					if(self::exists($Location."stand2.0.mail.png") && $this->stand == 2)
						self::useImage($Location."stand2.0.mail.png", $mailX, $mailY);
					else
						self::useImage($Location."stand1.0.mail.png", $mailX, $mailY);
				break;
				case "mailArm":
					$mailArmX = self::mainX + $xml->_mailArm->$snd->x;
					$mailArmY = self::neckY + $xml->_mailArm->$snd->y;

					self::useImage($Location."stand".$this->stand.".0.mailArm.png", $mailArmX, $mailArmY);
				break;
			}
		}
	}

	public function setWeapon($z) {
		$wepArray = array( // Updated
			"weapon"                    	=> array( "weapon", "effect", "weaponFront" ),
			"weaponBelowArm"            	=> array( "weapon", "weaponBelowArm", "ex" ),
			"weaponBelowBody"           	=> array( "weapon", "weaponBelowBody" ),
			"weaponOverArm"             	=> array( "weapon", "weaponOverArm", "string", "weaponOverGlove", "weaponOverGlve" ),
			"weaponOverArmBelowHead"    	=> array( "weapon", "weaponL" ),
			"weaponOverBody"            	=> array( "weapon", "weaponOverBody", "weaponL" ),
			"weaponOverGlove"           	=> array( "weapon", "belt" ),
			"weaponOverHand"           		=> array( "weapon", "weaponOverHand" ),
			"weaponWristOverGlove"     		=> array( "weapon", "weaponWrist" ),
			"armBelowHeadOverMailChest"		=> array( "weapon" ),
			"gloveWristBelowWeapon" 		=> array( "weapon" ),
			"weaponOverGloveBelowMailArm"	=> array( "weapon" ),
			"backWeapon" 					=> array( "weapon" ),
			"handBelowWeapon" 				=> array( "weaponL" ),
			"characterEnd" 					=> array( "effect" ),
			"emotionOverBody" 				=> array( "effect" ),
		);
		if ($z == "weaponOverArmBelowHead" && ($this->Shield['ID'] > 1340000 && $this->Shield['ID'] < 1350000)) { //Katara, Dualbow
			$Location = "Weapon/0".$this->Shield['ID'].".img/";
			if(self::exists($Location."coord.xml")) {
				$xml = self::XMLoader($Location);
				$snd = "_stand".$xml->_info->stand->value;
				$ss  = "stand".$xml->_info->stand->value; // I'm okay with this 'cause too lazy to recreate the whole GD folder to fix this.
				$shieldX = self::mainX + $xml->_weapon->$snd->x;
				$shieldY = self::neckY + $xml->_weapon->$snd->y;
				self::useImage("Weapon/0".$this->Shield['ID'].".img/".$ss.".0.weapon.png", $shieldX, $shieldY);
			}
		} else if (isset($this->Weapon['xml'])) {
			$snd = "_stand".$this->stand;
			$ss  = "stand".$this->stand; // I'm okay with this 'cause too lazy to recreate the whole GD folder to fix this.
			if($wepNUM = $this->Weapon['xml']->_info->$ss->NUM)
				$wepNUM .= ".";
			foreach( $wepArray[$z] as $type ) {
				$weap = "_".$type;
				if (property_exists ($this->Weapon['xml'] , $weap )) {
					if ($this->Weapon['xml']->$weap->$snd->z == $z) {
						$wepX = self::mainX + $this->Weapon['xml']->$weap->$snd->x;
						$wepY = self::neckY + $this->Weapon['xml']->$weap->$snd->y;
						self::useImage("Weapon/0".$this->Weapon['ID'].".img/".$wepNUM.$ss.".0.".$type.".png", $wepX, $wepY);
					}
				}
			}
		}
	}

	public function createBody($type) {
		$skin = 2000 + $this->Skin['ID'];
		switch($type) {
			case "head":
				self::useImage("Skin/0000".$skin.".img/front.head.png", self::mainX - 15, self::mainY - 12);
			break;
			case "body":
				self::useImage("Skin/0000".$skin.".img/stand{$this->stand}.0.body.png", (self::mainX + $this->stand) - 9, self::mainY + 21);
			break;
			case "arm":
				self::useImage("Skin/0000".$skin.".img/stand{$this->stand}.0.arm.png", self::mainX + ($this->stand==2?4:8), self::mainY + 23);
			break;
			case "hand":
				if($this->stand == 2)
					self::useImage("Skin/0000".$skin.".img/stand2.0.hand.png", self::mainX - 10, self::mainY + 26);
			break;
		}
	}

	public function charType($type,$name) {
		switch($type) {
			case 'create':ImagePNG($this->image, "Characters/".$name.".png");break;
			case 'use':self::useImage("Characters/".$name.".png");break;
		}
	}

	private function useImage($location, $x = 0, $y = 0) {
		if(self::exists($location)) {
			$implace = ImageCreateFromPNG($location);
			ImageCopy($this->image, $implace, $x, $y, 0, 0, imagesx($implace), imagesy($implace));
		}
	}

	public function XMLoader($path) {
		if(self::exists($path)) {
			$this->Load($path."coord.xml");
			return simplexml_import_dom($this);
		}
	}

	public function exists($path) {
		if(file_exists($path))
			return true;
	}

	function __destruct() {
		ImagePng($this->image);
		ImageDestroy($this->image);
	}
}

?>