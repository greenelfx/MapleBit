<?php
if(basename($_SERVER["PHP_SELF"]) == "main-gm.php"){
    die("403 - Access Forbidden");
}
echo "
<div class=\"col-md-6\">
<a href='?base=main&amp;page=gmblog'><h4>GM Blogs &raquo;</h4></a><hr/>";
	$i = 0;
	$gb = $mysqli->query("SELECT * FROM ".$prefix."gmblog ORDER BY id DESC LIMIT 4") or die();
	while($b = $gb->fetch_assoc()){
		$gc = $mysqli->query("SELECT * FROM ".$prefix."bcomments WHERE bid='".$b['id']."' ORDER BY id ASC") or die();
		$cc = $gc->num_rows;
		$title = $b['title'];
		$maxlength = 33;
		echo "
			[".$b['date']."]
			<a href=\"?base=main&amp;page=gmblog&amp;id=".$b['id']."\">";
		if(strlen($title) > $maxlength){
			echo htmlspecialchars(shortTitle($title), ENT_QUOTES, 'UTF-8');
		}else{
			echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
		}
		echo "<span class=\"badge pull-right\">".$cc."</span></a><br/>";
		$i++;
	}
	if($i == 0) {
		echo "No blogs to display right now!";
	}

echo "<hr/></div>";
?>