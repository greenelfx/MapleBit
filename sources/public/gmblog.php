<?php 
if(@$_GET['id']){
	$id = sql_sanitize($_GET['id']);
	$gb = $mysqli->query("SELECT * FROM ".$prefix."gmblog WHERE id='".$id."'") or die();
	$b = $gb->fetch_assoc();
	echo "
		<h2 class=\"text-left\">".$b['title']." | Posted by <a href=\"?base=main&amp;page=members&amp;name=".$b['author']."\">".$b['author']."</a> on ".$b['date']."</h2><hr/>";
	echo nl2br(stripslashes($b['content']))."<hr/>";
	$gc = $mysqli->query("SELECT ".$prefix."bcomments.*, accounts.email, accounts.id As id1, ".$prefix."profile.accountid, ".$prefix."profile.name FROM ".$prefix."bcomments INNER JOIN ".$prefix."profile ON ".$prefix."bcomments.author = ".$prefix."profile.name INNER JOIN accounts ON ".$prefix."profile.accountid = accounts.id") or die();
	$cc = $gc->num_rows;
	echo "
		<b>".$b['views']."</b> Views and <b>".$cc."</b> Responses<hr/>";

	$av = $mysqli->query("UPDATE ".$prefix."gmblog SET views = views + 1 WHERE id='".$id."'") or die();
	if(isset($_SESSION['admin']) || isset($_SESSION['gm'])){
		if($b['locked'] == "1"){
			$buttontext = "Unlock";
			$buttonlink = "unlock";
		}
		else {$buttontext = "Lock"; $buttonlink = "lock";}
		echo "
			<a href=\"?base=gmcp&page=manblog&action=edit&amp;id=".$b['id']."\" class=\"btn btn-primary\">Edit</a>
			<a href=\"?base=gmcp&page=manblog&action=del\" class=\"btn btn-info\">Delete</a>
			<a href=\"?base=gmcp&page=manblog&action=".$buttonlink."\" class=\"btn btn-default\">".$buttontext."</a>
			<hr />";
	}
	if(isset($_SESSION['id'])){
	$flood = $mysqli->query("SELECT * FROM ".$prefix."bcomments WHERE bid='".$id."' && author='".$_SESSION['pname']."' ORDER BY dateadded DESC LIMIT 1") or die();
	$fetchg = $flood->fetch_assoc();
	$seconds = 60*$basefloodint;
		if($_SESSION['mute'] == "1"){
			echo "<div class=\"alert alert-danger\">You have been muted. Please contact an administrator</div>";
		}elseif($b['locked'] == "1"){
			echo "<div class=\"alert alert-danger\">This article has been locked.</div>";
		}elseif($_SESSION['pname'] === "checkpname"){
			echo "<div class=\"alert alert-danger\">You must assign a profile name before you can comment blogs.</div>";
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
	if(@$_POST['comment']){
		$feedback = sanitize_space($_POST['feedback']);
		$date = date("m-d-y g:i A");
		$comment = sanitize_space($_POST['text']);
		if($comment == ""){
			echo "
				<br/><div class=\"alert alert-danger\">You cannot leave the comment field blank!</div>";
		}else{
			$timestamp = time();
			$i = $mysqli->query("INSERT INTO ".$prefix."bcomments (bid, author, feedback, date, comment, dateadded) VALUES ('".$id."','".$_SESSION['pname']."','".$feedback."','".$date."','".$comment."','".$timestamp."')") or die(mysql_error());
			echo "
				<meta http-equiv='refresh' content=\"0; url=?base=main&amp;page=gmblog&amp;id=".$id."\">";
		}
	}
	echo "<hr />";
	if($ngc = $gc->num_rows <= 0 && $b['locked'] == "0"){
		echo "<div class=\"alert alert-info\">There are no comments for this blog yet. Be the first to comment!</div>";
	}else{
		while($c = $gc->fetch_assoc()){
			if($c['feedback'] == "0"){
				$feedback = "
				<font color=\"green\">Positive</font>";
			}elseif($c['feedback'] == "1"){
				$feedback = "
				<font color=\"gray\">Neutral</font>";
			}elseif($c['feedback'] == "2"){
				$feedback = "
				<font color=\"red\">Negative</font>";
			}
			$modify = "";
			if (isset($_SESSION['gm'])) {
				$modify = "- <a href=\"?base=gmcp&amp;page=manblog&amp;action=pdel&amp;id=".$c['id']."\" title=\"Delete This Comment\" class=\"btn btn-default\">Delete</a>";
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
	$gb = $mysqli->query("SELECT * FROM ".$prefix."gmblog ORDER BY id DESC") or die();
	$rows = $gb->num_rows;
	if ($rows < 1) {
		echo "<div class=\"alert alert-danger\">Oops! No blogs to display right now!</div>";
	}
	else {
	echo "<h2 class=\"text-left\">".$servername." GM Blogs</h2><hr/>";
	while($b = $gb->fetch_assoc()){
		$gc = $mysqli->query("SELECT * FROM ".$prefix."bcomments WHERE bid='".$b['id']."' ORDER BY id ASC") or die();
		$cc = $gc->num_rows;
		echo "
			[".$b['date']."]
				<b><a href=\"?base=main&amp;page=gmblog&amp;id=".$b['id']."\">".$b['title']."</a></b> by
				<a href=\"?base=main&amp;page=members&amp;name=".$b['author']."\">".$b['author']."</a> 
		<span class=\"commentbubble\">
			<b>".$b['views']."</b> views | <b>".$cc."</b> comments
		";
		if (isset($_SESSION['gm'])) {
			echo "
			<span class=\"commentbubble\">
				<a href=\"?base=admin&amp;page=manblog&amp;action=edit&amp;id=".$b['id']."\">Edit</a> | 
				<a href=\"?base=admin&amp;page=manblog&amp;action=del\">Delete</a> | 
				<a href=\"?base=admin&amp;page=manblog&amp;action=lock\">Lock</a>&nbsp;
			";
		}
	echo "</span><br/>";
	}
}
}
?>