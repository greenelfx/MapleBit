<?php
if(basename($_SERVER["PHP_SELF"]) == "main-gm.php") {
	die("403 - Access Forbidden");
}
echo "
	<div class=\"col-md-6\">
	<a href='?base=main&amp;page=gmblog'><h4>GM Blogs &raquo;</h4></a><hr/>
";

$gb = $mysqli->query("SELECT * FROM ".$prefix."gmblog ORDER BY id DESC LIMIT 4");
if($gb && $gb->num_rows) {
	while($b = $gb->fetch_assoc()) {
		$gc = $mysqli->query("SELECT * FROM ".$prefix."bcomments WHERE bid='".$b['id']."' ORDER BY id ASC");
		$cc = $gc->num_rows;
		echo "
			[".$b['date']."]
			<a href=\"?base=main&amp;page=gmblog&amp;id=".$b['id']."\">";
			echo htmlspecialchars(ellipsize($b['title'], 25, 1, "..."), ENT_QUOTES, 'UTF-8');
			echo "<span class=\"badge pull-right\">".$cc."</span></a><br/>
		";
	}
}
else {
	echo "<div class=\"alert alert-info\">No blogs posted.</div>";
}
echo "<hr/></div>";