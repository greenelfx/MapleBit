<?php
if(basename($_SERVER["PHP_SELF"]) == "main-events.php") {
	die("403 - Access Forbidden");
}
echo "
	<div class=\"col-md-6\">
	<a href='?base=main&amp;page=events'><h4>Events &raquo;</h4></a><hr/>
";

$ge = $mysqli->query("SELECT * FROM ".$prefix."events ORDER BY id DESC LIMIT 4");
if($ge && $ge->num_rows) {
	while($e = $ge->fetch_assoc()) {
		$gc = $mysqli->query("SELECT * FROM ".$prefix."ecomments WHERE eid='".$e['id']."' ORDER BY id ASC");
		$cc = $gc->num_rows;
		echo "
			<img src=\"assets/img/news/".$e['type'].".gif\" alt='".$e['type']."' />
			[".$e['date']."]
			<a href=\"?base=main&amp;page=events&amp;id=".$e['id']."\">
		";
		echo htmlspecialchars(ellipsize($e['title'], 25, 1, "..."), ENT_QUOTES, 'UTF-8');
		echo "<span class=\"badge pull-right\">".$cc."</span></a><br/>";
	}
}
else {
	echo "<div class=\"alert alert-info\">No events posted.</div>";
}
echo "<hr/></div>";