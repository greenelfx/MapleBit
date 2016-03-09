<?php
if(basename($_SERVER["PHP_SELF"]) == "voteconfig.php"){
    die("403 - Access Forbidden");
}
if($_SESSION['admin']){
	if(!isset($_GET['action']) && isset($_GET['action']) != "add" && isset($_GET['action']) != "edit"){
		$query = $mysqli->query("SELECT * from ".$prefix."vote");
		$count = $query->num_rows;
		echo "<h2 class=\"text-left\">Vote Link Configuration</h2><hr/>";
		if($count == 0){
			echo "<div class=\"alert alert-danger\">You don't have any vote links added yet!<hr/><a href=\"?base=admin&amp;page=voteconfig&amp;action=add\" class=\"alert-link\">Add a Vote Site &raquo;</a></div>";
		}
		else {
			echo "<div class=\"alert alert-info\"><a href=\"?base=admin&amp;page=voteconfig&amp;action=add\" class=\"alert-link\">Add a Vote Site &raquo;</a></div>";
			echo "
			<div class=\"table-responsive\">
				<table class=\"table table-no-border\">
					<thead>
						<tr>
							<th>Vote Site</th>
							<th>NX</th>
							<th>Vote Points</th>
							<th>Wait Time</th>
							<th>Edit</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody>";
			while($row = $query->fetch_assoc()){
				echo "<tr>" .
						"<td><a href=\"" . $row['link'] . "\">".$row['name']."</td>" .
						"<td>" . $row['gnx'] . "</td>" .
						"<td>" . $row['gvp'] . "</td>" .
						"<td>" . $row['waittime']/3600 . " hours</td>" .
						"<td><a href=\"?base=admin&amp;page=voteconfig&amp;action=edit&amp;id=" . $row['id'] . "\">Edit &raquo;</td>" .
						"<td><a href=\"?base=admin&amp;page=voteconfig&amp;action=delete&amp;id=" . $row['id'] . "\">Delete &raquo;</td>" .
					"</tr>";
			}
			echo "
				</table>
			</div>";
		}
		if(!isset($_POST['submit'])){
			echo "
			<hr/>
			<h2 class=\"text-left\">Vote Column Configuration</h2><hr/>
			<form method=\"post\">
				<div class=\"form-group\">
					<label for=\"colNX\">NX Column</label><small> What column in the accounts table holds the NX value?</small>
					<input name=\"colnx\" type=\"text\" maxlength=\"100\" class='form-control' id=\"colNX\" value=\"".$colnx."\" required/>
				</div>
				<div class=\"form-group\">
					<label for=\"colVP\">Vote Points Column</label><small> What column in the accounts table holds the Vote Points value?</small>
					<input name=\"colvp\" type=\"text\" maxlength=\"100\" class='form-control' id=\"colVP\" value=\"".$colvp."\" required/>
				</div>
				<input type=\"submit\" name=\"submit\" value=\"Submit &raquo;\" class=\"btn btn-primary\">
			</form>
			";
		}
		else {
			$error = false;
			if(empty($_POST['colnx'])){
				echo "<div class=\"alert alert-danger\">Please enter a value for the NX column.</div>";
				$error = true;
			}
			else {
				$colnx = $mysqli->real_escape_string(strip_tags($_POST['colnx']));
			}
			if(empty($_POST['colvp'])){
				echo "<div class=\"alert alert-danger\">Please enter a value for the Vote Points column.</div>";
				$error = true;
			}
			else {
				$colvp = $mysqli->real_escape_string(strip_tags($_POST['colvp']));
			}
			if(!$error){
				$mysqli->query("UPDATE ".$prefix."properties SET colnx = '$colnx', colvp = '$colvp'");
				echo "<div class=\"alert alert-success\">Successfully updated vote configuration.</div>";
			}
		}
	}
	elseif(isset($_GET['action']) && $_GET['action'] == "add") {
		if(!isset($_POST['submit'])) {
			echo "<h2 class=\"text-left\">Add Vote Site</h2><hr/>
			<form method='post'>
				<div class=\"form-group\">
					<label for=\"voteLink\">Vote Link</label>
					<input name=\"votelink\" type=\"text\" class='form-control' id=\"voteLink\" placeholder=\"http://www.votesite.com\" required/>
				</div>
					<div class=\"form-group\">
						<label for=\"linkName\">Name of Voting Site</label>
						<input name=\"sitename\" type=\"text\" maxlength=\"100\" class='form-control' id=\"linkName\" placeholder=\"VOTESITE100\" required/>
					</div>
				<hr/>
				<div class=\"form-group\">
					<label for=\"nxGiven\">NX</label> <small>(Amount of NX given per vote)</small>
					<input name=\"nx\" type=\"text\" maxlength=\"100\" class='form-control' id=\"nxGiven\" placeholder=\"10000\" required/>
				</div>
				<hr/>
				<div class=\"form-group\">
					<label for=\"vpGiven\">Vote Points</label> <small>(Amount of Vote Points given per vote)</small>
					<input name=\"vp\" type=\"text\" maxlength=\"100\" class='form-control' id=\"vpGiven\" placeholder=\"1\" required/>
				</div>
				<hr/>
				<div class=\"form-group\">
					<label for=\"waitTime\">Waiting Time (In Hours)</label> <small>(How long do players have to wait before voting again? [Usually 6 hours])</small>
					<input name=\"wait\" type=\"text\" maxlength=\"10\" class='form-control' id=\"waitTime\" placeholder=\"6\" required/>
				</div>
			<input type='submit' name='submit' value='Submit &raquo;' class=\"btn btn-primary btn-large\"/>
			</form>";
		}
		else {
			$error = false;
			if(empty($_POST['votelink'])){
				echo "<div class=\"alert alert-danger\">Please enter a vote link.</div>";
				$error = true;
			}
			else {
				$votelink = $mysqli->real_escape_string(strip_tags($_POST['votelink']));
			}
			if(empty($_POST['sitename'])){
				echo "<div class=\"alert alert-danger\">Please enter a site name.</div>";
				$error = true;
			}
			else {
				$sitename = $mysqli->real_escape_string(strip_tags($_POST['sitename']));
			}
			if(empty($_POST['nx']) || !is_numeric($_POST['nx'])){
				echo "<div class=\"alert alert-danger\">Please enter a valid amount for the NX given.</div>";
				$error = true;
			}
			else {
				$givenx = $mysqli->real_escape_string(strip_tags($_POST['nx']));
			}
			if(empty($_POST['vp']) || !is_numeric($_POST['vp'])){
				echo "<div class=\"alert alert-danger\">Please enter a valid amount for the Vote Points given.</div>";
				$error = true;
			}
			else {
				$givevp = $mysqli->real_escape_string(strip_tags($_POST['vp']));
			}
			if(empty($_POST['wait']) || !is_numeric($_POST['wait'])){
				echo "<div class=\"alert alert-danger\">Please enter a valid amount for the waiting time.</div>";
				$error = true;
			}
			else {
				$waittime = $mysqli->real_escape_string(strip_tags($_POST['wait'])) * 3600;
			}
			if(!$error){
				$mysqli->query("INSERT INTO ".$prefix."vote (name, link, gnx, gvp, waittime) VALUES ('$sitename', '$votelink', '$givenx', '$givevp', '$waittime')");
				echo "<div class=\"alert alert-success\">Successfully added vote site.</div><hr/><a href=\"?base=admin&page=voteconfig\" class=\"btn btn-primary\">&laquo; Go Back</a>";
			}
			else {
				echo "<hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
			}
		}
	}
	elseif(isset($_GET['action']) && $_GET['action'] == "edit" && isset($_GET['id'])) {
		$id = $mysqli->real_escape_string(strip_tags($_GET['id']));
		$query = $mysqli->query("SELECT * FROM ".$prefix."vote WHERE id = ".$id."");
		$verify = $query->num_rows;
		if($verify == 1) {
			$row = $query->fetch_assoc();
			$wait = $row['waittime']/3600;
			if(!isset($_POST['submit'])) {
				echo "<h2 class=\"text-left\">Editing Vote Link</h2><hr/>
				<form method='post'>
					<div class=\"form-group\">
						<label for=\"voteLink\">Vote Link</label>
						<input name=\"votelink\" type=\"text\" class='form-control' id=\"voteLink\" placeholder=\"http://www.votesite.com/\" value=\"".$row['link']."\" required/>
					</div>
					<div class=\"form-group\">
						<label for=\"linkName\">Name of Voting Site</label>
						<input name=\"sitename\" type=\"text\" maxlength=\"100\" class='form-control' id=\"linkName\" placeholder=\"VOTESITE100\" value=\"".$row['name']."\" required/>
					</div>
					<hr/>
					<div class=\"form-group\">
						<label for=\"nxGiven\">NX</label> <small>(Amount of NX given per vote)</small>
						<input name=\"nx\" type=\"text\" maxlength=\"100\" class='form-control' id=\"nxGiven\" placeholder=\"10000\" value=\"".$row['gnx']."\" required/>
					</div>
					<hr/>
					<div class=\"form-group\">
						<label for=\"vpGiven\">Vote Points</label> <small>(Amount of Vote Points given per vote)</small>
						<input name=\"vp\" type=\"text\" maxlength=\"100\" class='form-control' id=\"vpGiven\" placeholder=\"1\" value=\"".$row['gvp']."\" required/>
					</div>
					<hr/>
					<div class=\"form-group\">
						<label for=\"waitTime\">Waiting Time (In Hours)</label> <small>(How long do players have to wait before voting again? [Usually 6 hours])</small>
						<input name=\"wait\" type=\"text\" maxlength=\"10\" class='form-control' id=\"waitTime\" placeholder=\"6\" value=\"".$wait."\" required/>
					</div>
				<input type='submit' name='submit' value='Submit &raquo;' class=\"btn btn-primary btn-large\"/>
				</form>";
			}
			else {
				$error = false;
				if(empty($_POST['votelink'])){
					echo "<div class=\"alert alert-danger\">Please enter a vote link.</div>";
					$error = true;
				}
				else {
					$votelink = $mysqli->real_escape_string(strip_tags($_POST['votelink']));
				}
				if(empty($_POST['sitename'])){
					echo "<div class=\"alert alert-danger\">Please enter a site name.</div>";
					$error = true;
				}
				else {
					$sitename = $mysqli->real_escape_string(strip_tags($_POST['sitename']));
				}
				if(empty($_POST['nx']) || !is_numeric($_POST['nx'])){
					echo "<div class=\"alert alert-danger\">Please enter a valid amount for the NX given.</div>";
					$error = true;
				}
				else {
					$givenx = $mysqli->real_escape_string(strip_tags($_POST['nx']));
				}
				if(empty($_POST['vp']) || !is_numeric($_POST['vp'])){
					echo "<div class=\"alert alert-danger\">Please enter a valid amount for the Vote Points given.</div>";
					$error = true;
				}
				else {
					$givevp = $mysqli->real_escape_string(strip_tags($_POST['vp']));
				}
				if(empty($_POST['wait']) || !is_numeric($_POST['wait'])){
					echo "<div class=\"alert alert-danger\">Please enter a valid amount for the waiting time.</div>";
					$error = true;
				}
				else {
					$waittime = $mysqli->real_escape_string(strip_tags($_POST['wait'])) * 3600;
				}
				if(!$error){
					$mysqli->query("UPDATE ".$prefix."vote SET name = '$sitename', link = '$votelink', gnx = '$givenx', gvp = '$givevp', waittime = '$waittime' WHERE id = '$id'");
					echo "<div class=\"alert alert-success\">Successfully edited vote site.</div><hr/><a href=\"?base=admin&page=voteconfig\" class=\"btn btn-primary\">&laquo; Go Back</a>";
				}
				else {
					echo "<hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
				}
			}
		} else {
			redirect ("?base=admin&page=voteconfig");
		}
	}
	elseif(isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['id'])) {
		$id = $mysqli->real_escape_string(strip_tags($_GET['id']));
		$query = $mysqli->query("DELETE FROM ".$prefix."vote WHERE id = ".$id."");
		redirect ("?base=admin&page=voteconfig");
	}
	else {
		redirect ("?base=admin&page=voteconfig");
	}
} else {
	redirect ("?base=main");
}
?>