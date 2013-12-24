<?php 
echo "
<div class=\"col-md-6\">
<a href='?cype=main&amp;page=gmblog'><h4>GM Blog &raquo;</h4></a><hr/>
";
	$i = 0;
	$gn = $mysqli->query("SELECT * FROM ".$prefix."gmblog ORDER BY id DESC LIMIT 4") or die();
	while($n = $gn->fetch_assoc()){
		$title = $n['title'];
		$maxlength = 33;
		echo "
		[".$n['date']."]	<a href=\"?cype=main&amp;page=gmblog&amp;id=".$n['id']."\">";
		if(strlen($title) > $maxlength){
			echo stripslashes(shortTitle($title));
		}else{
			echo stripslashes($title);
		}
		echo "<span class=\"badge pull-right\">".$cc."</span></a><br/>";
		$i++;
	}
	if($i == 0) {
		echo "Oops! No blogs to display right now!";
	}
echo "
<hr/></div>";
?>