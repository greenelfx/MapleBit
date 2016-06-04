<?php
if(basename($_SERVER["PHP_SELF"]) == "pages.php") {
	die("403 - Access Forbidden");
}
?>
<script src="assets/libs/ckeditor/ckeditor.js"></script>
<?php
if(isset($_SESSION['id'])) {
	if(isset($_SESSION['admin'])) {
		if(empty($_GET['action'])) {
			echo "<h2 class=\"text-left\">Manage Pages</h2><hr/>
				<a href=\"?base=admin&amp;page=pages&amp;action=add\"><b>Add Page &raquo;</b></a><br/>
				<a href=\"?base=admin&amp;page=pages&amp;action=edit\">Edit Page</a><br/>
				<a href=\"?base=admin&amp;page=pages&amp;action=del\">Delete Page</a><br/>
			";
		}
		elseif($_GET['action']=="add") {
			echo "
			<h2 class=\"text-left\">Add Page</h2><hr/>";
			if($_SESSION['pname'] == "checkpname") {
				echo "<div class=\"alert alert-danger\">You must assign a profile name before you can enter this page.</div>";
			}else{
				if(!isset($_POST['add'])) {
					echo "
			<form method=\"post\" role=\"form\">
			<div class=\"form-group\">
				<label for=\"title\">Title</label>
				<input type=\"text\" name=\"title\" class=\"form-control\" id=\"title\" placeholder=\"Title\" required/>
				<span class=\"help-block\"You may use <b>only</b> numbers, letters, and spaces)</span>
			</div>
			<div class=\"form-group\">
				<label for=\"slug\">Slug</label>
				<input type=\"text\" name=\"slug\" class=\"form-control\" id=\"slug\" placeholder=\"Slug\" required/>
				<span class=\"help-block\">URL friendly name (one word is required, you may use <b>only</b> numbers and letters)</span>
			</div>
			<b>Author:</b> ".$_SESSION['pname']."<br/>
			<div class=\"checkbox\">
				<label>
					<input type=\"checkbox\" name=\"visible\" checked> Show Page on Navigation Bar
				</label>
			</div>
			<hr/>
			<b>Page Content:</b> <small>Note that the page header is automatically added. If you're adding a donate or chat page, press &#34;Source&#34;</small>
			<textarea name=\"content\" style=\"height:300px;\" class=\"form-control\" id=\"content\"></textarea><br/>
			<input type=\"submit\" name=\"add\" class=\"btn btn-primary\" value=\"Add Page &raquo;\" />
		</form>";
				}else{
					$gettitle = $mysqli->real_escape_string($_POST['title']);
					$title = preg_replace("/[^0-9a-zA-Z ]/", "", $gettitle); # Escape and Strip all but alphanumeric and space
					$gslug = $mysqli->real_escape_string($_POST['slug']);
					$removespaceslug = preg_replace('/\s+/', '_', $gslug);
					$slug = preg_replace("/[^A-Za-z0-9 ]/", '', $removespaceslug); # Escape and Strip
					$author = $_SESSION['pname'];
					$content = $mysqli->real_escape_string($_POST['content']);
					if(isset($_POST['visible'])) {
						$visible = 1;
					} else {
						$visible = 0;
					}
					if($title == "") {
						echo "<div class=\"alert alert-danger\">You must enter a title.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					}elseif($content == "") {
						echo "<div class=\"alert alert-danger\">You must enter some content.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					}elseif($slug == "") {
						echo "<div class=\"alert alert-danger\">You must enter a slug.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					}else{
						$i = $mysqli->query("INSERT INTO ".$prefix."pages (title, slug, author, content, visible) VALUES ('".$title."','".$slug."','".$_SESSION['pname']."','".$content."', '".$visible."')") or die();
						echo "<div class=\"alert alert-success\">Your pages has been created. You may access it at <a href=\"".$siteurl."\?base=main&page=".$slug."\">here</a></div><hr/><a href=\"?base=admin\" class=\"btn btn-primary\">&laquo; Go Back</a>";
					}
				}
			}
			echo "
		";
		}elseif($_GET['action']=="edit") {
			echo "
			<h2 class=\"text-left\">Edit Page</h2><hr/>";
			if(isset($_GET['id'])) {
				$id = $mysqli->real_escape_string($_GET['id']);
				$gp = $mysqli->query("SELECT * FROM ".$prefix."pages WHERE id='".$id."'") or die();
				$p = $gp->fetch_assoc();
				if($p['visible'] == 1) {
					$checked = "checked";
				} else {
					$checked = "";
				}
				if(!isset($_POST['edit'])) {
					echo "
			<form method=\"post\" action=''>
			<div class=\"form-group\">
				<label for=\"title\">Title</label>
				<input type=\"text\" name=\"title\" class=\"form-control\" id=\"title\" placeholder=\"Title\" value=\"".$p['title']."\" required/>
			</div>
			<div class=\"form-group\">
				<label for=\"slug\">Slug</label>
				<input type=\"text\" name=\"slug\" class=\"form-control\" id=\"slug\" placeholder=\"Slug\" value=\"".$p['slug']."\" required/>
				<span class=\"help-block\">URL friendly name (one word is required, you may use <b>only</b> numbers and letters)</span>
			</div>
			<b>Author:</b> ".$p['author']."<br/>
			<div class=\"checkbox\">
				<label>
					<input type=\"checkbox\" name=\"visible\" ".$checked."> Show Page on Navigation Bar
				</label>
			</div>
			<textarea name=\"content\" style=\"height:300px;\" id=\"content\">".stripslashes($p['content'])."</textarea><br/>
			<input type=\"submit\" name=\"edit\" class=\"btn btn-primary\" value=\"Edit Page &raquo;\" />
			</form>";
				}else{
					$title = $mysqli->real_escape_string($_POST['title']);
					$content = $mysqli->real_escape_string($_POST['content']);
					$gslug = $mysqli->real_escape_string($_POST['slug']);
					$slug = preg_replace('/\s+/', '_', $gslug);
					if(isset($_POST['visible'])) {
						$visible = 1;
					} else {
						$visible = 0;
					}
					if($title == "") {
						echo "<div class=\"alert alert-danger\">You must enter a title.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					}elseif($content == "") {
						echo "<div class=\"alert alert-danger\">You must enter some content.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					}elseif($slug == "") {
						echo "<div class=\"alert alert-danger\">You must enter a slug.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					}else{
						$u = $mysqli->query("UPDATE ".$prefix."pages SET title='".$title."', slug = '".$slug."', content='".$content."', visible = '".$visible."' WHERE id='".$id."'") or die();
						echo "<div class=\"alert alert-success\">Page Updated. You may access it <a href=\"".$siteurl."\?base=main&page=".$slug."\">here</a></div><hr/><a href=\"?base=admin&page=pages\" class=\"btn btn-primary\">&laquo; Go Back</a>";
					}
				}
			}else{
				$gp = $mysqli->query("SELECT * FROM ".$prefix."pages ORDER BY id DESC") or die();
				$cgp = $gp->num_rows;
				if($cgp > 0) {
					echo "Select a page to modify:<hr/>";
					while($p = $gp->fetch_assoc()) {
						echo "
							<a href=\"?base=admin&amp;page=pages&amp;action=edit&amp;id=".$p['id']."\">".$p['title']."</a><hr/>
						";
					}
				} else {
					echo "<div class=\"alert alert-danger\">No Pages found!</div>";
				}
			}
		}elseif($_GET['action']=="del") {
			echo "<h2 class=\"text-left\">Delete a Page</h2><hr/>";
			if(!isset($_POST['del'])) {
				echo "
			<form method=\"post\" action=''>
			<div class=\"form-group\">
				<label for=\"deletePage\">Select a page to delete:</label>
				<select name=\"page\" class=\"form-control\" id=\"deletePage\">
					<option value=\"\">Please select...</option>";
				$gp = $mysqli->query("SELECT * FROM ".$prefix."pages ORDER BY id DESC") or die();
				while($p = $gp->fetch_assoc()) {
					echo "
					<option value=\"".$p['id']."\">".$p['title']."</option>";
				}
				echo "
				</select>
				<hr/>
				<input type=\"submit\" name=\"del\" value=\"Delete &raquo;\" class=\"btn btn-danger\"/>
				</form>";
			}else{
				$page = $mysqli->real_escape_string($_POST['page']);
				if($page == "") {
					echo "<div class=\"alert alert-danger\">Please select a page to delete.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
				}else{
					$d = $mysqli->query("DELETE FROM ".$prefix."pages WHERE id='".$page."'") or die();
					echo "<div class=\"alert alert-success\">The page has been deleted.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
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