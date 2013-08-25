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

if($_SESSION['id']){
	if($_SESSION['gm']){
		if($_SESSION['pname'] == NULL){
			echo "Hey there! You need to assign a profile name before you do this.";
		}else{
			if($_GET['action']=="add"){
				if(!$_POST['add']){
				echo "<legend>Add a GM Blog</legend>";
					echo "
				<form method=\"post\" action=''>
					You can add a blog entry about your day on <b>$servername</b>, how many hackers you banned, or anything!<br />
					Your imagination sets the limit.<br/><br/>
				<b>Title:</b><br/>
				<input type=\"text\" name=\"title\" /><br/>
				<b>Author:</b> ".$_SESSION['pname']."<br/>
				<b>Content:</b>
				<textarea name=\"content\" style=\"height:250px;width:100%\"></textarea><br/>
				<div class=\"alert alert-info\">You may edit or delete your blog entry later on.</div>
				<input type=\"submit\" name=\"add\" value=\"Add Blog Entry &raquo;\" class=\"btn btn-primary\"/>
				</form>";
				}else{
					$title = $mysqli->real_escape_string($_POST['title']);
					$date = date("m.d");
					$content = sanitize_space($_POST['content']);
					if($title == ""){
						echo "You must enter a title.";
					}elseif($content == ""){
						echo "You must enter some content.";
					}else{
						$i = $mysqli->query("INSERT INTO `cype_gmblog` (`title`,`author`,`date`,`content`) VALUES ('".$title."','".$_SESSION['pname']."','".$date."','".$content."')") or die(mysql_error());
						echo "Your blog entry has been posted.";
					}
				}
			}elseif($_GET['action']=="edit"){
				echo "<legend>Editing a GM Blog</legend>";
				if($_GET['id']){
					$id = sql_sanitize($_GET['id']);
					$gb = $mysqli->query("SELECT * FROM `cype_gmblog` WHERE `id`='".$id."'") or die(mysql_error());
					$b = $gb->fetch_assoc();
					if($_SESSION['pname'] == $b['author'] || $_SESSION['admin']){
						if(!$_POST['edit']){
							echo "
				<form method=\"post\" action=''>
					<b>Title:</b><br/>
					<input type=\"text\" style='width:50%;' name=\"title\" value=\"".stripslashes($b['title'])."\" /><br/>
					<b>Author:</b> ".$b['author']." <br/>
					<b>Content:</b><br/>
					<textarea name=\"content\" style=\"height:250px;width:100%\">".stripslashes($b['content'])."</textarea><br/>
					<div class=\"alert alert-info\">You may edit or delete your blog entry later on.</div><br/>
					<input type=\"submit\" name=\"edit\" value=\"Edit Blog Entry\" class=\"btn btn-primary\"/>
				</form>";
						}else{
							$title = sanitize_space($_POST['title']);
							$content = sanitize_space($_POST['content']);
							if($title == ""){
								echo "You must enter a title.";
							}elseif($content == ""){
								echo "You must enter some content.";
							}else{
								$u = $mysqli->query("UPDATE `cype_gmblog` SET `title`='".$title."',`content`='".$content."' WHERE `id`='".$id."'") or die(mysql_error());
								echo "Blog entry, <b>".stripslashes($b['title'])."</b>, has been updated.";
							}
						}
					}else{
						echo "<div class=\"alert alert-error\">This blog entry does not belong to you.</div>";
					}
				}else{
					echo "Select a blog entry to modify:<br/>";
					if($_SESSION['gm']){
						$gb = $mysqli->query("SELECT * FROM `cype_gmblog` WHERE `author`='".$_SESSION['pname']."' ORDER BY `id` ASC") or die(mysql_error());
						while($b = $gb->fetch_assoc()){
							echo "
						[".$b['date']."] <a href=\"?cype=gmcp&amp;page=manblog&action=edit&id=".$b['id']."\">".stripslashes($b['title'])."</a><br/>";
						}
					}
					if($_SESSION['admin']){
						echo "
						<hr/><b>Administrator Options<br/></b>
						Select a blog entry to modify:<br/>";
						$gab = $mysqli->query("SELECT * FROM `cype_gmblog` ORDER BY `id` ASC") or die(mysql_error());
						while($ab = $gab->fetch_assoc()){
							echo "
								[".$ab['date']."] <a href=\"?cype=gmcp&amp;page=manblog&amp;action=edit&id=".$ab['id']."\">".stripslashes($ab['title'])."</a> by <a href=\"?cype=main&amp;page=members&name=".$ab['author']."\">".$ab['author']."</a><br/>
								";
						}
					}
				}

			} else if ($_GET['action']=="pdel") {
				echo "<legend>Delete a GM Blog</legend>";
				if (!isset($_GET['id'])) {
					echo "No Blog Comment ID Specified.";
				} else if (!is_numeric($_GET['id'])) {
					echo "Invalid Blog Comment ID.";
				} else {
					$gmbid = sql_sanitize($_GET['id']);
					$query = $mysql->query("SELECT * FROM cype_bcomments WHERE id = ".$gmbid."") or die(mysql_error());
					$rows = $query->num_rows;
					$fetch = $query->fetch_assoc();
		
					if ($rows != 1) {
						echo "Blog Comment ID Does Not Exists.";
					} else {
						$delete = "DELETE FROM cype_bcomments WHERE id = ".$gmbid."";
						if ($mysql->query($delete)) {
							header("Location:?cype=main&page=gmblog&id=".$fetch['bid']);
						} else {
							echo "Error deleting blog comment.";
						}
					}
				}
			}elseif($_GET['action']=="del"){
				echo "<legend>Delete a GM Blog</legend>";
				if(!$_POST['del']){
					echo "
					<form method=\"post\" action=''>
						Select a blog entry to delete<br/>
						<b>Blog</b><br/>
						<select name=\"blog\">
							<option value=\"\">Please select...</option>
								<optgroup label=\"Your Blog Entries\">";
					$gb = $mysqli->query("SELECT * FROM `cype_gmblog` WHERE `author`='".$_SESSION['pname']."' ORDER BY `id` ASC") or die(mysql_error());
					while($b = $gb->fetch_assoc()){
						echo "
									<option value=\"".$b['id']."\">[".$b['date']."] ".stripslashes($b['title'])."</option>";
					}
					if($_SESSION['admin']){
						echo "
										<optgroup label=\"Administrator\">";
						$gb = $mysqli->query("SELECT * FROM `cype_gmblog` WHERE `author`!='".$_SESSION['pname']."' ORDER BY `author`,`id` ASC") or die(mysql_error());
						while($b = $gb->fetch_assoc()){
							echo "
											<option value=\"".$b['id']."\">[".$b['date']."] ".stripslashes($b['title'])."</option>";
						}
					}
					echo "
										</optgroup>
									</select><br/>
						<b>Delete:</b><br/>
							<select name=\"dec\">
								<option value=\"0\">No</option>
								<option value=\"1\">Yes</option>
							</select><br/>
						<div class=\"alert alert-error\">Please remember that this action cannot be undone.</div><br/>
						<input type=\"submit\" name=\"del\" value=\"Delete &raquo;\" class=\"btn btn-inverse\"/>
					</form>";
				}else{
					$blog = sql_sanitize($_POST['blog']);
					$dec = sanitize_space($_POST['dec']);
					if($blog == ""){
						echo "Please select a blog entry to delete.";
					}elseif($dec == "0"){
						echo "The blog entry was not deleted.";
					}else{
						$d = $mysqli->query("DELETE FROM `cype_gmblog` WHERE `id`='".$blog."'") or die(mysql_error());
						echo "The blog entry has been deleted.";
					}
				}
		}elseif($_GET['action']=="lock"){
			echo "
			<legend>Lock Blog</legend>";
			if(!isset($_POST['lock'])){
				echo "
			<form method=\"post\" action=''>
				Select a blog to lock:<br/>
				<select name=\"art\">
					<option value=\"\">Please select...</option>";
				$gn = $mysqli->query("SELECT * FROM `cype_gmblog` WHERE `author`='".$_SESSION['pname']."' ORDER BY `id` DESC") or die();
				while($n = $gn->fetch_assoc()){
					echo "
						<option value=\"".$n['id']."\">#".$n['id']." - ".$n['title']."</option>";
				}
				echo "
				</select><br/>
				<hr/>
				<input type=\"submit\" name=\"lock\" value=\"Lock &raquo;\" class=\"btn btn-inverse\"/>
			</form>";
			}else{
				$art = $mysqli->real_escape_string($_POST['art']);
				if($art == ""){
					echo "<div class=\"alert alert-block\">Please select a blog to lock.</div>";
				}else{
					$d = $mysqli->query("UPDATE `cype_gmblog` SET `locked` = 1 WHERE `id`='".$art."' AND `author`='".$_SESSION['pname']."'") or die();
					echo "<div class=\"alert alert-success\">The blog has been locked.</div>";
				}
			}
		}elseif($_GET['action']=="unlock"){
			echo "
			<legend>Unlock Blog</legend>";
			if(!isset($_POST['unlock'])){
				echo "
			<form method=\"post\" action=''>
				Select a blog to unlock:<br/>
				<select name=\"art\">
					<option value=\"\">Please select...</option>";
				$gn = $mysqli->query("SELECT * FROM `cype_gmblog` WHERE `author`='".$_SESSION['pname']."' AND `locked` = 1 ORDER BY `id` DESC") or die();
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
					echo "<div class=\"alert alert-block\">Please select a blog to unlock.</div>";
				}else{
					$d = $mysqli->query("UPDATE `cype_gmblog` SET `locked` = 0 WHERE `id`='".$art."' AND `author`='".$_SESSION['pname']."'") or die();
					echo "<div class=\"alert alert-success\">The blog has been unlocked.</div>";
				}
			}
		}
	}
	}else{
		include('sources/public/accessdenied.php');
	}
}else{
	echo "Please log in to use this feature.";
}
?>