<?php
	$querypage = $mysqli->query("SELECT * FROM ".$prefix."pages WHERE slug = '".$main."'");
	$nquerypage = $querypage->num_rows;
	$p = $querypage->fetch_assoc();
	if(empty($p['author']) || empty($p['content']) || $nquerypage == 0) {
		echo "<div class=\"alert alert-danger\">I couldn't make that page for you.</div>";
		redirect_wait5("?base=main");
	} else {
		require_once 'assets/config/HTMLPurifier.standalone.php';
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$clean_html = $purifier->purify($p['content']);
		echo "<h2 class=\"text-left\">".$p['title']."</h2>
		<hr/>
		".$clean_html."";	
	}
?>