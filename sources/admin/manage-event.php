<?php
if(basename($_SERVER["PHP_SELF"]) == "manage-event.php") {
    die("403 - Access Forbidden");
}
?>
<script src="assets/libs/ckeditor/ckeditor.js"></script>
<?php
if(isset($_SESSION['id'])) {
	if(isset($_SESSION['admin'])) {
		if(empty($_GET['action'])) {
			echo "<h2 class=\"text-left\">Manage Events</h2><hr/>
				<a href=\"?base=admin&amp;page=manevent&amp;action=add\"><b>Add Event &raquo;</b></a><br/>
				<a href=\"?base=admin&amp;page=manevent&amp;action=edit\">Edit Event</a><br/>
				<a href=\"?base=admin&amp;page=manevent&amp;action=del\">Delete Event</a><br/>
			";
		}
		elseif($_GET['action']=="add") {
			echo "
			<h2 class=\"text-left\">Add Event</h2><hr/>";
			if($_SESSION['pname'] == "checkpname") {
				echo "<div class=\"alert alert-danger\">You must assign a profile name before you can enter this page.</div>";
			}else{
				if(!isset($_POST['add'])) {
					echo "
			<form method=\"post\" role=\"form\">
			<div class=\"form-group\">
				<label for=\"title\">Title</label>
				<input type=\"text\" name=\"title\" class=\"form-control\" id=\"title\" placeholder=\"Title\" required/>
			</div>
			<b>Author:</b> ".$_SESSION['pname']."<br/>
			<div class=\"form-group\">
				<label for=\"category\">Category</label>
				<select name=\"cat\" class=\"form-control\" id=\"category\">
					<option value=\"ct_news_event_info\">Info</option>
					<option value=\"ct_news_event_lot\">Prize</option>
					<option value=\"ct_news_event_end\">End</option>
				</select>
			</div>
			<div class=\"form-group\">
				<label for=\"status\">Status</label>
				<select name=\"status\" class=\"form-control\" id=\"status\">
					<option value=\"Active\">Active</option>
					<option value=\"Standby\">Standby</option>
				</select>
			</div>
			<textarea name=\"content\" style=\"height:300px;\" class=\"form-control\" id=\"content\"></textarea><br/>
			<input type=\"submit\" name=\"add\" class=\"btn btn-primary\" value=\"Add Event &raquo;\" />
		</form>";
				}else{
					$title = $mysqli->real_escape_string($_POST['title']);
					$author = $_SESSION['pname'];
					$date = date("m.d");
					$cat = $mysqli->real_escape_string($_POST['cat']);
					$status = $mysqli->real_escape_string($_POST['status']);
					$content = $mysqli->real_escape_string($_POST['content']);
					if($title == "") {
						echo "<div class=\"alert alert-danger\">You must enter a title.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					}elseif(empty($cat)) {
						echo "<div class=\"alert alert-danger\">You must select a category.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					}elseif($content == "") {
						echo "<div class=\"alert alert-danger\">You must enter some content.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					}else{
						$i = $mysqli->query("INSERT INTO ".$prefix."events (title, author, date, type, status, content) VALUES ('".$title."','".$_SESSION['pname']."','".$date."','".$cat."','".$status."','".$content."')") or die(mysql_error());
						echo "<div class=\"alert alert-success\">Your event has been posted.</div><hr/><a href=\"?base=admin\" class=\"btn btn-primary\">&laquo; Go Back</a>";
					}
				}
			}
			echo "
		";
		}elseif($_GET['action']=="edit") {
			echo "
			<h2 class=\"text-left\">Edit an Event</h2><hr/>";
			if(isset($_GET['id'])) {
				$id = $mysqli->real_escape_string($_GET['id']);
				$ge = $mysqli->query("SELECT * FROM ".$prefix."events WHERE id='".$id."'") or die();
				$e = $ge->fetch_assoc();
				if(!isset($_POST['edit'])) {
					echo "
			<form method=\"post\" action=''>
			<div class=\"form-group\">
				<label for=\"title\">Title</label>
				<input type=\"text\" name=\"title\" class=\"form-control\" id=\"title\" placeholder=\"Title\" value=\"".htmlspecialchars($e['title'], ENT_QUOTES, 'UTF-8')."\" required/>
			</div>
			<b>Author:</b> ".$e['author']."<br/>
			<div class=\"form-group\">
				<label for=\"category\">Category</label>
				<select name=\"cat\" class=\"form-control\" id=\"category\">
					<option value=\"ct_news_event_info\">Info</option>
					<option value=\"ct_news_event_lot\">Prize</option>
					<option value=\"ct_news_event_end\">End</option>
				</select>
			</div>
			<div class=\"form-group\">
				<label for=\"status\">Status</label>
				<select name=\"status\" class=\"form-control\" id=\"status\">
					<option value=\"Active\">Active</option>
					<option value=\"Standby\">Standby</option>
				</select>
			</div>
			<textarea name=\"content\" style=\"height:300px;\" id=\"content\">".stripslashes($e['content'])."</textarea><br/>
			<input type=\"submit\" name=\"edit\" class=\"btn btn-primary\" value=\"Edit Event &raquo;\" />
			</form>";
				}else{
					$title = $mysqli->real_escape_string($_POST['title']);
					$cat = $mysqli->real_escape_string($_POST['cat']);
					$status = $mysqli->real_escape_string($_POST['status']);
					$content = $mysqli->real_escape_string($_POST['content']);
					if($title == "") {
						echo "<div class=\"alert alert-danger\">You must enter a title.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					}elseif(empty($cat)) {
						echo "<div class=\"alert alert-danger\">You must select a category.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					}elseif($content == "") {
						echo "<div class=\"alert alert-danger\">You must enter some content.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					}else{
						$u = $mysqli->query("UPDATE ".$prefix."events SET title='".$title."', type='".$cat."', status='".$status."', content='".$content."' WHERE id='".$id."'") or die();
						echo "<div class=\"alert alert-success\">Event was successfully updated.</div><hr/><a href=\"?base=admin\" class=\"btn btn-primary\">&laquo; Go Back</a>";
					}
				}
			}else{
				$ge = $mysqli->query("SELECT * FROM ".$prefix."events ORDER BY id DESC") or die();
				$cge = $ge->num_rows;
				if($cge > 0) {
					echo "Select an event to modify:<hr/>";
					while($e = $ge->fetch_assoc()) {
						echo "
							[".$e['date']."] <a href=\"?base=admin&amp;page=manevent&amp;action=edit&amp;id=".$e['id']."\">".htmlspecialchars($e['title'], ENT_QUOTES, 'UTF-8')."</a> [#".$e['id']."]<hr/>
						";
					}
				} else {
					echo "<div class=\"alert alert-danger\">No Events found!</div>";
				}
			}
		} elseif ($_GET['action']=="pdel") {
			if (!isset($_GET['id'])) {
				echo "No Comment ID Specified.";
			} else if (!is_numeric($_GET['id'])) {
				echo "Invalid Comment ID.";
			} else {
				$eventid = $mysqli->real_escape_string($_GET['id']);
				$query = $mysqli->query("SELECT * FROM ".$prefix."ecomments WHERE id = ".$eventid."") or die();
				$rows = $query->num_rows;
				$fetch = $query->fetch_assoc();

				if ($rows != 1) {
					echo "<div class=\"alert alert-danger\">Comment ID doesn't exist!</div>";
				} else {
					$delete = "DELETE FROM ".$prefix."ecomments WHERE id = ".$eventid."";
					if ($mysqli->query($delete)) {
						redirect("?base=main&page=events&id=".$fetch['eid']);
					} else {
						echo "<div class=\"alert alert-danger\">Error deleting event comment.</div>";
					}
				}

			}

		}elseif($_GET['action']=="del") {
			echo "<h2 class=\"text-left\">Delete an Event</h2><hr/>";
			if(!isset($_POST['del'])) {
				echo "
			<form method=\"post\" action=''>
			<div class=\"form-group\">
				<label for=\"deleteEvent\">Select an event to delete:</label>
				<select name=\"event\" class=\"form-control\" id=\"deleteEvent\">
					<option value=\"\">Please select...</option>";
				$ge = $mysqli->query("SELECT * FROM ".$prefix."events ORDER BY id DESC") or die();
				while($e = $ge->fetch_assoc()) {
					echo "
					<option value=\"".$e['id']."\">#".$e['id']." - ".htmlspecialchars($e['title'], ENT_QUOTES, 'UTF-8')."</option>";
				}
				echo "
				</select>
				<hr/>
				<input type=\"submit\" name=\"del\" value=\"Delete &raquo;\" class=\"btn btn-danger\"/>
				</form>";
			}else{
				$event = $mysqli->real_escape_string($_POST['event']);
				if($event == "") {
					echo "<div class=\"alert alert-danger\">Please select an event to delete.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
				}else{
					$d = $mysqli->query("DELETE FROM ".$prefix."events WHERE id='".$event."'") or die();
					echo "<div class=\"alert alert-success\">The event has been deleted.</div>";
				}
			}
		}elseif($_GET['action']=="lock") {
			echo "<h2 class=\"text-left\">Lock an Event</h2><hr/>";
			if(!isset($_POST['lock'])) {
				echo "
			<form method=\"post\">
			<div class=\"form-group\">
			<label for=\"lockEvent\">Select an event to lock:</label>
				<select name=\"art\" class=\"form-control\" id=\"lockEvent\">
					<option value=\"\">Please select...</option>";
				$ge = $mysqli->query("SELECT * FROM ".$prefix."events WHERE locked = 0 ORDER BY id DESC") or die();
				while($e = $ge->fetch_assoc()) {
					echo "
						<option value=\"".$e['id']."\">#".$e['id']." - ".htmlspecialchars($e['title'], ENT_QUOTES, 'UTF-8')."</option>";
				}
				echo "
				</select>
			</div>
				<hr/>
				<input type=\"submit\" name=\"lock\" value=\"Lock &raquo;\" class=\"btn btn-default\"/>
			</form>";
			}else{
				$art = $mysqli->real_escape_string($_POST['art']);
				if($art == "") {
					echo "<div class=\"alert alert-danger\">Please select an event to lock.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
				}else{
					$d = $mysqli->query("UPDATE ".$prefix."events SET locked = 1 WHERE id='".$art."'") or die();
					echo "<div class=\"alert alert-success\">The event has been locked.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
				}
			}
		} elseif($_GET['action']=="unlock") {
			echo "
			<h2 class=\"text-left\">Unlock an Event</h2><hr/>";
			if(!isset($_POST['unlock'])) {
				echo "
			<form method=\"post\">
			<div class=\"form-group\">
			<label for=\"unlockEvent\">Select an event to unlock:</label>
				<select name=\"art\" class=\"form-control\" id=\"unlockEvent\">
					<option value=\"\">Please select...</option>";
				$ge = $mysqli->query("SELECT * FROM ".$prefix."events WHERE locked = 1 ORDER BY id DESC") or die();
				while($e = $ge->fetch_assoc()) {
					echo "
						<option value=\"".$e['id']."\">#".$e['id']." - ".htmlspecialchars($e['title'], ENT_QUOTES, 'UTF-8')."</option>";
				}
				echo "
				</select>
			</div>
				<hr/>
				<input type=\"submit\" name=\"unlock\" value=\"Unlock &raquo;\" class=\"btn btn-default\"/>
			</form>";
			}else{
				$art = $mysqli->real_escape_string($_POST['art']);
				if($art == "") {
					echo "<div class=\"alert alert-danger\">Please select an event to unlock.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
				}else{
					$d = $mysqli->query("UPDATE ".$prefix."events SET locked = 0 WHERE id='".$art."'") or die();
					echo "<div class=\"alert alert-success\">The event has been unlocked.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
				}
			}
		} else {
			redirect("?base");
		}
	}
}else{
	redirect("?base");
}
?>
<script>
	CKEDITOR.replace( 'content' );
</script>