<?php
if(basename($_SERVER["PHP_SELF"]) == "funcs.php") {
	die("403 - Access Forbidden");
}

function ellipsize($str, $max_length, $position = 1, $ellipsis = '&hellip;') {
	// Strip tags
	$str = trim(strip_tags($str));
	// Is the string long enough to ellipsize?
	if (strlen($str) <= $max_length) {
		return $str;
	}
	$beg = substr($str, 0, floor($max_length * $position));
	$position = ($position > 1) ? 1 : $position;
	if ($position === 1) {
		$end = substr($str, 0, -($max_length - strlen($beg)));
	}
	else {
		$end = substr($str, -($max_length - strlen($beg)));
	}
	return $beg.$ellipsis.$end;
}

function getRealIpAddr() {
	//check ip from share internet
	if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	//to check ip is pass from proxy
	elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

function redirect($url) {
	if(!headers_sent()) {
		header('Location: '.$url);
    	exit;
	}
	else {
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

function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array()) {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&amp;d=identicon&amp;r=$r";
    if($img) {
        $url = '<img src="' . $url . '"';
        foreach ($atts as $key => $val)
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}

function ago($time) {
	$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	$lengths = array("60","60","24","7","4.35","12","10");
	$now = time();
	$difference  = $now - $time;
	$tense = "ago";

	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		$difference /= $lengths[$j];
	}
	$difference = round($difference);
	if($difference != 1) {
		$periods[$j].= "s";
	}
	return "$difference $periods[$j] ago";
}

function getCountries() {
	$countries = array("United States", "Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
	return $countries;
}

function getJobNames($unique) {
	$jobNames = array(0 => "Beginner",100 => "Warrior",110 => "Fighter", 111 => "Crusader", 112 => "Hero",120 => "Page", 121 => "White Knight", 122 => "Paladin",130 => "Spearman", 131 => "Dragon Knight", 132 => "Dark Knight",200 => "Magician",210 => "Wizard (F/P)", 211 => "Mage (F/P)", 212 => "Arch Mage (F/P)",220 => "Wizard (I/L)", 221 => "Mage (I/L)", 222 => "Arch Mage (I/L)",230 => "Cleric", 231 => "Priest", 232 => "Bishop",300 => "Bowman",310 => "Hunter", 311 => "Ranger", 312 => "Bowmaster",320 => "Crossbowman", 321 => "Sniper", 322 => "Marksman",400 => "Thief",410 => "Assassin", 411 => "Hermit", 412 => "Night Lord",420 => "Bandit", 421 => "Chief Bandit", 422 => "Shadower",430 => "Blade Recruit", 431 => "Blade Acolyte", 432 => "Blade Specialist", 433 => "Blade Lord", 434 => "Blade Master",500 => "Pirate",510 => "Brawler", 511 => "Marauder", 512 => "Buccaneer",520 => "Gunslinger", 521 => "Outlaw", 522 => "Corsair",501 => "Pirate", 530 => "Cannoneer", 531 => "Cannon Trooper", 532 => "Cannon Master",508 => "Jett", 570 => "Jett", 571 => "Jett", 572 => "Jett",509 => "Pirate",580 => "Brawler", 581 => "Marauder", 582 => "Buccaneer",590 => "Gunslinger", 591 => "Outlaw", 592 => "Corsair",1000 => "Noblesse",1100 => "Dawn Warrior", 1110 => "Dawn Warrior", 1111 => "Dawn Warrior", 1112 => "Dawn Warrior",1200 => "Blaze Wizard", 1210 => "Blaze Wizard", 1211 => "Blaze Wizard", 1212 => "Blaze Wizard",1300 => "Wind Archer", 1310 => "Wind Archer", 1311 => "Wind Archer", 1312 => "Wind Archer",1400 => "Night Walker", 1410 => "Night Walker", 1411 => "Night Walker", 1412 => "Night Walker",1500 => "Thunder Breaker", 1510 => "Thunder Breaker", 1511 => "Thunder Breaker", 1512 => "Thunder Breaker",2000 => "Legend", 2100 => "Aran", 2110 => "Aran", 2111 => "Aran", 2112 => "Aran",2001 => "Evan", 2200 => "Evan", 2210 => "Evan", 2211 => "Evan", 2212 => "Evan", 2213 => "Evan", 2214 => "Evan", 2215 => "Evan", 2216 => "Evan", 2217 => "Evan", 2218 => "Evan",2002 => "Mercedes", 2300 => "Mercedes", 2310 => "Mercedes", 2311 => "Mercedes", 2312 => "Mercedes",2003 => "Phantom", 2400 => "Phantom", 2410 => "Phantom", 2411 => "Phantom", 2412 => "Phantom",2004 => "Luminous", 2700 => "Luminous", 2710 => "Luminous", 2711 => "Luminous", 2712 => "Luminous",2005 => "Shade", 2500 => "Shade", 2510 => "Shade", 2511 => "Shade", 2512 => "Shade",3000 => "Citizen",3200 => "Battle Mage", 3210 => "Battle Mage", 3211 => "Battle Mage", 3212 => "Battle Mage",3300 => "Wild Hunter", 3310 => "Wild Hunter", 3311 => "Wild Hunter", 3312 => "Wild Hunter",3500 => "Mechanic", 3510 => "Mechanic", 3511 => "Mechanic", 3512 => "Mechanic",3001 => "Demon Slayer",3100 => "Demon Slayer", 3110 => "Demon Slayer", 3111 => "Demon Slayer", 3112 => "Demon Slayer",3101 => "Demon Avenger", 3120 => "Demon Avenger", 3121 => "Demon Avenger", 3122 => "Demon Avenger",3002 => "Xenon", 3600 => "Xenon", 3610 => "Xenon", 3611 => "Xenon", 3612 => "Xenon",4001 => "Hayato", 4100 => "Hayato", 4110 => "Hayato", 4111 => "Hayato", 4112 => "Hayato",4002 => "Kanna", 4200 => "Kanna", 4210 => "Kanna", 4211 => "Kanna", 4212 => "Kanna",5000 => "Mihile", 5100 => "Mihile", 5110 => "Mihile", 5111 => "Mihile", 5112 => "Mihile",6000 => "Kaiser", 6100 => "Kaiser", 6110 => "Kaiser", 6111 => "Kaiser", 6112 => "Kaiser",6001 => "Angelic Buster", 6500 => "Angelic Buster", 6510 => "Angelic Buster", 6511 => "Angelic Buster", 6512 => "Angelic Buster",10000 => "Zero", 10100 => "Zero", 10110 => "Zero", 10111 => "Zero", 10112 => "Zero",11000 => "Beast Tamer", 11200 => "Beast Tamer", 11210 => "Beast Tamer", 11211 => "Beast Tamer", 11212 => "Beast Tamer",13000 => "Pink Bean", 13100 => "Pink Bean",14000 => "Kinesis", 14200 => "Kinesis", 14210 => "Kinesis", 14211 => "Kinesis", 14212 => "Kinesis");
	if($unique) {
		return array_unique($jobNames);
	}
	return $jobNames;
}
?>