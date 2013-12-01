<?php 
if(isset($_GET['id'])){
	$id = $mysqli->real_escape_string($_GET['id']);
	$gn = $mysqli->query("SELECT * FROM `".$prefix."news` WHERE `id`='".$id."'") or die();
	$n = $gn->fetch_assoc();
	echo "
		<legend>".stripslashes($n['title'])." | Posted by <a href=\"?cype=main&amp;page=members&amp;name=".$n['author']."\">".$n['author']."</a> on ".$n['date']."</legend>
		";
	echo nl2br(stripslashes($n['content']))."
	<br /><br />
	";
	$gc = $mysqli->query("SELECT * FROM `".$prefix."ncomments` WHERE `nid`='".$id."' ORDER BY `id` ASC") or die();
	$cc = $gc->num_rows;
	echo "
	<b>".$n['views']."</b> Views and <b>".$cc."</b> Responses<hr />";
	$av = $mysqli->query("UPDATE `".$prefix."news` SET `views` = views + 1 WHERE `id`='".$id."'") or die();
	if(isset($_SESSION['admin'])){
		if($n['locked'] == "1"){
			$buttontext = "Unlock";
			$buttonlink = "unlock";
		}
		else {$buttontext = "Lock"; $buttonlink = "lock";}
		echo "
			<a href=\"?cype=admin&amp;page=mannews&amp;action=edit&amp;id=".$n['id']."\" class=\"btn btn-primary\">Edit</a>
			<a href=\"?cype=admin&amp;page=mannews&amp;action=del\" class=\"btn btn-info\">Delete</a>
			<a href=\"?cype=admin&amp;page=mannews&amp;action=".$buttonlink."\" class=\"btn btn-default\">".$buttontext."</a>
			<hr />";
	}
	$flood = $mysqli->query("SELECT * FROM `".$prefix."ncomments` WHERE `nid`='".$id."' && `author`='".$_SESSION['pname']."' ORDER BY `dateadded` DESC LIMIT 1") or die();
	$fetchg = $flood->fetch_assoc();
	$seconds = 60*$cypefloodint;
	if(isset($_SESSION['id'])){
		if(isset($_SESSION['mute']) == "1"){
			include("sources/public/mutemessage.php");
		}
		if($n['locked'] == "1"){
			echo "<div class=\"alert alert-danger\">This article has been locked.</div>";
		}elseif($_SESSION['pname'] == NULL){
			echo "You must assign a profile name before you can comment news articles.";
		}elseif($cypeflood > 0 && (time() - $seconds) < $fetchg['dateadded']) {
			echo "<b>You may only post every ".$cypefloodint." minutes to prevent spam.</b>";
		}else{
			echo "
				<form method=\"post\" action=''>
						<b>Mood:</b> 
							<select name=\"feedback\">
								<option value=\"0\">Positive</option>
								<option value=\"1\">Neutral</option>
								<option value=\"2\">Negative</option>
							</select><br/>
						<b>Comment:</b><br />
						<textarea name=\"text\" class=\"form-control\" rows=\"5\"></textarea><br/>
						<input type=\"submit\" name=\"comment\" value=\"Submit Comment\" class=\"btn btn-primary\"/>
				</form>";
		}
	}else{
		echo "
			<br/><div class=\"alert alert-danger\">Please log in to comment.</div>";
	}
	if(isset($_POST['comment'])){
		$feedback = $mysqli->real_escape_string($_POST['feedback']);
		$date = date("m-d-y g:i A");
		$comment = htmlspecialchars($mysqli->real_escape_string($_POST['text']));
		if($comment == ""){
			echo "<br/><div class=\"alert alert-danger\">You cannot leave the comment field blank!</div>";
		}else{
			$timestamp = time();
			$i = $mysqli->query("INSERT INTO `".$prefix."ncomments` (`nid`,`author`,`feedback`,`date`,`comment`,`dateadded`) VALUES ('".$id."','".$_SESSION['pname']."','".$feedback."','".$date."','".$comment."','".$timestamp."')") or die();
			echo "<meta http-equiv=refresh content=\"0; url=?cype=main&amp;page=news&amp;id=".$id."\" />";
		}
	}
	echo "<hr />";

	if($cgc = $gc->num_rows <= 0 && $n['locked'] == 0){
		echo "<div class=\"alert alert-info\">There are no comments for this article yet. Be the first to comment!</div>";
	}else{
		while($c = $gc->fetch_assoc()){
			if($c['feedback'] == "0"){
				$feedback = "<font color=\"green\">Positive</font>";
			}elseif($c['feedback'] == "1"){
				$feedback = "<font color=\"gray\">Neutral</font>";
			}elseif($c['feedback'] == "2"){
				$feedback = "<font color=\"red\">Negative</font>";
			}
			$modify = "";
			if(isset($_SESSION['admin'])){
				$modify = "<a href=\"?cype=admin&amp;page=mannews&amp;action=pdel&amp;id=".$c['id']."\" class=\"btn btn-inverse text-right\">Remove Comment</a>";
			}
			echo "
				<b>".$c['author']."</b> at ".$c['date']."
				<br/><b>Mood:</b> ".$feedback."<br />
				<b>Comment:</b> ".stripslashes($c['comment'])."<br />".$modify."<br /><hr/>";
		}
	}
}else{
	$gn = $mysqli->query("SELECT * FROM `".$prefix."news` ORDER BY `id` DESC") or die();

	$rows = $gn->num_rows;

	if ($rows < 1) {
		echo "<div class=\"alert alert-danger\">Oops! There isn't any news to display right now!</div>";
	}
	else{
	echo "<legend>".$servername." News</legend>";
	while($n = $gn->fetch_assoc()){
		$gc = $mysqli->query("SELECT * FROM `".$prefix."ncomments` WHERE `nid`='".$n['id']."' ORDER BY `id` ASC") or die();
		$cc = $gc->num_rows;
		echo "
			<img src=\"assets/img/news/".$n['type'].".gif\" alt='".$n['type']."' />
			[".$n['date']."] <b><a href=\"?cype=main&page=news&amp;id=".$n['id']."\">".stripslashes($n['title'])."</a></b>
		<span class=\"commentbubble\">	
			<b>".$n['views']."</b> views | <b>".$cc."</b> comments
		</span>";
		if(isset($_SESSION['admin'])){
			echo "
			<span class=\"commentbubble\">
				<a href=\"?cype=admin&amp;page=mannews&amp;action=edit&amp;id=".$n['id']."\">Edit</a> | 
				<a href=\"?cype=admin&amp;page=mannews&amp;action=del\">Delete</a> |
				<a href=\"?cype=admin&amp;page=mannews&amp;action=lock\">Lock</a>&nbsp;
			</span>";
		}
		echo "<br/>";
	}
}
}
?>