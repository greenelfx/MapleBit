<?php
if(basename($_SERVER["PHP_SELF"]) == "account-settings.php") {
    die("403 - Access Forbidden");
}

echo "<h2 class=\"text-left\">Account Settings</h2><hr/>";
if(!isset($_POST['modify'])) {
	$query = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$_SESSION['id']."'") or die();
	$row = $query->fetch_assoc();
	echo "
		<div class=\"alert alert-warning\">If you want to keep your current password, leave the password fields blank! <a class=\"close\" data-dismiss=\"alert\" href=\"#\" aria-hidden=\"true\">&times;</a></div>
		<form method=\"post\" role=\"form\">
			<b><abbr title=\"You can't change this!\">Username</abbr></b>
				".$row['name']."
		<div class=\"form-group\">
			<label for=\"cPassword\">Current Password</label>
			<input type=\"password\" class=\"form-control\" id=\"cPassword\" placeholder=\"Current Password\" name=\"current\" />
		</div>
		<div class=\"form-group\">
			<label for=\"nPassword\">New Password</label>
			<input type=\"password\" class=\"form-control\" id=\"nPassword\" placeholder=\"New Password\" name=\"password\" />
		</div>
		<div class=\"form-group\">
			<label for=\"coPassword\">Confirm Password</label>
			<input type=\"password\" class=\"form-control\" id=\"coPassword\" placeholder=\"Confirm Password\" name=\"copassword\" />
		</div>
		<div class=\"form-group\">
			<label for=\"Email\">Email</label>
			<input type=\"email\" class=\"form-control\" id=\"Email\" placeholder=\"email@dot.com\" maxlength=\"50\" name=\"email\" value=\"".$row['email']."\" />
		</div>
		<div class=\"form-group\">
			<label for=\"Birthday\">Birthday</label>
			<input type=\"text\" class=\"form-control\" id=\"Birthday\" placeholder=\"1990-01-01\" name=\"birth\" value=\"".$row['birthday']."\" />
		</div>
			<input type=\"submit\" name=\"modify\" class=\"btn btn-primary\" value=\"Modify &raquo;\" />
		</form><br/>
	";
}
else {
	$u = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$_SESSION['id']."'") or die();
	$userz = $u->fetch_assoc();
	$current = $mysqli->real_escape_string($_POST['current']);
	$pass = $mysqli->real_escape_string($_POST['password']);
	$confirm_pass = $mysqli->real_escape_string($_POST['copassword']);
	$email = $mysqli->real_escape_string($_POST['email']);
	$birth = $mysqli->real_escape_string($_POST['birth']);
	$continue = true;

	// If the current password field is filled in, the user is changing their password
	if($current) {
		// Ensure that current password matches with the record in the database
		if($userz['password'] == hash('sha512',$current.$userz['salt']) || sha1($current) == $userz['password']) {
			// If the new password or confirm password fields are empty
			if(empty($pass) || empty($confirm_pass)) {
				echo "<div class=\"alert alert-danger\">Please fill in the password and confirm password fields.</div>";
				$continue = false;
			}
			// Check new password and confirm password fields for equality
			else if($pass != $confirm_pass) {
				echo "<div class=\"alert alert-danger\">Passwords do not match.</div>";
				$continue = false;
			}
			// Ensure proper length for password
			else if(strlen($pass) < 6 || strlen($pass) > 12) {
				echo "<div class=\"alert alert-danger\">Your new password must be between 6 and 12 characters.</div>";
				$continue = false;
			}
		}
		else {
			echo "<div class=\"alert alert-danger\">The password you have entered is incorrect.</div>";
			$continue = false;
		}
	}

	// If the email is empty or does not match a builtin filter
	if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo "<div class=\"alert alert-danger\">Please supply a valid email address.</div>";
		$continue = false;
	}
	// If the birthday doesn't match a specific format
	if($birth != date('Y-m-d', strtotime($birth))) {
		echo "<div class=\"alert alert-danger\">Please supply a valid birthday. Format is YYYY-MM-DD.</div>";
		$continue = false;
	}
	
	if(!$continue) {
		echo "<hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
	}
	else {
		$check_email = $mysqli->query("SELECT id FROM accounts WHERE email='".$email."' AND id != '".$userz['id']."' LIMIT 1");
		if($check_email->num_rows) {
			echo "<div class=\"alert alert-danger\">This email has already been used.</div>";
		}
		else {
			if($current) {
				$u = $mysqli->query("UPDATE `accounts` SET `password`='".sha1($pass)."' WHERE `name`='".$userz['name']."'") or die();
			}
			$u = $mysqli->query("UPDATE `accounts` SET `email`='".$email."',`birthday`='".$birth."' WHERE `name`='".$userz['name']."'") or die();
			$_SESSION['email'] = $email;
			echo "<div class=\"alert alert-success\">Your changes have successfully been saved.</div>";
		}
	}
}