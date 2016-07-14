<?php
if(basename($_SERVER["PHP_SELF"]) == "news.php"){
    die("403 - Access Forbidden");
}
?>
<script src="assets/libs/cksimple/ckeditor.js"></script>
<style>
.permalinkshow {
	display: none;
}
</style>
<?php
if(isset($_GET['id'])) {
	$id = $mysqli->real_escape_string($_GET['id']);
	$gn = $mysqli->query("SELECT * FROM ".$prefix."news WHERE id='".$id."'");
	$n = $gn->fetch_assoc();
	if($gn->num_rows) {
		$mysqli->query("UPDATE `".$prefix."news` SET `views` = views + 1 WHERE `id`='".$id."'");
		require_once 'assets/libs/HTMLPurifier.standalone.php';
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.SafeIframe', true);
		$config->set('HTML.TargetBlank', true);
		$config->set('HTML.SafeObject', true);
		$config->set('Output.FlashCompat', true);
		$config->set('HTML.SafeEmbed', true);
		$config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%'); //allow YouTube and Vimeo
		$purifier = new HTMLPurifier($config);
		$clean_html = $purifier->purify($n['content']);
		$positive = 0;
		$negative = 0;
		$neutral = 0;
		$gc = $mysqli->query("SELECT ".$prefix."ncomments.*, accounts.email, accounts.id As id1, ".$prefix."profile.accountid, ".$prefix."profile.name FROM ".$prefix."ncomments INNER JOIN ".$prefix."profile ON ".$prefix."ncomments.author = ".$prefix."profile.name INNER JOIN accounts ON ".$prefix."profile.accountid = accounts.id WHERE ".$prefix."ncomments.nid= '".$id."' ORDER BY date DESC") or die();
		$cc = $gc->num_rows;
		$getfeedback = $mysqli->query("SELECT feedback FROM ".$prefix."ncomments");
		if($cc > 0) {
			while($afeed = $getfeedback->fetch_assoc()) {
				if($afeed['feedback'] == 0){
					$positive++;
				}
				elseif ($afeed['feedback'] == 1) {
					$neutral++;
				}
				elseif($afeed['feedback'] == 2){
					$negative++;
				}
			}
			$positive = ($positive/$cc)*100;
			$negative = ($negative/$cc)*100;
			$neutral = ($neutral/$cc)*100;
		}
		echo "
			<h2 class=\"text-left\">".htmlspecialchars($n['title'], ENT_QUOTES, 'UTF-8')." | Posted by <a href=\"?base=main&amp;page=members&amp;name=".$n['author']."\">".$n['author']."</a> on ".	$n['date']."</h2><hr/>
			";
		echo "<div class=\"breakword\">" . $clean_html."</div><hr/>";
		echo "
			<b>".$n['views']."</b> Views and <b>".$cc."</b> Responses<hr />
			<div class=\"progress\">
				<div class=\"progress-bar progress-bar-success\" style=\"width: ".$positive."%\">
					<span class=\"sr-only\">".$positive."% (positive)</span>
				</div>
				<div class=\"progress-bar progress-bar-danger\" style=\"width: ".$negative."%\">
					<span class=\"sr-only\">".$negative."% (negative)</span>
				</div>
				<div class=\"progress-bar progress-bar-default\" style=\"width: ".$neutral."%\">
					<span class=\"sr-only\">".$neutral." (neutral)</span>
				</div>
			</div>
		";
		if(isset($_SESSION['admin'])) {
			if($n['locked'] == "1") {
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
		if(isset($_SESSION['id'])) {
			$flood = $mysqli->query("SELECT * FROM `".$prefix."ncomments` WHERE `nid`='".$id."' && `author`='".$_SESSION['pname']."' ORDER BY `date` DESC LIMIT 1") or die();
			$fetchg = $flood->fetch_assoc();
			$seconds = 60*$basefloodint;
			$editor = false;
			if($_SESSION['mute'] == 1) {
				echo "<div class=\"alert alert-danger\">You have been muted. Please contact an administrator</div>";
			}
			elseif($n['locked'] == "1") {
				echo "<div class=\"alert alert-danger\">This article has been locked.</div>";
			} elseif($_SESSION['pname'] == "checkpname") {
				echo "<div class=\"alert alert-danger\">You must assign a profile name before you can comment news articles.</div>";
			} elseif($baseflood > 0 && (time() - $seconds) < $fetchg['date']) {
				echo "<div class=\"alert alert-danger\">You may only post every ".$basefloodint." minutes to prevent spam.</div>";
			} else {
				$editor = true;
				echo "
				<form method=\"post\" id=\"commentBox\">
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
		}
		else{
			echo "
				<div class=\"alert alert-danger\">Please log in to comment.</div>";
		}
		if(isset($_POST['comment'])){
			$feedback = $mysqli->real_escape_string($_POST['feedback']);
			$comment = $mysqli->real_escape_string($_POST['text']);
			if($comment == ""){
				echo "<br/><div class=\"alert alert-danger\">You cannot leave the comment field blank!</div>";
			}
			else {
				$date = time();
				$i = $mysqli->query("INSERT INTO ".$prefix."ncomments (nid, author, feedback, date, comment) VALUES ('".$id."','".$_SESSION['pname']."','".$feedback."','".$date."','".$comment."')	") or die();
				echo "<meta http-equiv=refresh content=\"0; url=?base=main&amp;page=news&amp;id=".$id."\" />";
			}
		}
		echo "<hr />";
	
		if($cgc = $gc->num_rows <= 0 && $n['locked'] == 0) {
			echo "<div class=\"alert alert-info\">There are no comments for this article yet. Be the first to comment!</div>";
		}
		else {
			$commentconfig = HTMLPurifier_Config::createDefault();
			$commentconfig->set('HTML.Allowed', 'p, b, u, s, ol, li, ul, i, em, strong, blockquote, small, hr');
			$commentpurifier = new HTMLPurifier($commentconfig);
			while($c = $gc->fetch_assoc()){
			$clean_comment = $commentpurifier->purify($c['comment']);
				if($c['feedback'] == "0"){
					$feedback = "<span class=\"positive_comment\">Positive</span>";
				}elseif($c['feedback'] == "1"){
					$feedback = "<span class=\"neutral_comment\">Neutral</span>";
				}elseif($c['feedback'] == "2"){
					$feedback = "<span class=\"negative_comment\">Negative</span>";
				}
				$modify = "";
				if(isset($_SESSION['admin'])){
					$modify = "<a href=\"?base=admin&amp;page=mannews&amp;action=pdel&amp;id=".$c['id']."\">Delete</a> | ";
				}
				$quote = "";
				if(isset($_SESSION['id'])){
					$quote = "<a href=\"#comment-".$c['id']."-".$c['author']."\" class=\"quote\">Quote</a> | ";
				}
				echo "
				<div class=\"well\"><img src=\"" . get_gravatar($c['email']) . "\" alt=\"".$c['author']."\" class=\"img-responsive\" style=\"float:left;padding-right:10px;\"/>
				<h4 style=\"margin:0px;\">".$c['author']."</h4>
					<b>Feedback:</b> ".$feedback."<br/>
					<small>Posted ".ago($c['date']).", on ". date('M j, Y', $c['date'])."</small><br/>
					<small>".$modify . $quote."<a href=\"#comment-link-".$c['id']."\" class=\"permalink\">Permalink</a><a href=\"?base=main&page=news&id=".$id."#comment-".$c['id']."\" class=\"	permalinkshow linkid-".$c['id']."\">?base=main&page=news&id=".$id."#comment-".$c['id']."</a></small><hr/>
					<div class=\"breakword\" id=\"comment-".$c['id']."\">".$clean_comment."</div>
					</div>";
			}
		}
	}
	else {
		echo "invalid id";
	}
}
else {
	$gn = $mysqli->query("SELECT * FROM ".$prefix."news ORDER BY id DESC") or die();
	$rows = $gn->num_rows;
	if ($rows < 1) {
		echo "<div class=\"alert alert-danger\">Oops! There isn't any news to display right now!</div>";
	}
	else {
		echo "<h2 class=\"text-left\">".$servername." News</h2><hr/>";
		while($n = $gn->fetch_assoc()) {
			$gc = $mysqli->query("SELECT * FROM ".$prefix."ncomments WHERE nid='".$n['id']."' ORDER BY id ASC") or die();
			$cc = $gc->num_rows;
			echo "
				<img src=\"assets/img/news/".$n['type'].".gif\" alt='".$n['type']."' />
				[".$n['date']."] <b><a href=\"?base=main&page=news&amp;id=".$n['id']."\">".htmlspecialchars($n['title'], ENT_QUOTES, 'UTF-8')."</a></b>
			<span class=\"commentbubble\">
				<b>".$n['views']."</b> views | <b>".$cc."</b> comments
			";
			if(isset($_SESSION['admin'])) {
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
<script>
<?php
	if(isset($_SESSION['id']) && $editor){
?>
CKEDITOR.replace( 'inputComment', {
    allowedContent: 'b i u li ol ul blockquote anchor hr small'
});
$(function() {
for ( var i in CKEDITOR.instances ){
   var currentInstance = i;
   break;
}
var oEditor = CKEDITOR.instances[currentInstance];
  $('.quote').click(function(e) {
    var getcomment_id = $(this).attr('href');
	var commentarr = getcomment_id.split("-");
	var comment_id = commentarr[1];
	var author = commentarr[2];
    var comment = ('<blockquote>' + $("#comment-"+ comment_id).html() + '<small>' + author + '</small></blockquote>').replace(new RegExp("<hr>", 'g'),"") + "<hr><p>";
	oEditor.insertHtml(comment);
    $("body, html").animate({
		scrollTop: $('#commentBox').offset().top+10
	}, 200);
  });
});
<?php
	}
?>
$(function(){
  $(".permalink").click(function(){
	var comment_id = $(this).attr('href').replace(/[^0-9]+/, '');
    $(".linkid-" + comment_id).fadeToggle();
	$(this).hide();
  });
});
</script>