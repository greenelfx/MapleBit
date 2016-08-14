<?php
if(basename($_SERVER["PHP_SELF"]) == "profile-name.php") {
	die("403 - Access Forbidden");
}
require "assets/libs/gump.class.php";

//eventually move validators to their own file
GUMP::add_validator("exists", function($field, $input, $param = NULL) use ($mysqli, $prefix) {
	return $mysqli->query("SELECT COUNT(*) FROM ".$prefix."profile WHERE $param ='".$mysqli->real_escape_string($input[$field])."'")->fetch_row()[0] == 0;
});
GUMP::add_validator("unallowed", function($field, $input, $param = NULL) {
	if($input[$field] === "checkpname") {
		return false;
	}
	return true;
});

if($_SESSION['pname'] !== "checkpname") {
	echo "<div class=\"alert alert-danger\">Oops! Looks like you already have a profile name!</div>";
	return;
}

if(isset($_POST['create'])) {
	$gump = new GUMP();
	$_POST = $gump->sanitize($_POST);
	$gump->validation_rules(array(
		'name' => 'required|alpha_numeric|exists,name|max_len,16|min_len,4|unallowed,checkpname'
	));
	$gump->filter_rules(array(
	    'name' => 'trim|sanitize_string'
	));
	$validated_data = $gump->run($_POST);

	if($validated_data === false) {
		echo '<div class="alert alert-danger">';
		foreach($gump->get_errors_array() as $error) {
			echo $error . '<br/>';
		}
		echo '</div>';
	} else {
		$i = $mysqli->query("INSERT INTO ".$prefix."profile (accountid, name) VALUES (".$_SESSION['id'].",'".$validated_data['name']."')");
		echo "<div class=\"alert alert-success\">The profile name has been created! You can now go to the community page and edit your public profile.</div>";
		$_SESSION['pname'] = $validated_data['name'];
	}
}
?>

<h2 class="text-left">Set Profile Name</h2><hr/>
Once you've created a profile, other people can view your biography, character, and so on. Note that none of your private information will be shown.<br/>
Please pick a name <i>other</i> than your LoginID!<br/><br/>

<b>Steps:</b><br/>
<b>1.</b> Insert your desired profile name and click submit.<br />
<b>2.</b> If the name is taken, you will be notified. If not, your profile will be created.<br />
<b>3.</b> Afterwards you can go to the community menu and change your profile informations.<br /><br />

<form method="post">
	<input type="text" name="name" placeholder="Profile Name" class="form-control" value="<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>"><br/>
	<input type="submit" name="create" class="btn btn-primary" value="Submit &raquo;" />
</form>
<br/>