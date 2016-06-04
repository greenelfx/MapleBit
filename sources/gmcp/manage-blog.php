<?php
if(basename($_SERVER["PHP_SELF"]) == "manage-blog.php") {
    die("403 - Access Forbidden");
}
?>
<script src="assets/libs/ckeditor/ckeditor.js"></script>
<?php
if(isset($_SESSION['id'])) {
	if(isset($_SESSION['gm']) || isset($_SESSION['admin'])) {
		if($_SESSION['pname'] == NULL || $_SESSION['pname'] == "checkpname") {
			echo "<div class=\"alert alert-danger\"><b>Error:</b> You need to assign a profile name before you do this.<hr/><a href=\"?base=ucp&page=profname\" class=\"alert-link\">Set Profile Name &raquo;</a></div>";
		}else{
			if($_GET['action']=="add") {
				if(!isset($_POST['add'])) {
				echo "<h2 class=\"text-left\">Add a GM Blog</h2><hr/>";
					echo "
				<form method=\"post\">
					You can add a blog entry about your day on <b>$servername</b>, how many hackers you banned, or anything!<br />
					Your imagination sets the limit.<hr/>
				<div class=\"form-group\">
					<label for=\"title\">Title</label>
					<input type=\"text\" name=\"title\" class=\"form-control\" id=\"title\" placeholder=\"Title\" required/>
				</div>
				<b>Author:</b> ".$_SESSION['pname']."<br/>
				<b>Content:</b><br/>
				<textarea name=\"content\" style=\"height:300px;width:100%;\" class=\"form-control\"></textarea><br/>
				<div class=\"alert alert-info\">You may edit or delete your blog entry later on.</div>
				<input type=\"submit\" name=\"add\" value=\"Add Blog Entry &raquo;\" class=\"btn btn-primary\"/>
				</form>";
				}else{
					$title = $mysqli->real_escape_string($_POST['title']);
					$date = date("m.d");
					$content = $mysqli->real_escape_string($_POST['content']);
					if($title == "") {
						echo "<div class=\"alert alert-danger\">You must enter a title.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					}elseif($content == "") {
						echo "<div class=\"alert alert-danger\">You must enter some content.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					}else{
						$i = $mysqli->query("INSERT INTO ".$prefix."gmblog (title, author, date, content) VALUES ('".$title."','".$_SESSION['pname']."','".$date."','".$content."')") or die(mysql_error());
						echo "<div class=\"alert alert-success\">Your blog entry has been posted.</div><hr/><a href=\"?base=gmcp\" class=\"btn btn-primary\">&laquo; Go Back</a>";
					}
				}
			}elseif($_GET['action']=="edit") {
				echo "<h2 class=\"text-left\">Edit a Blog</h2><hr/>";
				if(isset($_GET['id'])) {
					$id = $mysqli->real_escape_string($_GET['id']);
					$gb = $mysqli->query("SELECT * FROM ".$prefix."gmblog WHERE id='".$id."'") or die();
					$b = $gb->fetch_assoc();
					if($_SESSION['pname'] == $b['author'] || isset($_SESSION['admin'])) {
						if(!isset($_POST['edit'])) {
							echo "
				<form method=\"post\">
					<div class=\"form-group\">
						<label for=\"title\">Title</label>
						<input type=\"text\" name=\"title\" class=\"form-control\" id=\"title\" placeholder=\"Title\" value=\"".htmlspecialchars($b['title'], ENT_QUOTES, 'UTF-8')."\" required/>
					</div>
					<b>Author:</b> ".$b['author']." <br/>
					<b>Content:</b><br/>
					<textarea name=\"content\" style=\"height:300px;width:100%;\" class=\"form-control\">".htmlspecialchars($b['title'], ENT_QUOTES, 'UTF-8')."</textarea><br/>
					<div class=\"alert alert-info\">You may edit or delete your blog entry later on.</div><br/>
					<input type=\"submit\" name=\"edit\" value=\"Edit Blog Entry\" class=\"btn btn-primary\"/>
				</form>";
						}else{
							$title = $mysqli->real_escape_string($_POST['title']);
							$content = $mysqli->real_escape_string($_POST['content']);
							if($title == "") {
								echo "<div class=\"alert alert-danger\">You must enter a title.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
							}elseif($content == "") {
								echo "<div class=\"alert alert-danger\">You must enter some content.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
							}else{
								$u = $mysqli->query("UPDATE ".$prefix."gmblog SET title='".$title."', content='".$content."' WHERE id='".$id."'") or die();
								echo "The Blog entry has been updated.<hr/><a href=\"?base=gmcp\" class=\"btn btn-primary\">&laquo; Go Back</a>";
							}
						}
					}else{
						echo "<div class=\"alert alert-error\">This blog entry does not belong to you.</div><hr/><a href=\"?base=gmcp\" class=\"btn btn-primary\">&laquo; Go Back</a>";
					}
				}else{
					if(isset($_SESSION['gm']) || isset($_SESSION['admin'])) {
						$gb = $mysqli->query("SELECT * FROM ".$prefix."gmblog WHERE author='".$_SESSION['pname']."' ORDER BY id ASC") or die();
						if($gb->num_rows > 0) {
							$i = 1;
							echo "
							<table class=\"table\">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Date</th>
									<th>Views</th>
								</tr>
							</thead>
							<tbody>
							";
							while($b = $gb->fetch_assoc()) {
								echo "
								<tr>
									<th scope=\"row\">".$i++."</th>
									<td><a href=\"?base=gmcp&amp;page=manblog&action=edit&id=".$b['id']."\">".htmlspecialchars($b['title'], ENT_QUOTES, 'UTF-8')." &raquo;</a></td>
									<td>".$b['date']."</td>
									<td>".$b['views']."</td>
								</tr>";
							}
							echo "
							</tbody>
							</table>
							";
						}
						else {
							echo "<div class=\"alert alert-info\">No blogs found!</div>";
						}
					}
					if(isset($_SESSION['admin'])) {
						echo "<hr/>
						<h3>Edit all Blogs <small>(Administrators Only)</small></h3>";
						$gab = $mysqli->query("SELECT * FROM ".$prefix."gmblog ORDER BY id ASC") or die();
						if($gab->num_rows > 0) {
							$i = 1;
							echo "
							<table class=\"table\">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Author</th>
									<th>Date</th>
									<th>Views</th>
								</tr>
							</thead>
							<tbody>
							";
							while($b = $gab->fetch_assoc()) {
								echo "
								<tr>
									<th scope=\"row\">".$i++."</th>
									<td><a href=\"?base=gmcp&amp;page=manblog&action=edit&id=".$b['id']."\">".htmlspecialchars($b['title'], ENT_QUOTES, 'UTF-8')." &raquo;</a></td>
									<td><a href=\"?base=main&amp;page=members&name=".$b['author']."\">".$b['author']." &raquo;</a></td>
									<td>".$b['date']."</td>
									<td>".$b['views']."</td>
								</tr>";
							}
							echo "
							</tbody>
							</table>
							";
						}
						else {
							echo "<div class=\"alert alert-info\">No blogs found!</div>";
						}
					}
				}

			} else if ($_GET['action']=="pdel") {
				echo "<h2 class=\"text-left\">Delete a Blog Comment</h2><hr/>";
				if (!isset($_GET['id'])) {
					echo "No Blog Comment ID Specified.";
				} else if (!is_numeric($_GET['id'])) {
					echo "Invalid Blog Comment ID.";
				} else {
					$gmbid = $mysqli->real_escape_string($_GET['id']);
					$query = $mysqli->query("SELECT * FROM ".$prefix."bcomments WHERE id = ".$gmbid."") or die();
					$rows = $query->num_rows;
					$fetch = $query->fetch_assoc();

					if ($rows != 1) {
						echo "<div class=\"alert alert-danger\">This comment doesn't exist!</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					} else {
						$delete = "DELETE FROM ".$prefix."bcomments WHERE id = ".$gmbid."";
						if ($mysqli->query($delete)) {
							redirect("?base=main&page=gmblog&id=".$fetch['bid']);
						} else {
							echo "<div class=\"alert alert-danger\">Error deleting blog comment.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
						}
					}
				}
			}elseif($_GET['action']=="del") {
				echo "<h2 class=\"text-left\">Delete a GM Blog</h2><hr/>";
				if(!isset($_POST['del'])) {
					echo "
					<form method=\"post\">
						<div class=\"form-group\">
							<label for=\"delBlog\">Please Select a Blog:</label>
						<select name=\"blog\" id=\"delBlog\" class=\"form-control\">
							<option value=\"\">Please select...</option>
								<optgroup label=\"Your Blog Entries\">";
					$gb = $mysqli->query("SELECT * FROM ".$prefix."gmblog WHERE author='".$_SESSION['pname']."' ORDER BY id ASC") or die();
					while($b = $gb->fetch_assoc()) {
						echo "
									<option value=\"".$b['id']."\">[".$b['date']."] ".htmlspecialchars($b['title'], ENT_QUOTES, 'UTF-8')."</option>";
					}
					echo "
								</optgroup>";
					if($_SESSION['admin']) {
						echo "
								<optgroup label=\"Administrator\">";
						$gb = $mysqli->query("SELECT * FROM ".$prefix."gmblog ORDER BY author, id ASC") or die();
						while($b = $gb->fetch_assoc()) {
							echo "
									<option value=\"".$b['id']."\">[".$b['date']."] ".htmlspecialchars($b['title'], ENT_QUOTES, 'UTF-8')."</option>";
						}
					}
					echo "
								</optgroup>
						</select>
						</div>
						<input type=\"submit\" name=\"del\" value=\"Delete &raquo;\" class=\"btn btn-default\"/>
					</form>
					";
				}else{
					$blog = $mysqli->real_escape_string($_POST['blog']);
					if($blog == "") {
						echo "<div class=\"alert alert-danger\">Please select a blog entry to delete.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					}else{
						$d = $mysqli->query("DELETE FROM ".$prefix."gmblog WHERE id='".$blog."'") or die();
						echo "<div class=\"alert alert-success\">The blog entry has been deleted.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					}
				}
		}elseif($_GET['action']=="lock") {
			echo "
			<h2 class=\"text-left\">Lock a Blog</h2><hr/>";
			if(!isset($_POST['lock'])) {
				echo "
			<form method=\"post\">
				<div class=\"form-group\">
					<label for=\"selectBlog\">Please Select a Blog:</label>
						<select name=\"art\" id=\"selectBlog\" class=\"form-control\">
							<option value=\"\">Please select...</option>";
				$gn = $mysqli->query("SELECT * FROM ".$prefix."gmblog WHERE author='".$_SESSION['pname']."' AND locked = 0 ORDER BY id DESC") or die();
				while($n = $gn->fetch_assoc()) {
					echo "
							<option value=\"".$n['id']."\">#".$n['id']." - ".htmlspecialchars($n['title'], ENT_QUOTES, 'UTF-8')."</option>";
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
					echo "<div class=\"alert alert-danger\">Please select a blog to lock.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
				}else{
					$d = $mysqli->query("UPDATE ".$prefix."gmblog SET locked = 1 WHERE id='".$art."' AND author='".$_SESSION['pname']."'") or die();
					echo "<div class=\"alert alert-success\">The blog has been locked.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
				}
			}
		}elseif($_GET['action']=="unlock") {
			echo "
			<h2 class=\"text-left\">Unlock a Blog</h2><hr/>";
			if(!isset($_POST['unlock'])) {
				echo "
			<form method=\"post\" action=''>
				<div class=\"form-group\">
					<label for=\"selectBlog\">Please Select a Blog:</label>
						<select name=\"art\" id=\"selectBlog\" class=\"form-control\">
							<option value=\"\">Please select...</option>";
				$gn = $mysqli->query("SELECT * FROM ".$prefix."gmblog WHERE author='".$_SESSION['pname']."' AND locked = 1 ORDER BY id DESC") or die();
				while($n = $gn->fetch_assoc()) {
					echo "
							<option value=\"".$n['id']."\">#".$n['id']." - ".htmlspecialchars($n['title'], ENT_QUOTES, 'UTF-8')."</option>";
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
					echo "<div class=\"alert alert-danger\">Please select a blog to unlock.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
				}else{
					$d = $mysqli->query("UPDATE ".$prefix."gmblog SET locked = 0 WHERE id='".$art."' AND author='".$_SESSION['pname']."'") or die();
					echo "<div class=\"alert alert-success\">The blog has been unlocked.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
				}
			}
		}
	}
	}else{
		redirect("?base");
	}
}else{
	redirect("?base");
}
?>
<script>
	CKEDITOR.replace( 'content' );
</script>