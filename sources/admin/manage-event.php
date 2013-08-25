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
	if($_SESSION['admin']){
		if($_GET['action']=="add"){
			echo "
			<legend>Add An Event</legend>";
			if($_SESSION['pname'] == NULL){
				echo "You must assign a profile name before you can enter this page.";
			}else{
				if(!isset($_POST['add'])){
					echo "
				<form method=\"post\" action=''>
				<b>Title:</b><br/>
				<input type=\"text\" style='width:50%;' name=\"title\" required/><br/>
				<b>Author:</b><br/>
				".$_SESSION['pname']."<br/>
				<b>Category:</b><br/>
				<select name=\"cat\">
					<option value=\"ct_news_event_info\">Info</option>
					<option value=\"ct_news_event_lot\">Prize</option>
					<option value=\"ct_news_event_end\">End</option>
				</select><br/>
				<b>Status:</b><br/>
				<select name=\"status\">
					<option value=\"Active\">Active</option>
					<option value=\"Standby\">Standby</option>
				</select>
				<textarea name=\"content\" style=\"height:300px;width:100%;\"></textarea><br/>
				<input type=\"submit\" name=\"add\" class=\"btn btn-primary\" value=\"Add Event &raquo;\" />
				</form>";
				}else{
					$title = $mysqli->real_escape_string($_POST['title']);
					$date = date("m.d");
					$cat = $mysqli->real_escape_string($_POST['cat']);
					$status = $mysqli->real_escape_string($_POST['status']);
					$content = $mysqli->real_escape_string($_POST['content']);
					if($title == ""){
						echo "You must enter a title.";
					}elseif($cat == ""){
						echo "You must select a category.";
					}elseif($content == ""){
						echo "You must enter some content.";
					}else{
						$i = $mysqli->query("INSERT INTO `cype_events` (`title`,`author`,`date`,`type`,`status`,`content`) VALUES ('".$title."','".$_SESSION['pname']."','".$date."','".$cat."','".$status."','".$content."')") or die(mysql_error());
						echo "Your event has been posted.";
					}
				}
			}
		}elseif($_GET['action']=="edit"){
			echo "
			<legend>Edit An Event</legend>";
			if(isset($_GET['id'])){
				$id = $mysqli->real_escape_string($_GET['id']);
				$ge = $mysqli->query("SELECT * FROM `cype_events` WHERE `id`='".$id."'") or die();
				$e = $ge->fetch_assoc();
				if(!isset($_POST['edit'])){
					echo "
			<form method=\"post\" action=''>
			<b>Title:</b><br/>
			<input type=\"text\" style='width:50%;' name=\"title\" value=\"".$e['title']."\" required/><br/>
			<b>Author:</b><br/>
			".$e['author']."<br/>
			<b>Category:</b><br/>
			<select name=\"cat\">
				<option value=\"ct_news_event_info\">Info</option>
				<option value=\"ct_news_event_lot\">Prize</option>
				<option value=\"ct_news_event_end\">End</option>
			</select><br/>
			<b>Status:</b><br/>
			<select name=\"status\">
				<option value=\"Active\">Active</option>
				<option value=\"Standby\">Standby</option>
				<option value=\"Ended\">End</option>
			</select><br/>
			<textarea name=\"content\" style=\"height:300px;width:100%;\">".stripslashes($e['content'])."</textarea><br/>
			<input type=\"submit\" name=\"edit\" class=\"btn btn-primary\" value=\"Edit Event &raquo;\" />
			</form>";
				}else{
					$title = $mysqli->real_escape_string($_POST['title']);
					$cat = $mysqli->real_escape_string($_POST['cat']);
					$status = $mysqli->real_escape_string($_POST['status']);
					$content = $mysqli->real_escape_string($_POST['content']);
					if($title == ""){
						echo "You must enter a title.";
					}elseif(empty($cat)){
						echo "You must select a category.";
					}elseif($content == ""){
						echo "You must enter some content.";
					}else{
						$u = $mysqli->query("UPDATE `cype_events` SET `title`='".$title."',`type`='".$cat."',`status`='".$status."',`content`='".$content."' WHERE `id`='".$id."'") or die();
						echo "The event has been edited.";
					}
				}
			}else{
				echo "Select an event to modify:<br/>";
				$ge = $mysqli->query("SELECT * FROM `cype_events` ORDER BY `id` DESC") or die();
				while($e = $ge->fetch_assoc()){
					echo "
						[".$e['date']."] <a href=\"?cype=admin&amp;page=manevent&amp;action=edit&amp;id=".$e['id']."\">".$e['title']."</a> [#".$e['id']."]<br/>
					";
				}
			}
			echo "
			</table>
		</fieldset>";

		} else if ($_GET['action']=="pdel") {
			if (!isset($_GET['id'])) {
				echo "No Comment ID Specified.";
			} else if (!is_numeric($_GET['id'])) {
				echo "Invalid Comment ID.";
			} else {
				$eventid = $mysqli->real_escape_string($_GET['id']);
				$query = $mysqli->query("SELECT * FROM cype_ecomments WHERE id = ".$eventid."") or die();
				$rows = $query->num_rows;
				$fetch = $query->fetch_assoc();
	
				if ($rows != 1) {
					echo "Comment ID Does Not Exists.";
				} else {
					$delete = "DELETE FROM cype_ecomments WHERE id = ".$eventid."";
					if ($mysqli->query($delete)) {
						header("Location:?cype=main&page=events&id=".$fetch['eid']);
					} else {
						echo "Error deleting event comment.";
					}
				}

			}

		}elseif($_GET['action']=="del"){
			echo "
			<legend>Delete An Event</legend>";
			if(!isset($_POST['del'])){
				echo "
			<form method=\"post\" action=''>
				Select an event to delete:<br/>
				<select name=\"event\">
					<option value=\"\">Please select...</option>";
				$ge = $mysqli->query("SELECT * FROM `cype_events` ORDER BY `id` DESC") or die();
				while($e = $ge->fetch_assoc()){
					echo "
					<option value=\"".$e['id']."\">#".$e['id']." - ".$e['title']."</option>";
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
				$event = $mysqli->real_escape_string($_POST['event']);
				$dec = $mysqli->real_escape_string($_POST['dec']);
				if($event == ""){
					echo "<div class=\"alert alert-block\">Please select an event to delete.</div>";
				}elseif($dec == "0"){
					echo "<div class=\"alert alert-error\">The event was not deleted because you selected No.</div>";
				}else{
					$d = $mysqli->query("DELETE FROM `cype_events` WHERE `id`='".$event."'") or die();
					echo "<div class=\"alert alert-success\">The event has been deleted.</div>";
				}
			}
		}elseif($_GET['action']=="lock"){
			echo "
			<legend>Lock Event</legend>";
			if(!isset($_POST['lock'])){
				echo "
			<form method=\"post\" action=''>
				Select a event to lock:<br/>
				<select name=\"art\">
					<option value=\"\">Please select...</option>";
				$ge = $mysqli->query("SELECT * FROM `cype_events` ORDER BY `id` DESC") or die();
				while($e = $ge->fetch_assoc()){
					echo "
						<option value=\"".$e['id']."\">#".$e['id']." - ".$e['title']."</option>";
				}
				echo "
				</select><br/>
				<hr/>
				<input type=\"submit\" name=\"lock\" value=\"Lock &raquo;\" class=\"btn btn-inverse\"/>
			</form>";
			}else{
				$art = $mysqli->real_escape_string($_POST['art']);
				if($art == ""){
					echo "<div class=\"alert alert-block\">Please select an event to lock.</div>";
				}else{
					$d = $mysqli->query("UPDATE `cype_events` SET `locked` = 1 WHERE `id`='".$art."'") or die();
					echo "<div class=\"alert alert-success\">The event has been locked.</div>";
				}
			}
		} elseif($_GET['action']=="unlock"){
			echo "
			<legend>Unlock Event</legend>";
			if(!isset($_POST['unlock'])){
				echo "
			<form method=\"post\" action=''>
				Select a event to unlock:<br/>
				<select name=\"art\">
					<option value=\"\">Please select...</option>";
				$ge = $mysqli->query("SELECT * FROM `cype_events` WHERE `locked` = 1 ORDER BY `id` DESC") or die();
				while($e = $ge->fetch_assoc()){
					echo "
						<option value=\"".$e['id']."\">#".$e['id']." - ".$e['title']."</option>";
				}
				echo "
				</select><br/>
				<hr/>
				<input type=\"submit\" name=\"unlock\" value=\"Unlock &raquo;\" class=\"btn btn-inverse\"/>
			</form>";
			}else{
				$art = $mysqli->real_escape_string($_POST['art']);
				if($art == ""){
					echo "<div class=\"alert alert-block\">Please select an event to unlock.</div>";
				}else{
					$d = $mysqli->query("UPDATE `cype_events` SET `locked` = 0 WHERE `id`='".$art."'") or die();
					echo "<div class=\"alert alert-success\">The event has been unlocked.</div>";
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