<?php
if(basename($_SERVER["PHP_SELF"]) == "main-news.php"){
    die("403 - Access Forbidden");
}
echo "
<div class=\"col-md-6\">
<a href='?base=main&amp;page=news'><h4>News &raquo;</h4></a><hr/>";
	$i = 0;
	$gn = $mysqli->query("SELECT * FROM ".$prefix."news ORDER BY id DESC LIMIT 4") or die();
	while($n = $gn->fetch_assoc()){
		$gc =$mysqli->query("SELECT * FROM ".$prefix."ncomments WHERE nid='".$n['id']."' ORDER BY id ASC") or die();
		$cc = $gc->num_rows;
		$title = $n['title'];
		$maxlength = 33;
		echo "
			<img src=\"assets/img/news/".$n['type'].".gif\" alt='".$n['type']."' class='text-left' />
			[".$n['date']."]
			<a href=\"?base=main&amp;page=news&amp;id=".$n['id']."\">";
		if(strlen($title) > $maxlength){
			echo htmlspecialchars(shortTitle($title), ENT_QUOTES, 'UTF-8');
		}else{
			echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
		}
		echo "<span class=\"badge pull-right\">".$cc."</span></a><br/>";
		$i++;
	}
	if($i == 0) {
		echo "No news to display right now!";
	}

echo "<hr/></div>";
?>