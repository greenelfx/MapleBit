<?php 
/*
    Copyright (C) 2009  Murad <Murawd>
						Josh L. <Josho192837>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
if(@$_GET['id']){
	$id = sql_sanitize($_GET['id']);
	$gb = $mysqli->query("SELECT * FROM `".$prefix."gmblog` WHERE `id`='".$id."'") or die(mysql_error());
	$b = $gb->fetch_assoc();
	echo "
		<legend>".$b['title']." | Posted by <a href=\"?cype=main&amp;page=members&amp;name=".$b['author']."\">".$b['author']."</a> on ".$b['date']."</legend>";
	echo nl2br(stripslashes($b['content']))."<br /><br />";
	$gc = $mysqli->query("SELECT * FROM `".$prefix."bcomments` WHERE `bid`='".$id."' ORDER BY `id` ASC") or die(mysql_error());
	$cc = $gc->num_rows;
	$flood = $mysqli->query("SELECT * FROM `".$prefix."bcomments` WHERE `bid`='".sql_sanitize($id)."' && `author`='".sql_sanitize($_SESSION['pname'])."' ORDER BY `dateadded` DESC LIMIT 1") or die(mysql_error());
	$fetchg = $flood->fetch_assoc();
	$seconds = 60*$cypefloodint;

	echo "
		<b>".$b['views']."</b> Views and <b>".$cc."</b> Responses<hr/>";

	$av = $mysqli->query("UPDATE `".$prefix."gmblog` SET `views` = views + 1 WHERE `id`='".$id."'") or die(mysql_error());
	if(isset($_SESSION['admin']) || isset($_SESSION['gm'])){
		if($b['locked'] == "1"){
			$buttontext = "Unlock";
			$buttonlink = "unlock";
		}
		else {$buttontext = "Lock"; $buttonlink = "lock";}
		echo "
			<a href=\"?cype=gmcp&page=manblog&action=edit&amp;id=".$b['id']."\" class=\"btn btn-primary\">Edit</a>
			<a href=\"?cype=gmcp&page=manblog&action=del\" class=\"btn btn-info\">Delete</a>
			<a href=\"?cype=gmcp&page=manblog&action=".$buttonlink."\" class=\"btn btn-default\">".$buttontext."</a>
			<hr />";
	}
	if(isset($_SESSION['id'])){
		if($_SESSION['mute'] == "1"){
			include("sources/public/mutemessage.php");
		}if($b['locked'] == "1"){
			echo "<div class=\"alert alert-error\">This article has been locked.</div>";
		}elseif($_SESSION['pname'] == "checkpname"){
			echo "You must assign a profile name before you can comment news articles.";
		}elseif($cypeflood > 0 && (time() - $seconds) < $fetchg['dateadded']) {
			echo "<b>You may only post every ".$cypefloodint." minutes to prevent spam.</b>";
		}else{
			echo "
				<form method=\"post\" action=''>
					<b>Mood:</b><br/>
						<select name=\"feedback\">
							<option value=\"0\">Positive</option>
							<option value=\"1\">Neutral</option>
							<option value=\"2\">Negative</option>
						</select><br/>
					<b>Comment:</b><br/>
					<textarea name=\"text\" class=\"form-control\" rows=\"5\"></textarea><br/>
					<input type=\"submit\" name=\"comment\" value=\"Comment\" class=\"btn btn-primary\"/>
				</form>";
		}
	}else{
		echo "
			<br/><div class=\"alert alert-danger\">Please log in to comment!</div>";
	}
	if(@$_POST['comment']){
		$feedback = sanitize_space($_POST['feedback']);
		$date = date("m-d-y g:i A");
		$comment = bbcodeParser(sanitize_space($_POST['text']));
		if($comment == ""){
			echo "
				<br/><div class=\"alert alert-danger\">You cannot leave the comment field blank!</div>";
		}else{
			$timestamp = time();
			$i = $mysqli->query("INSERT INTO `".$prefix."bcomments` (`bid`,`author`,`feedback`,`date`,`comment`,`dateadded`) VALUES ('".$id."','".$_SESSION['pname']."','".$feedback."','".$date."','".$comment."','".$timestamp."')") or die(mysql_error());
			echo "
				<meta http-equiv='refresh' content=\"0; url=?cype=main&amp;page=gmblog&amp;id=".$id."\">";
		}
	}
	echo "<hr />";
	if($ngc = $gc->num_rows <= 0){
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
				$modify = "- <a href=\"?cype=gmcp&amp;page=manblog&amp;action=pdel&amp;id=".$c['id']."\" title=\"Delete This Comment\">X</a>";
			}

			echo "
					<legend>
						<b>".$c['author']."</b> - ".$c['date']."".$modify."
					</legend>
					<b>Feedback:</b> ".$feedback."<br />
					".stripslashes($c['comment'])."
				<br />";
		}
	}
}else{
	$gb = $mysqli->query("SELECT * FROM `".$prefix."gmblog` ORDER BY `id` DESC") or die(mysql_error());
	$rows = $gb->num_rows;
	if ($rows < 1) {
		echo "<div class=\"alert alert-danger\">Oops! No blogs to display right now!</div>";
	}
	else {
	echo "<legend>".$servername." GM Blogs</legend>";
	while($b = $gb->fetch_assoc()){
		$gc = $mysqli->query("SELECT * FROM `".$prefix."bcomments` WHERE `bid`='".$b['id']."' ORDER BY `id` ASC") or die(mysql_error());
		$cc = $gc->num_rows;
		echo "
			[".$b['date']."]
				<b><a href=\"?cype=main&amp;page=gmblog&amp;id=".$b['id']."\">".$b['title']."</a></b> by
				<a href=\"?cype=main&amp;page=members&amp;name=".$b['author']."\">".$b['author']."</a> 
		<span class=\"commentbubble\">
			<b>".$b['views']."</b> views | <b>".$cc."</b> comments
		</span>";
		if (isset($_SESSION['gm'])) {
			echo "
			<span class=\"commentbubble\">
				<a href=\"?cype=admin&amp;page=manblog&amp;action=edit&amp;id=".$n['id']."\">Edit</a> | 
				<a href=\"?cype=admin&amp;page=manblog&amp;action=del\">Delete</a> | 
				<a href=\"?cype=admin&amp;page=manblog&amp;action=lock\">Lock</a>&nbsp;
			</span>";
		}
	}
}
}
	echo "
		<tr>
			<td height='4'></td>
		</tr>
	</tbody>
</table>";

?>