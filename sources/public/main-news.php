<?php
if(basename($_SERVER["PHP_SELF"]) == "main-news.php") {
	die("403 - Access Forbidden");
}
echo "
	<div class=\"col-md-6\">
	<a href='?base=main&amp;page=news'><h4>News &raquo;</h4></a><hr/>
";

$gn = $mysqli->query("SELECT * FROM ".$prefix."news ORDER BY id DESC LIMIT 4");
if($gn && $gn->num_rows) {
	while($n = $gn->fetch_assoc()) {
		$gc = $mysqli->query("SELECT * FROM ".$prefix."ncomments WHERE nid='".$n['id']."' ORDER BY id ASC");
		$cc = $gc->num_rows;
		echo "
			<img src=\"assets/img/news/".$n['type'].".gif\" alt='".$n['type']."' class='text-left' />
			[".$n['date']."]
			<a href=\"?base=main&amp;page=news&amp;id=".$n['id']."\">
		";
		echo htmlspecialchars(ellipsize($n['title'], 25, 1, "..."), ENT_QUOTES, 'UTF-8');
		echo "<span class=\"badge pull-right\">".$cc."</span></a><br/>";
	}
}
else {
	echo "<div class=\"alert alert-info\">No news posted.</div>";
}
echo "<hr/></div>";