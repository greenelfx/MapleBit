<?php
if(basename($_SERVER["PHP_SELF"]) == "profile-name.php"){
    die("403 - Access Forbidden");
}
if($_SESSION['id']){
	if($_SESSION['pname'] == "checkpname"){
		echo "
		<h2 class=\"text-left\">Set Profile Name</h2><hr/>
		Once you've created a profile, other people can view your biography, character, and so on. Note that none of your private information will be shown.<br />
		Please pick a name <i>other</i> than your LoginID!<br/><br/>

		<b>Steps:</b><br />
		<b>1.</b> Insert your desired profile name and click submit.<br />
		<b>2.</b> If the name is taken, you will be notified. If not, your profile will be created.<br />
		<b>3.</b> Afterwards you can go to the community menu and change your profile informations.<br /><br />

		<form method=\"post\">
			<input type=\"text\" name=\"name\" placeholder=\"Profile Name\" class=\"form-control\" required><br/>
			<input type=\"submit\" name=\"create\" class=\"btn btn-primary\" value=\"Submit &raquo;\" />
		</form>
		<br/>";

		if(isset($_POST['create'])){
			$name = $mysqli->real_escape_string($_POST['name']);
			$pcheck = $mysqli->query("SELECT * FROM ".$prefix."profile WHERE name='".$name."'");
			$countpcheck = $pcheck->num_rows;
			if($countpcheck > 0){
				echo "<div class=\"alert alert-danger\">The profile name entered is already in use. Please select another one.</div>";
			}elseif($name == ""){
				echo "<div class=\"alert alert-warning\">Please enter a profile name.</div>";
			}elseif(strlen($name) > 16){
				echo "<div class=\"alert alert-warning\">The profile name must be between 4 and 16 characters.</div>";
			}elseif(strlen($name) < 4){
				echo "<div class=\"alert alert-warning\">The profile name must be between 4 and 16 characters.</div>";
			}elseif(ctype_alnum($name) == false) {
				echo "<div class=\"alert alert-danger\">Special characters are not allowed.</div>";
			}else{
				$i = $mysqli->query("INSERT INTO ".$prefix."profile (accountid, name) VALUES (".$_SESSION['id'].",'".$name."')");
				echo "<div class=\"alert alert-success\">The profile name has been created! You can now go to the community page and edit your public profile.</div>";
				$_SESSION['pname'] = $name;
			}
		}
	}else{
		echo "<div class=\"alert alert-danger\">Oops! Looks like you already have a profile name!</div>";
	}
} else {
	redirect("?base=main");
}
?>