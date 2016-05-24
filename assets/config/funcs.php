<?php
if(basename($_SERVER["PHP_SELF"]) == "funcs.php") {
	die("403 - Access Forbidden");
}

function shortTitle($title) {
	$maxlength = 30;
	$title = $title." ";
	$title = substr($title, 0, $maxlength);
	$title = substr($title, 0, strrpos($title,' '));
	$title = $title."...";
	return $title;
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
?>