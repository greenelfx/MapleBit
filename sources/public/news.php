<?php
if(isset($_GET['id'])){
	$id = $mysqli->real_escape_string($_GET['id']);
	$gn = $mysqli->query("SELECT * FROM ".$prefix."news WHERE id='".$id."'") or die();
	$n = $gn->fetch_assoc();
	echo "
		<h2 class=\"text-left\">".stripslashes($n['title'])." | Posted by <a href=\"?base=main&amp;page=members&amp;name=".$n['author']."\">".$n['author']."</a> on ".$n['date']."</h2><hr/>
		";
	echo nl2br(stripslashes($n['content']))."
	<br /><br />
	";
	$gc = $mysqli->query("SELECT ".$prefix."ncomments.*, accounts.email, accounts.id As id1, ".$prefix."profile.accountid, ".$prefix."profile.name FROM ".$prefix."ncomments INNER JOIN ".$prefix."profile ON ".$prefix."ncomments.author = ".$prefix."profile.name INNER JOIN accounts ON ".$prefix."profile.accountid = accounts.id WHERE ".$prefix."ncomments.id= '".$id."'") or die();
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
			<a href=\"?base=admin&amp;page=mannews&amp;action=edit&amp;id=".$n['id']."\" class=\"btn btn-primary\">Edit</a>
			<a href=\"?base=admin&amp;page=mannews&amp;action=del\" class=\"btn btn-info\">Delete</a>
			<a href=\"?base=admin&amp;page=mannews&amp;action=".$buttonlink."\" class=\"btn btn-default\">".$buttontext."</a>
			<hr />";
	}
	if(isset($_SESSION['id'])){
	$flood = $mysqli->query("SELECT * FROM `".$prefix."ncomments` WHERE `nid`='".$id."' && `author`='".$_SESSION['pname']."' ORDER BY `dateadded` DESC LIMIT 1") or die();
	$fetchg = $flood->fetch_assoc();
	$seconds = 60*$basefloodint;
		if($_SESSION['mute'] == 1){
			echo "<div class=\"alert alert-danger\">You have been muted. Please contact an administrator</div>";
		}
		elseif($n['locked'] == "1"){
			echo "<div class=\"alert alert-danger\">This article has been locked.</div>";
		}elseif(isset($_SESSION['pname']) === "checkpname"){
			echo "<div class=\"alert alert-danger\">You must assign a profile name before you can comment news articles.</div>";
		}elseif($baseflood > 0 && (time() - $seconds) < $fetchg['dateadded']) {
			echo "<div class=\"alert alert-danger\">You may only post every ".$basefloodint." minutes to prevent spam.</div>";
		}else{
			echo "
			<form method=\"post\">
				 <div class=\"form-group\">
					<label for=\"inputMood\">Mood</label>
						<select name=\"feedback\" class=\"form-control\" id=\"inputMood\">
							<option value=\"0\">Positive</option>
							<option value=\"1\">Neutral</option>
							<option value=\"2\">Negative</option>
						</select>
					</div>
					<div class=\"form-group\">
						<label for=\"inputComment\">Comment:</label>
						<textarea name=\"text\" class=\"form-control\" rows=\"5\" id=\"inputComment\"></textarea>
					</div>
					<hr/>
					<input type=\"submit\" name=\"comment\" value=\"Comment\" class=\"btn btn-primary\"/>
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
			$i = $mysqli->query("INSERT INTO ".$prefix."ncomments (nid, author, feedback, date, comment, dateadded) VALUES ('".$id."','".$_SESSION['pname']."','".$feedback."','".$date."','".$comment."','".$timestamp."')") or die();
			echo "<meta http-equiv=refresh content=\"0; url=?base=main&amp;page=news&amp;id=".$id."\" />";
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
				$modify = "<a href=\"?base=admin&amp;page=mannews&amp;action=pdel&amp;id=".$c['id']."\" class=\"btn btn-default text-right\">Delete</a>";
			}
			echo "
			<div class=\"well\"><img src=\"" . get_gravatar($c['email']) . "\" alt=\"".$c['author']."\" class=\"img-responsive\" style=\"float:left;padding-right:10px;\"/>
			<h4><b>".$c['author']."</b> - Posted on ".$c['date']." ".$modify."</h4>
					<b>Feedback:</b> ".$feedback."<hr />
					".stripslashes($c['comment'])."
				</div>";
		}
	}
}else{
	$gn = $mysqli->query("SELECT * FROM ".$prefix."news ORDER BY id DESC") or die();
	$rows = $gn->num_rows;
	if ($rows < 1) {
		echo "<div class=\"alert alert-danger\">Oops! There isn't any news to display right now!</div>";
	}
	else{
	echo "<h2 class=\"text-left\">".$servername." News</h2><hr/>";
	while($n = $gn->fetch_assoc()){
		$gc = $mysqli->query("SELECT * FROM ".$prefix."ncomments WHERE nid='".$n['id']."' ORDER BY id ASC") or die();
		$cc = $gc->num_rows;
		echo "
			<img src=\"assets/img/news/".$n['type'].".gif\" alt='".$n['type']."' />
			[".$n['date']."] <b><a href=\"?base=main&page=news&amp;id=".$n['id']."\">".stripslashes($n['title'])."</a></b>
		<span class=\"commentbubble\">	
			<b>".$n['views']."</b> views | <b>".$cc."</b> comments
		";
		if(isset($_SESSION['admin'])){
			echo "
				<a href=\"?base=admin&amp;page=mannews&amp;action=edit&amp;id=".$n['id']."\">Edit</a> | 
				<a href=\"?base=admin&amp;page=mannews&amp;action=del\">Delete</a> |
				<a href=\"?base=admin&amp;page=mannews&amp;action=lock\">Lock</a>&nbsp;
			";
		}
		echo "</span><br/>";
	}
}
}
?>