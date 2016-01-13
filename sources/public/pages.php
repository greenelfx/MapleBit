<?php
if(basename($_SERVER["PHP_SELF"]) == "pages.php"){
    die("403 - Access Forbidden");
}
	$querypage = $mysqli->query("SELECT * FROM ".$prefix."pages WHERE slug = '".$main."'");
	$nquerypage = $querypage->num_rows;
	$p = $querypage->fetch_assoc();
	if(empty($p['author']) || empty($p['content']) || $nquerypage == 0) {
		echo "<div class=\"alert alert-danger\">I couldn't make that page for you.</div>";
		redirect_wait5("?base=main");
	} else {
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
		echo "<h2 class=\"text-left\">".$p['title']."</h2>
		<hr/>
		".$clean_html."";
	}
?>