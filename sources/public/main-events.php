<?php
if(basename($_SERVER["PHP_SELF"]) == "main-events.php"){
    die("403 - Access Forbidden");
}
echo "
	<div class=\"col-md-6\">
	<a href='?base=main&amp;page=events'><h4>Events &raquo;</h4></a><hr/>
";
$i = 0;
$ge = $mysqli->query("SELECT * FROM ".$prefix."events ORDER BY id DESC LIMIT 4") or die();
while($e = $ge->fetch_assoc()) {
	$gc = $mysqli->query("SELECT * FROM ".$prefix."ecomments WHERE eid='".$e['id']."' ORDER BY id ASC") or die();
	$cc = $gc->num_rows;
	echo "
		<img src=\"assets/img/news/".$e['type'].".gif\" alt='".$e['type']."' />
		[".$e['date']."]
		<a href=\"?base=main&amp;page=events&amp;id=".$e['id']."\">
	";
	echo htmlspecialchars(ellipsize($e['title'], 25, 1, "..."), ENT_QUOTES, 'UTF-8');
	echo "<span class=\"badge pull-right\">".$cc."</span></a><br/>";
	$i++;
}

if($i == 0) {
	echo "No events to display right now!";
}
echo "<hr/></div>";