<?php
if(basename($_SERVER["PHP_SELF"]) == "pages.php") {
	die("403 - Access Forbidden");
}
$query = $mysqli->query("SELECT * FROM ".$prefix."pages WHERE slug = '".$main."'");
if($query->num_rows == 0) {
	echo "<div class=\"alert alert-danger\">This page doesn't exist.</div>";
	redirect_wait5("?base=main");
}
else {
	$p = $query->fetch_assoc();
	require_once 'assets/libs/HTMLPurifier.standalone.php';
	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.SafeIframe', true);
	$config->set('HTML.TargetBlank', true);
	$config->set('HTML.SafeObject', true);
	$config->set('Output.FlashCompat', true);
	$config->set('HTML.SafeEmbed', true);
	$config->set('HTML.Trusted', true);
	$config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%'); //allow YouTube and Vimeo
	$purifier = new HTMLPurifier($config);
	$clean_html = $purifier->purify($p['content']);
	echo "
		<h2 class=\"text-left\">".$p['title']."</h2>
		<hr/>
		".$clean_html."
	";
}