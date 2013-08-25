<script src="assets/js/nicEdit.js" type="text/javascript"></script>
<script type="text/javascript">
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>
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

if(isset($_SESSION['id'])){
	if(isset($_SESSION['admin'])){
		if($_GET['action']=="add"){
			echo "
			<legend>Add A News Article</legend>";
			if($_SESSION['pname'] == NULL){
				echo "<div class=\"alert alert-danger\">You must assign a profile name before you can enter this page.</div>";
			}else{
				if(!isset($_POST['add'])){
					echo "
			<form method=\"post\" action='' role=\"form\">
			<div class=\"form-group\">
				<label for=\"title\">Title</label>
				<input type=\"text\" name=\"title\" class=\"form-control\" id=\"title\" placeholder=\"Title\" required/>
			</div>
			<b>Author:</b> ".$_SESSION['pname']."<br/>
			<b>Category:</b><br/>
			<select name=\"cat\" class=\"form-control\">
				<option value=\"ct_news_notice_notice\">Notice</option>
				<option value=\"ct_news_gameup\">Game Up</option>
			</select><br/>
			<textarea name=\"content\" style=\"height:300px;width:100%;\" class=\"form-control\"></textarea><br/>
			<input type=\"submit\" name=\"add\" class=\"btn btn-primary\" value=\"Add News Article &raquo;\" />
		</form>";
				}else{
					$title = $mysqli->real_escape_string($_POST['title']);
					$author = $_SESSION['pname'];
					$date = date("m.d");
					$cat = $mysqli->real_escape_string($_POST['cat']);
					$content = $mysqli->real_escape_string($_POST['content']);
					if($title == ""){
						echo "You must enter a title.";
					}elseif(empty($cat)){
						echo "You must select a category.";
					}elseif($content == ""){
						echo "You must enter some content.";
					}else{
						$i = $mysqli->query("INSERT INTO `cype_news` (`title`,`author`,`type`,`date`,`content`) VALUES ('".$title."','".$author."','".$cat."','".$date."','".$content."')") or die();
						echo "Your news article has been posted.";
					}
				}
			}
			echo "
		";
		}elseif($_GET['action']=="edit"){
			echo "
			<legend>Edit A News Article</legend>";
			if(isset($_GET['id'])){
				$id = $mysqli->real_escape_string($_GET['id']);
				$gn = $mysqli->query("SELECT * FROM `cype_news` WHERE `id`='".$id."'") or die();
				$n = $gn->fetch_assoc();
				if(!isset($_POST['edit'])){
					echo "
				<form method=\"post\" action=''>
			<div class=\"form-group\">
				<label for=\"title\">Title</label>
				<input type=\"text\" name=\"title\" class=\"form-control\" id=\"title\" placeholder=\"Title\"value=\"".$n['title']."\" required/>
			</div>
				<b>Author:</b> ".$n['author']."<br/>
				<b>Category:</b><br/>
				<select name=\"cat\" class=\"form-control\">
					<option value=\"ct_news_notice_notice\">Notice</option>
					<option value=\"ct_news_gameup\">Game Up</option>
				</select><br/>
				<textarea name=\"content\" style=\"height:300px;width:100%;\" class=\"form-control\">".stripslashes($n['content'])."</textarea><br/>
				<input type=\"submit\" name=\"edit\" class=\"btn btn-primary\" value=\"Edit News Article &raquo;\" />		
			</form>";
				}else{
					$title = $mysqli->real_escape_string($_POST['title']);
					$cat = $mysqli->real_escape_string($_POST['cat']);
					$content = $mysqli->real_escape_string($_POST['content']);
					if($title == ""){
						echo "You must enter a title.";
					}elseif(empty($cat)){
						echo "You must select a category.";
					}elseif($content == ""){
						echo "You must enter some content.";
					}else{
						$u = $mysqli->query("UPDATE `cype_news` SET `title`='".$title."',`type`='".$cat."',`content`='".$content."' WHERE `id`='".$id."'") or die();
						echo "<div class=\"alert alert-success\"><b>".stripslashes($n['title'])."</b> has been updated.</div>";
					}
				}
			}else{
				echo "
				Select a news article to modify:<br/>
				";
				$gn = $mysqli->query("SELECT * FROM `cype_news` ORDER BY `id` DESC") or die();
				while($n = $gn->fetch_assoc()){
					echo "
					[".$n['date']."] <a href=\"?cype=admin&amp;page=mannews&amp;action=edit&amp;id=".$n['id']."\">".$n['title']."</a><br/>
					";
				}
			}
		} else if ($_GET['action']=="pdel") {
			if (!isset($_GET['id'])) {
				echo "No Comment ID Specified.";
			} else if (!is_numeric($_GET['id'])) {
				echo "Invalid Comment ID.";
			} else {
				$newsid = $mysqli->real_escape_string($_GET['id']);
				$query = $mysqli->query("SELECT * FROM cype_ncomments WHERE id = ".$newsid."") or die();
				$rows = $query->num_rows;
				$fetch = $query->fetch_assoc();
				
				if ($rows != 1) {
					echo "Comment ID Does Not Exists.";
				} else {
					$delete = "DELETE FROM cype_ncomments WHERE id = ".$newsid."";
					if ($mysqli->query($delete)) {
						header("Location:?cype=main&page=news&id=".$fetch['nid']);
					} else {
						echo "Error deleting news comment.";
					}
				}

			}

		}elseif($_GET['action']=="del"){
			echo "
			<legend>Delete A News Article</legend>";
			if(!isset($_POST['del'])){
				echo "
			<form method=\"post\" action=''>
				Select a news article to delete:<br/>
				<select name=\"art\">
					<option value=\"\">Please select...</option>";
				$gn = $mysqli->query("SELECT * FROM `cype_news` ORDER BY `id` DESC") or die();
				while($n = $gn->fetch_assoc()){
					echo "
						<option value=\"".$n['id']."\">#".$n['id']." - ".$n['title']."</option>";
				}
				echo "
				</select><br/>
				<hr/>
				<b>Confirm Deletion:</b><br/>
				<select name=\"dec\">
					<option value=\"0\">No</option>
					<option value=\"1\">Yes</option>
				</select><br/><br/>
				<input type=\"submit\" name=\"del\" value=\"Delete &raquo;\" class=\"btn btn-default\"/>
			</form>";
			}else{
				$art = $mysqli->real_escape_string($_POST['art']);
				$dec = $mysqli->real_escape_string($_POST['dec']);
				if($art == ""){
					echo "<div class=\"alert alert-danger\">Please select a news article to delete.</div>";
				}elseif($dec == "0"){
					echo "<div class=\"alert alert-warning\">The news article was not deleted because you selected \"No\".</div>";
				}else{
					$d = $mysqli->query("DELETE FROM `cype_news` WHERE `id`='".$art."'") or die();
					echo "<div class=\"alert alert-success\">The news article has been deleted.</div>";
				}
			}
		}elseif($_GET['action']=="lock"){
			echo "
			<legend>Lock News Article</legend>";
			if(!isset($_POST['lock'])){
				echo "
			<form method=\"post\" action=''>
				Select a news article to lock:<br/>
				<select name=\"art\">
					<option value=\"\">Please select...</option>";
				$gn = $mysqli->query("SELECT * FROM `cype_news` ORDER BY `id` DESC") or die();
				while($n = $gn->fetch_assoc()){
					echo "
						<option value=\"".$n['id']."\">#".$n['id']." - ".$n['title']."</option>";
				}
				echo "
				</select><br/>
				<hr/>
				<input type=\"submit\" name=\"lock\" value=\"Lock &raquo;\" class=\"btn btn-default\"/>
			</form>";
			}else{
				$art = $mysqli->real_escape_string($_POST['art']);
				if($art == ""){
					echo "<div class=\"alert alert-block\">Please select a news article to lock.</div>";
				}else{
					$d = $mysqli->query("UPDATE `cype_news` SET `locked` = 1 WHERE `id`='".$art."'") or die();
					echo "<div class=\"alert alert-success\">The news article has been locked.</div>";
				}
			}
		} elseif($_GET['action']=="unlock"){
			echo "
			<legend>Unlock News Article</legend>";
			if(!isset($_POST['unlock'])){
				echo "
			<form method=\"post\" action=''>
				Select a news article to unlock:<br/>
				<select name=\"art\">
					<option value=\"\">Please select...</option>";
				$gn = $mysqli->query("SELECT * FROM `cype_news` WHERE `locked` = 1 ORDER BY `id` DESC") or die();
				while($n = $gn->fetch_assoc()){
					echo "
						<option value=\"".$n['id']."\">#".$n['id']." - ".$n['title']."</option>";
				}
				echo "
				</select><br/>
				<hr/>
				<input type=\"submit\" name=\"unlock\" value=\"Unlock &raquo;\" class=\"btn btn-inverse\"/>
			</form>";
			}else{
				$art = $mysqli->real_escape_string($_POST['art']);
				if($art == ""){
					echo "<div class=\"alert alert-block\">Please select a news article to unlock.</div>";
				}else{
					$d = $mysqli->query("UPDATE `cype_news` SET `locked` = 0 WHERE `id`='".$art."'") or die();
					echo "<div class=\"alert alert-success\">The news article has been unlocked.</div>";
				}
			}
		}
	}else{
		include('sources/public/accessdenied.php');
	}
}else{
	echo "You must be logged in to use this feature.";
}
?>