<?php 
if(@$_GET['id']){
	$id = $mysqli->real_escape_string($_GET['id']);
	$ge = $mysqli->query("SELECT * FROM ".$prefix."events WHERE id='".sql_sanitize($id)."'") or die();
	$e = $ge->fetch_assoc();
	echo "
		<legend>".stripslashes($e['title'])." | Posted by <a href=\"?cype=main&amp;page=members&amp;name=".$e['author']."\">".$e['author']."</a> on ".$e['date']."</legend>
	";
	if($e['status'] == "Active"){
		$status = "<div class=\"alert alert-success\">Event is active</div>";
	}
	if($e['status'] == "Standby"){
			$status = "<div class=\"alert\">Event is on Standby</div>";
	}
	if($e['status'] == "Ended"){
		$status = "<div class=\"alert alert-danger\">This event has ended</div>";
	}
	echo " ".$status."";
	echo nl2br(stripslashes($e['content']))."
	<br /><br />";
	$gc = $mysqli->query("SELECT * FROM ".$prefix."ecomments WHERE eid='".sql_sanitize($id)."' ORDER BY id ASC") or die();
	$cc = $gc->num_rows;
	echo "<b>".$e['views']."</b> Views and <b>".$cc."</b> Reponses";
	echo "<hr />";
	$av = $mysqli->query("UPDATE ".$prefix."events SET views = views + 1 WHERE id='".sql_sanitize($id)."'") or die();
	if(isset($_SESSION['admin'])){
		if($e['locked'] == "1"){
			$buttontext = "Unlock";
			$buttonlink = "unlock";
		}
		else {$buttontext = "Lock"; $buttonlink = "lock";}
		echo "
			<a href=\"?cype=admin&amp;page=manevent&amp;action=edit&amp;id=".$e['id']."\" class=\"btn btn-primary\">Edit</a>
			<a href=\"?cype=admin&amp;page=manevent&amp;action=del\" class=\"btn btn-info\">Delete</a>
			<a href=\"?cype=admin&amp;page=manevent&amp;action=".$buttonlink."\" class=\"btn btn-default\">".$buttontext."</a>
			<hr />";
	}
	$flood = $mysqli->query("SELECT * FROM ".$prefix."ecomments WHERE eid='".sql_sanitize($id)."' && author='".sql_sanitize($_SESSION['pname'])."' ORDER BY dateadded DESC LIMIT 1") or die();
	$fetchg = $flood->fetch_assoc();
	$seconds = 60*$cypefloodint;

	if(isset($_SESSION['id'])){
		if($_SESSION['mute'] =="1"){
			echo "<div class==\"alert alert-danger\">You have been muted. Please contact an administrator</div>";
		}if($e['locked'] == "1"){
			echo "<div class=\"alert alert-danger\">This article has been locked.</div>";
		}elseif($_SESSION['pname'] == "checkpname"){
			echo "<div class=\"alert alert-danger\">You must assign a profile name before you can comment news articles.</div>";
		}elseif($cypeflood > 0 && (time() - $seconds) < $fetchg['dateadded']) {
			echo "<div class=\"alert alert-danger\">You may only post every ".$cypefloodint." minutes to prevent spam.</div>";
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
		echo "<br/><div class=\"alert alert-danger\">Please log in to comment.</div>";
	}
	if(isset($_POST['comment'])){
		$author = $_SESSION['pname'];
		$feedback = $mysqli->real_escape_string($_POST['feedback']);
		$date = date("m-d-y g:i A");
		$comment = $mysqli->real_escape_string($_POST['text']);
		if($comment == ""){
			echo "<br/><div class=\"alert alert-danger\">You cannot leave the comment field blank!</div>";
		}else{
			$timestamp = time();
			$i = $mysqli->query("INSERT INTO ".$prefix."ecomments (eid, author, feedback, date, comment, dateadded) VALUES ('".sql_sanitize($id)."','".sql_sanitize($author)."','".sql_sanitize($feedback)."','".sql_sanitize($date)."','".sanitize_space($comment)."','".sql_sanitize($timestamp)."')") or die();
			echo "<meta http-equiv=refresh content=\"0; url=?cype=main&amp;page=events&amp;id=".$id."\" />";
		}
	}
	echo "<hr />";
	if($ngc = $gc->num_rows <= 0 && $e['locked'] == 0){
		echo "<div class=\"alert alert-info\">There are no comments for this article yet. Be the first to comment!</div>";
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
			if(isset($_SESSION['admin'])){
				$modify = "<a href=\"?cype=admin&amp;page=mannews&amp;action=pdel&amp;id=".$c['id']."\" class=\"btn btn-default text-right\">Delete</a>";
			}
			echo "
			<h4><b>".$c['author']."</b> - Posted on ".$c['date']." ".$modify."</h4>
					<b>Feedback:</b> ".$feedback."<br />
					".stripslashes($c['comment'])."
				<br />";
		}
	}
}else{
	$ge = $mysqli->query("SELECT * FROM ".$prefix."events ORDER BY id DESC") or die();
	$rows = $ge->num_rows;
	if ($rows < 1) {
		echo "<div class=\"alert alert-danger\">Oops! No events to display right now!</div>";
	}
	else {
	echo "<h2 class=\"text-left\">".$servername." Events</h2><hr/>";
	while($e = $ge->fetch_assoc()){
		$gc = $mysqli->query("SELECT * FROM ".$prefix."ecomments WHERE eid='".sql_sanitize($e['id'])."' ORDER BY id ASC") or die();
		$cc = $gc->num_rows;
		echo "<img src=\"assets/img/news/".$e['type'].".gif\" alt='' />";
		echo "[".$e['date']."]  
			<b><a href=\"?cype=main&amp;page=events&amp;id=".$e['id']."\">".stripslashes($e['title'])."</a></b>
		<span class=\"commentbubble\">
			<b>".$e['views']."</b> views | <b>".$cc."</b> comments
		</span>";
		if(isset($_SESSION['admin'])){
			echo "
			<span class=\"commentbubble\">
				<a href=\"?cype=admin&amp;page=manevent&amp;action=edit&amp;id=".$e['id']."\">Edit</a> | 
				<a href=\"?cype=admin&amp;page=manevent&amp;action=del\">Delete</a> | 
				<a href=\"?cype=admin&amp;page=manevent&amp;action=lock\">Lock</a>&nbsp;
			</span>";
		}
	}
}
}
?>