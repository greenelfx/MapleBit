<?php
#+----------------------------------------------------------+
#|                   Made by HoltHelper                   	|
#|                                                        	|
#| Please give proper credits when using this code since  	|
#| It took me over a couple of months to finish this code 	|
#|                                                        	|
#| Updated by Basis: 										|
#| + Fixed Dual Bow Gun display (Basis)						|
#| + Fixed Knuckler display	(Limecat)						|
#| + Added caching of character image (Limecat)				|
#+----------------------------------------------------------+
class Image {
	//Image Coord Variables
	public $mainX = 44;
	public $mainY = 34;
	public $neckY = 65;
	public $rootfolder;
	
	function __construct() {
		//header('Content-type: image/png');
		$this->image = ImageCreateTrueColor(96, 96);
		ImageSaveAlpha($this->image, true);
		$trans = ImageColorAllocateAlpha($this->image, 0, 0, 0, 127);
		ImageFill($this->image, 0, 0, $trans);
	}
	public function setName($type) {
		return $this->name=$type;
	}
	public function setBG($bg) {
		return $this->bg=$bg;
	}	
	public function setSkin($type) {
		return $this->skin=$type;
	}
	
	public function setStand($type) {
		return $this->stand=$type;
	}
	
	public function setWepNum($type) {
		return $this->wepNum=$type;
	}
	
	public function setFace($face) {
		$location = $this->rootfolder."assets/img/GD/Face/000{$face}.img/index.txt";
		if(file_exists($location)) {
			$faceArray = $this->txt_parse($location);
			$faceX = (-$faceArray[default_face_origin_x]) - $faceArray[default_face_map_brow_x];
			$faceY = (-$faceArray[default_face_origin_y]) - $faceArray[default_face_map_brow_y];
		}
		$location = $this->rootfolder."assets/img/GD/Face/000{$face}.img/default.face.png";
		if(file_exists($location)){
			$implace = ImageCreateFromPNG($location);
			ImageCopy($this->image, $implace, $this->mainX + $faceX, $this->mainY + $faceY, 0, 0, imagesx($implace), imagesy($implace));
		}
    }
	
	public function setHair($hair,$type) {
		$location = $this->rootfolder."assets/img/GD/Hair/000{$hair}.img/index.txt";
		if(file_exists($location)) {
			$this->hair=$hair;
			$hairShade=$this->skin-2000;
			$hairArray = $this->txt_parse($location);
			$hairX = (-$hairArray[default_hair_origin_x]) - $hairArray[default_hair_map_brow_x];
			$hairY = (-$hairArray[default_hair_origin_y]) - $hairArray[default_hair_map_brow_y];
			$this->overHairX = (-$hairArray[default_hairOverHead_origin_x]) - $hairArray[default_hairOverHead_map_brow_x];
			$this->overHairY = (-$hairArray[default_hairOverHead_origin_y]) - $hairArray[default_hairOverHead_map_brow_y];
			$this->backHairX = (-$hairArray[default_hairBelowBody_origin_x]) - $hairArray[default_hairBelowBody_map_brow_x];
			$this->backHairY = (-$hairArray[default_hairBelowBody_origin_y]) - $hairArray[default_hairBelowBody_map_brow_y];
			$shadeHairX = (-$hairArray[default_hairShade_0_origin_x]) - $hairArray[default_hairShade_0_map_brow_x];
			$shadeHairY = (-$hairArray[default_hairShade_0_origin_y]) - $hairArray[default_hairShade_0_map_brow_y];
		}
		switch($type) {
			case "hair":
				if(substr_count($this->vSlot, 'H1H2H3H4H5H6') == 1){
					return NULL;
				} else {
					if(file_exists($this->rootfolder."assets/img/GD/Hair/000{$hair}.img/default.hair.png")) {
						$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Hair/000{$hair}.img/default.hair.png");
						ImageCopy($this->image, $implace, $this->mainX + $hairX, $this->mainY + $hairY, 0, 0, imagesx($implace), imagesy($implace));
					}
				}
			break;
			case "hairShade":
				if(substr_count($this->vSlot, 'H1H2H3H4H5H6') == 1){
					return NULL;
				} else {
					$location = $this->rootfolder."assets/img/GD/Hair/000{$hair}.img/default.hairShade.{$hairShade}.png";
					if(file_exists($location)) {
						$implace = ImageCreateFromPNG($location);
						ImageCopy($this->image, $implace, $this->mainX + $shadeHairX, $this->mainY + $shadeHairY, 0, 0, imagesx($implace), imagesy($implace));
					}
				}
			break;
		}
	}
	
	public function setAccessory($accessory,$type) {
		$location = $this->rootfolder."assets/img/GD/Accessory/0{$accessory}.img/index.txt";
		if(file_exists($location)) {
			$maskArray = $this->txt_parse($location);
			$accessoryX = (-$maskArray[default_default_origin_x]) - $maskArray[default_default_map_brow_x];
			$accessoryY = (-$maskArray[default_default_origin_y]) - $maskArray[default_default_map_brow_y];
			$accessoryZ = $maskArray[default_default_z];
		}
		switch($type) {
			case "accessory":
				if(file_exists($this->rootfolder."assets/img/GD/Accessory/0{$accessory}.img/default.default.png")) {
					$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Accessory/0{$accessory}.img/default.default.png");
					ImageCopy($this->image, $implace, $this->mainX + $accessoryX, $this->mainY + $accessoryY, 0, 0, imagesx($implace), imagesy($implace));
				}
			break;
			case "mask":
				if($accessoryZ == "accessoryFaceBelowFace") {
					$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Accessory/0{$accessory}.img/default.default.png");
					ImageCopy($this->image, $implace, $this->mainX + $accessoryX, $this->mainY + $accessoryY, 0, 0, imagesx($implace), imagesy($implace));
				} elseif($accessoryZ == "accessoryFace") {
					if(file_exists($this->rootfolder."assets/img/GD/Accessory/0{$accessory}.img/default.0.png") && $accessory == "1012008") {
						$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Accessory/0{$accessory}.img/default.0.png");
						ImageCopy($this->image, $implace, $this->mainX + $accessoryX, $this->mainY + $accessoryY, 0, 0, imagesx($implace), imagesy($implace));
					} else {
						$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Accessory/0{$accessory}.img/default.default.png");
						ImageCopy($this->image, $implace, $this->mainX + $accessoryX, $this->mainY + $accessoryY, 0, 0, imagesx($implace), imagesy($implace));
					}
				}
			break;
		}
	}
	
	public function setHat($hat,$type) {
		$location = $this->rootfolder."assets/img/GD/Cap/0{$hat}.img/index.txt";
		if(file_exists($location)){
			$hatArray = $this->txt_parse($location);
			$hatX = (-$hatArray[default_default_origin_x]) - $hatArray[default_default_map_brow_x];
			$hatY = (-$hatArray[default_default_origin_y]) - $hatArray[default_default_map_brow_y];
			$acHatX = (-$hatArray[default_defaultAc_origin_x]) - $hatArray[default_defaultAc_map_brow_x];
			$acHatY = (-$hatArray[default_defaultAc_origin_y]) - $hatArray[default_defaultAc_map_brow_y];
			$this->vSlot = $hatArray[info_vslot];
		}
		switch($type) {
			case "acHat":
				$location = $this->rootfolder."assets/img/GD/Cap/0{$hat}.img/default.defaultAc.png";
				if(file_exists($location)) {
					$implace = ImageCreateFromPNG($location);
					ImageCopy($this->image, $implace, $this->mainX + $acHatX, $this->mainY + $acHatY, 0, 0, imagesx($implace), imagesy($implace));
				}
			break;
			case "regular":
				if($hat) {
					if($this->vSlot == "CpH5"){
						if(file_exists($this->rootfolder."assets/img/GD/Hair/000{$this->hair}.img/default.hairOverHead.png")) {
							$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Hair/000{$this->hair}.img/default.hairOverHead.png");
							ImageCopy($this->image, $implace, $this->mainX + $this->overHairX, $this->mainY + $this->overHairY, 0, 0, imagesx($implace), imagesy($implace));
						}
					}
					if(file_exists($this->rootfolder."assets/img/GD/Cap/0{$hat}.img/stand{$this->stand}.0.png")) {
						$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Cap/0{$hat}.img/stand{$this->stand}.0.png");
						ImageCopy($this->image, $implace, $this->mainX + $hatX, $this->mainY + $hatY, 0, 0, imagesx($implace), imagesy($implace));
					} elseif(file_exists($this->rootfolder."assets/img/GD/Cap/0{$hat}.img/default.default.png")) {
						$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Cap/0{$hat}.img/default.default.png");
						ImageCopy($this->image, $implace, $this->mainX + $hatX, $this->mainY + $hatY, 0, 0, imagesx($implace), imagesy($implace));
					}
				} else {
					if(file_exists($this->rootfolder."assets/img/GD/Hair/000{$this->hair}.img/default.hairOverHead.png")) {
						$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Hair/000{$this->hair}.img/default.hairOverHead.png");
						ImageCopy($this->image, $implace, $this->mainX + $this->overHairX, $this->mainY + $this->overHairY, 0, 0, imagesx($implace), imagesy($implace));
					}
				}
			break;
		}
	}
	
	public function setCape($cape,$type) {
		$location = $this->rootfolder."assets/img/GD/Cape/0{$cape}.img/index.txt";
		if(file_exists($location)) {
			$capeArray = $this->txt_parse($location);
			$capeX = (-$capeArray[stand1_0_cape_origin_x]) - $capeArray[stand1_0_cape_map_navel_x];
			$capeY = (-$capeArray[stand1_0_cape_origin_y]) - $capeArray[stand1_0_cape_map_navel_y];
			$capeArmX = (-$capeArray[stand1_0_capeArm_origin_x]) - $capeArray[stand1_0_capeArm_map_navel_x];
			$capeArmY = (-$capeArray[stand1_0_capeArm_origin_y]) - $capeArray[stand1_0_capeArm_map_navel_y];
			$capeZ = $capeArray[stand1_0_cape_z];
		}
		switch($type) {
			case "cape":
				if($capeZ == 'capeBelowBody') {
					if(substr_count($this->vSlot, 'H1H2H3H4H5H6') == 1){
						return NULL;
					} else {
						$location = $this->rootfolder."assets/img/GD/Hair/000{$this->hair}.img/default.hairBelowBody.png";
						if(file_exists($location)) {
							$implace = ImageCreateFromPNG($location);
							ImageCopy($this->image, $implace, $this->mainX + $this->backHairX, $this->mainY + $this->backHairY, 0, 0, imagesx($implace), imagesy($implace));
						}
					}
					if(file_exists($this->rootfolder."assets/img/GD/Cape/0{$cape}.img/stand2.0.cape.png") && $this->stand == 2) {
						$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Cape/0{$cape}.img/stand2.0.cape.png");
						ImageCopy($this->image, $implace, $this->mainX + $capeX, $this->neckY + $capeY, 0, 0, imagesx($implace), imagesy($implace));
					} elseif(file_exists($this->rootfolder."assets/img/GD/Cape/0{$cape}.img/stand1.0.cape.png")) {
						$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Cape/0{$cape}.img/stand1.0.cape.png");
						ImageCopy($this->image, $implace, $this->mainX + $capeX, $this->neckY + $capeY, 0, 0, imagesx($implace), imagesy($implace));
					}
				} else {
					if(file_exists($this->rootfolder."assets/img/GD/Cape/0{$cape}.img/stand2.0.cape.png") && $this->stand == 2) {
						$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Cape/0{$cape}.img/stand2.0.cape.png");
						ImageCopy($this->image, $implace, $this->mainX + $capeX, $this->neckY + $capeY, 0, 0, imagesx($implace), imagesy($implace));
					} elseif(file_exists($this->rootfolder."assets/img/GD/Cape/0{$cape}.img/stand1.0.cape.png")) {
						$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Cape/0{$cape}.img/stand1.0.cape.png");
						ImageCopy($this->image, $implace, $this->mainX + $capeX, $this->neckY + $capeY, 0, 0, imagesx($implace), imagesy($implace));
					}
					if(substr_count($this->vSlot, 'H1H2H3H4H5H6') == 1){
						return NULL;
					} else {
						$location = $this->rootfolder."assets/img/GD/Hair/000{$this->hair}.img/default.hairBelowBody.png";
						if(file_exists($location)) {
							$implace = ImageCreateFromPNG($location);
							ImageCopy($this->image, $implace, $this->mainX + $this->backHairX, $this->mainY + $this->backHairY, 0, 0, imagesx($implace), imagesy($implace));
						}
					}
				}
			break;
			case "capeArm":
				if(file_exists($this->rootfolder."assets/img/GD/Cape/0{$cape}.img/stand2.0.capeArm.png") && $this->stand == 2) {
					$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Cape/0{$cape}.img/stand2.0.capeArm.png");
					ImageCopy($this->image, $implace, $this->mainX + $capeArmX, $this->neckY + $capeArmY, 0, 0, imagesx($implace), imagesy($implace));
				} elseif(file_exists($this->rootfolder."assets/img/GD/Cape/0{$cape}.img/stand1.0.capeArm.png")) {
					$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Cape/0{$cape}.img/stand1.0.capeArm.png");
					ImageCopy($this->image, $implace, $this->mainX + $capeArmX, $this->neckY + $capeArmY, 0, 0, imagesx($implace), imagesy($implace));
				}
			break;
		}
	}
	
	public function setShield($shield) {
		$location = $this->rootfolder."assets/img/GD/Shield/0{$shield}.img/index.txt";
		if(file_exists($location)) {
			$shieldArray = $this->txt_parse($location);
			$shieldX = (-$shieldArray[stand1_0_shield_origin_x]) - $shieldArray[stand1_0_shield_map_navel_x];
			$shieldY = (-$shieldArray[stand1_0_shield_origin_y]) - $shieldArray[stand1_0_shield_map_navel_y];
		}
		$location = $this->rootfolder."assets/img/GD/Shield/0{$shield}.img/stand1.0.shield.png";
		if(file_exists($location)) {
			$implace = ImageCreateFromPNG($location);
			ImageCopy($this->image, $implace, $this->mainX + $shieldX, $this->neckY + $shieldY, 0, 0, imagesx($implace), imagesy($implace));
		}
	}
	
	public function setShoes($shoes) {
		$location = $this->rootfolder."assets/img/GD/Shoes/0{$shoes}.img/index.txt";
		if(file_exists($location)) {
			$shoesArray = $this->txt_parse($location);
			$shoesX = (-$shoesArray[stand1_0_shoes_origin_x]) - $shoesArray[stand1_0_shoes_map_navel_x];
			$shoesY = (-$shoesArray[stand1_0_shoes_origin_y]) - $shoesArray[stand1_0_shoes_map_navel_y];
		}
		$location = $this->rootfolder."assets/img/GD/Shoes/0{$shoes}.img/stand1.0.shoes.png";
		if(file_exists($location)) {
			$implace = ImageCreateFromPNG($location);
			ImageCopy($this->image, $implace, $this->mainX + $shoesX, $this->neckY + $shoesY, 0, 0, imagesx($implace), imagesy($implace));
		}
	}
	
	public function setGlove($glove,$type) {
		$location = $this->rootfolder."assets/img/GD/Glove/0{$glove}.img/index.txt";
		if(file_exists($location)) {
			$gloveArray = $this->txt_parse($location);
			$LgloveX = (-$gloveArray['stand'.$this->stand.'_0_lGlove_origin_x']) - $gloveArray['stand'.$this->stand.'_0_lGlove_map_navel_x'];
			$LgloveY = (-$gloveArray['stand'.$this->stand.'_0_lGlove_origin_y']) - $gloveArray['stand'.$this->stand.'_0_lGlove_map_navel_y'];
			$RgloveX = (-$gloveArray['stand'.$this->stand.'_0_rGlove_origin_x']) - $gloveArray['stand'.$this->stand.'_0_rGlove_map_navel_x'];
			$RgloveY = (-$gloveArray['stand'.$this->stand.'_0_rGlove_origin_y']) - $gloveArray['stand'.$this->stand.'_0_rGlove_map_navel_y'];
			$LGloveX = (-$gloveArray[stand1_0_gloveOverBody_origin_x]) - $gloveArray[stand1_0_gloveOverBody_map_navel_x];
			$LGloveY = (-$gloveArray[stand1_0_gloveOverBody_origin_y]) - $gloveArray[stand1_0_gloveOverBody_map_navel_y];
			$RGloveX = (-$gloveArray[stand1_0_gloveOverHead_origin_x]) - $gloveArray[stand1_0_gloveOverHead_map_navel_x];
			$RGloveY = (-$gloveArray[stand1_0_gloveOverHead_origin_y]) - $gloveArray[stand1_0_gloveOverHead_map_navel_y];
			$MgloveX = (-$gloveArray[stand2_0_gloveOverHand_origin_x]) - $gloveArray[stand2_0_gloveOverHand_map_navel_x];
			$MgloveY = (-$gloveArray[stand2_0_gloveOverHand_origin_y]) - $gloveArray[stand2_0_gloveOverHand_map_navel_y];
		}
		switch($type) {
			case "leftGlove":
				if(file_exists($this->rootfolder."assets/img/GD/Glove/0{$glove}.img/stand{$this->stand}.0.gloveOverBody.png")) {
					$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Glove/0{$glove}.img/stand{$this->stand}.0.gloveOverBody.png");
					ImageCopy($this->image, $implace, $this->mainX + $LGloveX, $this->neckY + $LGloveY, 0, 0, imagesx($implace), imagesy($implace));
				} elseif(file_exists($this->rootfolder."assets/img/GD/Glove/0{$glove}.img/stand{$this->stand}.0.lGlove.png")) {
					$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Glove/0{$glove}.img/stand{$this->stand}.0.lGlove.png");
					ImageCopy($this->image, $implace, $this->mainX + $LgloveX, $this->neckY + $LgloveY, 0, 0, imagesx($implace), imagesy($implace));
				}
			break;
			case "rightGlove":
				if(file_exists($this->rootfolder."assets/img/GD/Glove/0{$glove}.img/stand{$this->stand}.0.gloveOverHead.png")) {
					$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Glove/0{$glove}.img/stand{$this->stand}.0.gloveOverHead.png");
					ImageCopy($this->image, $implace, $this->mainX + $RGloveX, $this->neckY + $RGloveY, 0, 0, imagesx($implace), imagesy($implace));
				} elseif(file_exists($this->rootfolder."assets/img/GD/Glove/0{$glove}.img/stand{$this->stand}.0.rGlove.png")) {
					$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Glove/0{$glove}.img/stand{$this->stand}.0.rGlove.png");
					ImageCopy($this->image, $implace, $this->mainX + $RgloveX, $this->neckY + $RgloveY, 0, 0, imagesx($implace), imagesy($implace));
				}
			break;
			case "middleGlove":
				$location = $this->rootfolder."assets/img/GD/Glove/0{$glove}.img/stand{$this->stand}.0.gloveOverHand.png";
				if(file_exists($location)) {
					$implace = ImageCreateFromPNG($location);
					ImageCopy($this->image, $implace, $this->mainX + $MgloveX, $this->neckY + $MgloveY, 0, 0, imagesx($implace), imagesy($implace));
				}
			break;
		}
	}
	
	public function setPants($pants) {
		$location = $this->rootfolder."assets/img/GD/Pants/0{$pants}.img/index.txt";
		if(file_exists($location)) {
			$pantsArray = $this->txt_parse($location);
			$pantsX = (-$pantsArray[stand1_0_pants_origin_x]) - $pantsArray[stand1_0_pants_map_navel_x];
			$pantsY = (-$pantsArray[stand1_0_pants_origin_y]) - $pantsArray[stand1_0_pants_map_navel_y];
		}
		if(!($pants || $this->overAll)) {
			$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Pants/01060026.img/stand1.0.pants.png");
			ImageCopy($this->image, $implace, $this->mainX - 3, $this->neckY + 1, 0, 0, imagesx($implace), imagesy($implace));
		} elseif(file_exists($this->rootfolder."assets/img/GD/Pants/0{$pants}.img/stand2.0.pants.png") && $this->stand == 2) {
			$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Pants/0{$pants}.img/stand2.0.pants.png");
			ImageCopy($this->image, $implace, $this->mainX + $pantsX, $this->neckY + $pantsY, 0, 0, imagesx($implace), imagesy($implace));
		} elseif(file_exists($this->rootfolder."assets/img/GD/Pants/0{$pants}.img/stand1.0.pants.png")) {
			$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Pants/0{$pants}.img/stand1.0.pants.png");
			ImageCopy($this->image, $implace, $this->mainX + $pantsX, $this->neckY + $pantsY, 0, 0, imagesx($implace), imagesy($implace));
		}
	}
	
	public function setTop($top,$type) {
		$location = $this->rootfolder."assets/img/GD/Coat/0{$top}.img/index.txt";
		if(file_exists($location)) {
			$mailArray = $this->txt_parse($location);
			$mailX = (-$mailArray['stand'.$this->stand.'_0_mail_origin_x']) - $mailArray['stand'.$this->stand.'_0_mail_map_navel_x'];
			$mailY = (-$mailArray['stand'.$this->stand.'_0_mail_origin_y']) - $mailArray['stand'.$this->stand.'_0_mail_map_navel_y'];
			$mailArmX = (-$mailArray['stand'.$this->stand.'_0_mailArm_origin_x']) - $mailArray['stand'.$this->stand.'_0_mailArm_map_navel_x'];
			$mailArmY = (-$mailArray['stand'.$this->stand.'_0_mailArm_origin_y']) - $mailArray['stand'.$this->stand.'_0_mailArm_map_navel_y'];
		}
		switch($type) {
			case "top":
				if(!($top || $this->overAll) && file_exists($this->rootfolder."assets/img/GD/Coat/01040036.img/stand1.0.mail.png")) {
					$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Coat/01040036.img/stand1.0.mail.png");
					ImageCopy($this->image, $implace, $this->mainX - 3, $this->neckY - 9, 0, 0, imagesx($implace), imagesy($implace));
				} elseif(file_exists($this->rootfolder."assets/img/GD/Coat/0{$top}.img/stand2.0.mail.png") && $this->stand == 2) {
					$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Coat/0{$top}.img/stand2.0.mail.png");
					ImageCopy($this->image, $implace, $this->mainX + $mailX, $this->neckY + $mailY, 0, 0, imagesx($implace), imagesy($implace));
				} elseif(file_exists($this->rootfolder."assets/img/GD/Coat/0{$top}.img/stand1.0.mail.png")) {
					$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Coat/0{$top}.img/stand1.0.mail.png");
					ImageCopy($this->image, $implace, $this->mainX + $mailX, $this->neckY + $mailY, 0, 0, imagesx($implace), imagesy($implace));
				}
			break;
			case "topArm":
				$location = $this->rootfolder."assets/img/GD/Coat/0{$top}.img/stand{$this->stand}.0.mailArm.png";
				if(file_exists($location)) {
					$implace = ImageCreateFromPNG($location);
					ImageCopy($this->image, $implace, $this->mainX + $mailArmX, $this->neckY + $mailArmY, 0, 0, imagesx($implace), imagesy($implace));
				}
			break;
		}
	}
	
	public function setOverAll($overAll,$type) {
		$location = $this->rootfolder."assets/img/GD/Longcoat/0{$overAll}.img/index.txt";
		if(file_exists($location)) {
			$mailArray = $this->txt_parse($location);
			$mailX = (-$mailArray['stand'.$this->stand.'_0_mail_origin_x']) - $mailArray['stand'.$this->stand.'_0_mail_map_navel_x'];
			$mailY = (-$mailArray['stand'.$this->stand.'_0_mail_origin_y']) - $mailArray['stand'.$this->stand.'_0_mail_map_navel_y'];
			$mailArmX = (-$mailArray['stand'.$this->stand.'_0_mailArm_origin_x']) - $mailArray['stand'.$this->stand.'_0_mailArm_map_navel_x'];
			$mailArmY = (-$mailArray['stand'.$this->stand.'_0_mailArm_origin_y']) - $mailArray['stand'.$this->stand.'_0_mailArm_map_navel_y'];
		}
		switch($type) {
			case "overall":
				if(file_exists($this->rootfolder."assets/img/GD/Longcoat/0{$overAll}.img/stand2.0.mail.png") && $this->stand == 2) {
					$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Longcoat/0{$overAll}.img/stand2.0.mail.png");
					ImageCopy($this->image, $implace, $this->mainX + $mailX, $this->neckY + $mailY, 0, 0, imagesx($implace), imagesy($implace));
				} elseif(file_exists($this->rootfolder."assets/img/GD/Longcoat/0{$overAll}.img/stand1.0.mail.png")) {
					$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Longcoat/0{$overAll}.img/stand1.0.mail.png");
					ImageCopy($this->image, $implace, $this->mainX + $mailX, $this->neckY + $mailY, 0, 0, imagesx($implace), imagesy($implace));
				}
			break;
			case "overallArm":
				$location = $this->rootfolder."assets/img/GD/Longcoat/0{$overAll}.img/stand{$this->stand}.0.mailArm.png";
				if(file_exists($location)) {
					$implace = ImageCreateFromPNG($location);
					ImageCopy($this->image, $implace, $this->mainX + $mailArmX, $this->neckY + $mailArmY, 0, 0, imagesx($implace), imagesy($implace));
				}
			break;
		}
	}
	
	public function setWeapon($weapon,$type) {
		$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/index.txt";
		if(file_exists($location)) {
			$weaponArray = $this->txt_parse($location);
			$wepX = $this->mainX + 12;
			$wepY = $this->neckY + 6;
			if($this->wepNum) {
				if($weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weapon_map_hand_x'] != NULL) {
					$position = 'hand';
				} else {
					$position = 'navel';
					$wepX = $this->mainX;
					$wepY = $this->neckY;
				}
				$weaponX = (-$weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weapon_origin_x']) - $weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weapon_map_'.$position.'_x'];
				$weaponY = (-$weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weapon_origin_y']) - $weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weapon_map_'.$position.'_y'];
				$weaponZ = $weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weapon_z'];
			} else {
				if($weaponArray['stand'.$this->stand.'_0_weapon_map_hand_x'] != NULL) {
					$position = 'hand';
				} else {
					$position = 'navel';
					$wepX = $this->mainX;
					$wepY = $this->neckY;
				}
				$weaponX = (-$weaponArray['stand'.$this->stand.'_0_weapon_origin_x']) - $weaponArray['stand'.$this->stand.'_0_weapon_map_'.$position.'_x'];
				$weaponY = (-$weaponArray['stand'.$this->stand.'_0_weapon_origin_y']) - $weaponArray['stand'.$this->stand.'_0_weapon_map_'.$position.'_y'];
				$weaponZ = $weaponArray['stand'.$this->stand.'_0_weapon_z'];
				$wristWepX = (-$weaponArray[stand1_0_weaponWrist_origin_x]) - $weaponArray[stand1_0_weaponWrist_map_hand_x];
				$wristWepY = (-$weaponArray[stand1_0_weaponWrist_origin_y]) - $weaponArray[stand1_0_weaponWrist_map_hand_y];
			}
			if($this->stand == 2 && $position == 'hand') {
				$wepX -= 7;
				$wepY -= 7;
			}
		}
		switch($type) {
			case "weaponBelowBody":
				if($weaponZ == 'weaponBelowBody') {
					if($this->wepNum) {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/{$this->wepNum}.stand{$this->stand}.0.weapon.png";
					} else {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/stand{$this->stand}.0.weapon.png";
					}
					if(file_exists($location)) {
						$implace = ImageCreateFromPNG($location);
						ImageCopy($this->image, $implace, $wepX + $weaponX, $wepY + $weaponY, 0, 0, imagesx($implace), imagesy($implace));
					}
				}
			break;
			case "weaponBelowArm":
				if($weaponZ == 'weaponBelowArm') {
					if($this->wepNum) {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/{$this->wepNum}.stand{$this->stand}.0.weapon.png";
					} else {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/stand{$this->stand}.0.weapon.png";
					}
					if(file_exists($location)) {
						$implace = ImageCreateFromPNG($location);
						ImageCopy($this->image, $implace, $wepX + $weaponX, $wepY + $weaponY, 0, 0, imagesx($implace), imagesy($implace));
					}
				}
			break;
			case "armLOverMailChest":
				if($weaponZ == 'armLOverMailChest') {
					if($this->wepNum) {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/{$this->wepNum}.stand{$this->stand}.0.weapon.png";
					} else {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/stand{$this->stand}.0.weapon.png";
					}
					if(file_exists($location)) {
						$implace = ImageCreateFromPNG($location);
						ImageCopy($this->image, $implace, $wepX + $weaponX, $wepY + $weaponY, 0, 0, imagesx($implace), imagesy($implace));
					}
				}
			break;
			case "weaponOverArm":
				if($weaponZ == 'weaponOverArm') {
					if($this->wepNum) {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/{$this->wepNum}.stand{$this->stand}.0.weapon.png";
					} else {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/stand{$this->stand}.0.weapon.png";
					}
					if(file_exists($location)) {
						$implace = ImageCreateFromPNG($location);
						ImageCopy($this->image, $implace, $wepX + $weaponX, $wepY + $weaponY, 0, 0, imagesx($implace), imagesy($implace));
					}
				}
			case "weaponOverBody":
				if($weaponZ == 'weaponOverBody') {
					if($this->wepNum) {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/{$this->wepNum}.stand{$this->stand}.0.weapon.png";
					} else {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/stand{$this->stand}.0.weapon.png";
					}
					if(file_exists($location)) {
						$implace = ImageCreateFromPNG($location);
						ImageCopy($this->image, $implace, $wepX + $weaponX, $wepY + $weaponY, 0, 0, imagesx($implace), imagesy($implace));
					}
				}
			break;
			case "weapon":
				if($weaponZ == 'weapon') {
					if($this->wepNum) {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/{$this->wepNum}.stand{$this->stand}.0.weapon.png";
					} else {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/stand{$this->stand}.0.weapon.png";
					}
					if(file_exists($location)) {
						$implace = ImageCreateFromPNG($location);
						ImageCopy($this->image, $implace, $wepX + $weaponX, $wepY + $weaponY, 0, 0, imagesx($implace), imagesy($implace));
					}
				}
			break;
			case "weaponOverGlove":
				if($weaponZ == 'weaponOverGlove') {
					if($this->wepNum) {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/{$this->wepNum}.stand{$this->stand}.0.weapon.png";
					} else {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/stand{$this->stand}.0.weapon.png";
					}
					if(file_exists($location)) {
						$implace = ImageCreateFromPNG($location);
						ImageCopy($this->image, $implace, $wepX + $weaponX, $wepY + $weaponY, 0, 0, imagesx($implace), imagesy($implace));
					}
					if(isset($wristWepX) && isset($wristWepY)) {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/stand1.0.weaponWrist.png";
						if(file_exists($location)) {
							$implace = ImageCreateFromPNG($location);
							ImageCopy($this->image, $implace, $wepX + $wristWepX, $this->neckY + $wristWepY, 0, 0, imagesx($implace), imagesy($implace));
						}
					}
				}
			break;
			case "weaponOverHand":
				if($weaponZ == 'weaponOverHand') {
					if($this->wepNum) {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/{$this->wepNum}.stand{$this->stand}.0.weapon.png";
					} else {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/stand{$this->stand}.0.weapon.png";
					}
					if(file_exists($location)) {
						$implace = ImageCreateFromPNG($location);
						ImageCopy($this->image, $implace, $wepX + $weaponX, $wepY + $weaponY, 0, 0, imagesx($implace), imagesy($implace));
					}
				}
			break;
		}
	}
	public function setKnuckler($weapon,$type) {
		$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/index.txt";
		if(file_exists($location)) {
			$weaponArray = $this->txt_parse($location);
			$wepX = $this->mainX + 12;
			$wepY = $this->neckY + 6;
			if($this->wepNum) {
				if($weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponOverBody_map_hand_x'] != NULL) {
					$positionOverBody = 'hand';
				} else {
					$positionOverBody = 'navel';
					$wepXOverBody = $this->mainX;
					$wepYOverBody = $this->neckY;
				}
				if($weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponOverArm_map_hand_x'] != NULL) {
					$positionOverArm = 'hand';
				} else {
					$positionOverArm = 'navel';
					$wepXOverArm = $this->mainX;
					$wepYOverArm = $this->neckY;
				}
				$weaponOverBodyX = (-$weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponOverBody_origin_x']) - $weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponOverBody_map_'.$positionOverBody.'_x'];
				$weaponOverBodyY = (-$weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponOverBody_origin_y']) - $weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponOverBody_map_'.$positionOverBody.'_y'];
				$weaponOverBodyZ = $weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponOverBody_z'];
				$weaponOverArmX = (-$weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponOverArm_origin_x']) - $weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponOverArm_map_'.$positionOverArm.'_x'];
				$weaponOverArmY = (-$weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponOverArm_origin_y']) - $weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponOverArm_map_'.$positionOverArm.'_y'];
				$weaponOverArmZ = $weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponOverArm_z'];
			} else {
				if($weaponArray['stand'.$this->stand.'_0_weaponOverBody_map_hand_x'] != NULL) {
					$positionOverBody = 'hand';
				} else {
					$positionOverBody = 'navel';
					$wepXOverBody = $this->mainX;
					$wepYOverBody = $this->neckY;
				}
				if($weaponArray['stand'.$this->stand.'_0_weaponOverArm_map_hand_x'] != NULL) {
					$positionOverArm = 'hand';
				} else {
					$positionOverArm = 'navel';
					$wepXOverArm = $this->mainX;
					$wepYOverArm = $this->neckY;
				}
				$weaponOverBodyX = (-$weaponArray['stand'.$this->stand.'_0_weaponOverBody_origin_x']) - $weaponArray['stand'.$this->stand.'_0_weaponOverBody_map_'.$positionOverBody.'_x'];
				$weaponOverBodyY = (-$weaponArray['stand'.$this->stand.'_0_weaponOverBody_origin_y']) - $weaponArray['stand'.$this->stand.'_0_weaponOverBody_map_'.$positionOverBody.'_y'];
				$weaponOverBodyZ = $weaponArray['stand'.$this->stand.'_0_weaponOverBody_z'];
				$weaponOverArmX = (-$weaponArray['stand'.$this->stand.'_0_weaponOverArm_origin_x']) - $weaponArray['stand'.$this->stand.'_0_weaponOverArm_map_'.$positionOverArm.'_x'];
				$weaponOverArmY = (-$weaponArray['stand'.$this->stand.'_0_weaponOverArm_origin_y']) - $weaponArray['stand'.$this->stand.'_0_weaponOverArm_map_'.$positionOverArm.'_y'];
				$weaponOverArmZ = $weaponArray['stand'.$this->stand.'_0_weaponOverArm_z'];
				$wristWepX = (-$weaponArray[stand1_0_weaponWrist_origin_x]) - $weaponArray[stand1_0_weaponWrist_map_hand_x];
				$wristWepY = (-$weaponArray[stand1_0_weaponWrist_origin_y]) - $weaponArray[stand1_0_weaponWrist_map_hand_y];
			}
			if($this->stand == 2 && $positionOverBody == 'hand') {
				$wepXOverBody -= 7;
				$wepYOverBody -= 7;
			}
			if($this->stand == 2 && $positionOverArm == 'hand') {
				$wepXOverArm -= 7;
				$wepYOverArm -= 7;
			}
		}
		switch($type) {
			case "weaponOverBody":
				if($weaponOverBodyZ == 'weaponOverBody') {
					if($this->wepNum) {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/{$this->wepNum}.stand{$this->stand}.0.weaponOverBody.png";
					} else {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/stand{$this->stand}.0.weaponOverBody.png";
					}
					if(file_exists($location)) {
						$implace = ImageCreateFromPNG($location);
						ImageCopy($this->image, $implace, $wepXOverBody + $weaponOverBodyX, $wepYOverBody + $weaponOverBodyY, 0, 0, imagesx($implace), imagesy($implace));
					}
				}
			break;
			case "weaponOverArm":
				if($weaponOverArmZ == 'weaponOverArm') {
					if($this->wepNum) {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/{$this->wepNum}.stand{$this->stand}.0.weaponOverArm.png";
					} else {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/stand{$this->stand}.0.weaponOverArm.png";
					}
					if(file_exists($location)) {
						$implace = ImageCreateFromPNG($location);
						ImageCopy($this->image, $implace, $wepXOverArm + $weaponOverArmX, $wepYOverArm + $weaponOverArmY, 0, 0, imagesx($implace), imagesy($implace));
					}
				}
			break;
			}
	}
	public function setDualGun($weapon,$type) {
		$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/index.txt";
		if(file_exists($location)) {
			$weaponArray = $this->txt_parse($location);
			$wepX = $this->mainX + 12;
			$wepY = $this->neckY + 6;
			if($this->wepNum) {
				if($weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponR_map_hand_x'] != NULL) {
					$positionR = 'hand';
				} else {
					$positionR = 'navel';
					$wepXR = $this->mainX;
					$wepYR = $this->neckY;
				}
				if($weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponL_map_hand_x'] != NULL) {
					$positionL = 'hand';
				} else {
					$positionL = 'navel';
					$wepXL = $this->mainX;
					$wepYL = $this->neckY;
				}
				$weaponRX = (-$weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponR_origin_x']) - $weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponR_map_'.$positionR.'_x'];
				$weaponRY = (-$weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponR_origin_y']) - $weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponR_map_'.$positionR.'_y'];
				$weaponRZ = $weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponR_z'];
				$weaponLX = (-$weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponL_origin_x']) - $weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponL_map_'.$positionL.'_x'];
				$weaponLY = (-$weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponL_origin_y']) - $weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponL_map_'.$positionL.'_y'];
				$weaponLZ = $weaponArray[$this->wepNum.'_stand'.$this->stand.'_0_weaponL_z'];
			} else {
				if($weaponArray['stand'.$this->stand.'_0_weaponR_map_hand_x'] != NULL) {
					$positionR = 'hand';
				} else {
					$positionR = 'navel';
					$wepXR = $this->mainX;
					$wepYR = $this->neckY;
				}
				if($weaponArray['stand'.$this->stand.'_0_weaponL_map_hand_x'] != NULL) {
					$positionL = 'hand';
				} else {
					$positionL = 'navel';
					$wepXL = $this->mainX;
					$wepYL = $this->neckY;
				}
				$weaponRX = (-$weaponArray['stand'.$this->stand.'_0_weaponR_origin_x']) - $weaponArray['stand'.$this->stand.'_0_weaponR_map_'.$positionR.'_x'];
				$weaponRY = (-$weaponArray['stand'.$this->stand.'_0_weaponR_origin_y']) - $weaponArray['stand'.$this->stand.'_0_weaponR_map_'.$positionR.'_y'];
				$weaponRZ = $weaponArray['stand'.$this->stand.'_0_weaponR_z'];
				$weaponLX = (-$weaponArray['stand'.$this->stand.'_0_weaponL_origin_x']) - $weaponArray['stand'.$this->stand.'_0_weaponL_map_'.$positionL.'_x'];
				$weaponLY = (-$weaponArray['stand'.$this->stand.'_0_weaponL_origin_y']) - $weaponArray['stand'.$this->stand.'_0_weaponL_map_'.$positionL.'_y'];
				$weaponLZ = $weaponArray['stand'.$this->stand.'_0_weaponL_z'];
				$wristWepX = (-$weaponArray[stand1_0_weaponWrist_origin_x]) - $weaponArray[stand1_0_weaponWrist_map_hand_x];
				$wristWepY = (-$weaponArray[stand1_0_weaponWrist_origin_y]) - $weaponArray[stand1_0_weaponWrist_map_hand_y];
			}
			if($this->stand == 2 && $positionR == 'hand') {
				$wepXR -= 7;
				$wepYR -= 7;
			}
			if($this->stand == 2 && $positionL == 'hand') {
				$wepXL -= 7;
				$wepYL -= 7;
			}
		}
		switch($type) {
			case "weaponR":
				if($weaponRZ == 'weaponR') {
					if($this->wepNum) {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/{$this->wepNum}.stand{$this->stand}.0.weapon.png";
					} else {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/stand{$this->stand}.0.weapon.png";
					}
					if(file_exists($location)) {
						$implace = ImageCreateFromPNG($location);
						ImageCopy($this->image, $implace, $wepXR + $weaponRX, $wepYR + $weaponRY, 0, 0, imagesx($implace), imagesy($implace));
					}
				}
			break;
			case "weaponL":
				if($weaponLZ == 'weaponL') {
					if($this->wepNum) {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/{$this->wepNum}.stand{$this->stand}.0.weaponL.png";
					} else {
						$location = $this->rootfolder."assets/img/GD/Weapon/0{$weapon}.img/stand{$this->stand}.0.weaponL.png";
					}
					if(file_exists($location)) {
						$implace = ImageCreateFromPNG($location);
						ImageCopy($this->image, $implace, $wepXL + $weaponLX, $wepYL + $weaponLY, 0, 0, imagesx($implace), imagesy($implace));
					}
				}
			break;
			}
	}
	public function createBody($type) {
		switch($type) {
			case "head":
				$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Skin/0000{$this->skin}.img/front.head.png");
				return ImageCopy($this->image, $implace, $this->mainX - 15, $this->mainY - 12, 0, 0, imagesx($implace), imagesy($implace));
			break;
			case "mercedesEars":
				$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Skin/0000{$this->skin}.img/front.ear.png");
				return ImageCopy($this->image, $implace, $this->mainX - 19, $this->mainY + 6, 0, 0, imagesx($implace), imagesy($implace));
			break;
			case "body":
				$implace = ImageCreateFromPNG($this->rootfolder."assets/img/GD/Skin/0000{$this->skin}.img/stand{$this->stand}.0.body.png");
				return ImageCopy($this->image, $implace, ($this->mainX + $this->stand) - 9, $this->mainY + 21, 0, 0, imagesx($implace), imagesy($implace));
			break;
			case "arm":
				$location = $this->rootfolder."assets/img/GD/Skin/0000{$this->skin}.img/stand{$this->stand}.0.arm.png";
				if(file_exists($location)) {
					$implace = ImageCreateFromPNG($location);
					ImageCopy($this->image, $implace, $this->mainX + ($this->stand==2?4:8), $this->mainY + 23, 0, 0, imagesx($implace), imagesy($implace));
				}
			break;
			case "hand":
				$location = $this->rootfolder."assets/img/GD/Skin/0000{$this->skin}.img/stand2.0.hand.png";
				if(file_exists($location)) {
					$implace = ImageCreateFromPNG($location);
					ImageCopy($this->image, $implace, $this->mainX - 10, $this->mainY + 26, 0, 0, imagesx($implace), imagesy($implace));
				}
			break;
		}
	}
	
	# Thanks Darkmagic
	public function txt_parse($file) {
		$filehandle = fopen($file,"r");
		$filecontent = fread($filehandle, filesize($file));
		fclose($filehandle);
		$filecontent = preg_replace("/ /","=",$filecontent);
		$filecontent = preg_replace("/\./","_",$filecontent);
		$filecontent = preg_replace("/\s/","&",$filecontent);
		parse_str($filecontent,$filearray);
		return $filearray;
	}
	
	function __destruct() {
		//echo $this->rootfolder;
		imagepng($this->image, $this->rootfolder."assets/img/GD/Characters/".$this->name.".png");
		ImageDestroy($this->image);
	#	$image_1 = imagecreatefrompng('assets/img/rank/bg/bg'.$this->bg.'.png');
	#	$image_2 = imagecreatefrompng('assets/img/GD/Characters/'.$this->name.'.png');
	#	imagealphablending($image_1, true);
	#	imagesavealpha($image_1, true);
	#	imagecopy($image_1, $image_2, 33, 33, 0, 0, 96, 96);
	#	imagepng($image_1, 'assets/img/GD/Characters/'.$this->name.'.png');
		return true;
	}

}
?>