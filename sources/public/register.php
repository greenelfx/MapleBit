<?php 
if(isset($_SESSION['id'])){
    echo "<meta http-equiv=refresh content=\"0; url=?cype=ucp\">";
}
else{
echo "<legend>Registration</legend>";
if (@$_POST["register"] != "1") {
?>
	<form action="?cype=main&page=register" method="POST" role="form">
	<div class="form-group">
		<label for="inputUser">Username</label>
		<input type="text" name="musername" maxlength="12" class="form-control" id="inputUser" required autocomplete="off" placeholder="Username">
	</div>
	<div class="form-group">
		<label for="inputPass">Password</label>
		<input type="password" name="mpass" maxlength="30" class="form-control" id="inputPass" required autocomplete="off" placeholder="Password">
	</div>
	<div class="form-group">
		<label for="inputConfirm">Confirm Password</label>
		<input type="password" name="mpwcheck" maxlength="30" class="form-control" id="inputConfirm" required autocomplete="off" placeholder="Confirm Password">
	</div>
	<div class="form-group">
		<label for="inputEmail">Email</label>
		<input type="email" name="memail" maxlength="50" class="form-control" id="inputEmail" required autocomplete="off" placeholder="Email">
	</div>
	<b>reCaptcha</b>
	<?php
		require_once('assets/config/recaptchalib.php');
		$error = null;
		$publickey = "6LemqAwAAAAAAF4dIpSjTB3GJt1ax0MRQ9FvOX_T";
		$privatekey = "6LemqAwAAAAAAO69RT3j9M1eHPX_ahhmC6Gakuwb";
		echo recaptcha_get_html($publickey, $error);
	?>
	<br/>
		<input type="submit" class="btn btn-primary" name="submit" alt="Register" value="Register &raquo;"/> 
		<input type="hidden" name="register" value="1" />
	</form>
	<br/>
<?php
} else {
	if (!isset($_POST["musername"]) OR
		!isset($_POST["mpass"]) OR
		!isset($_POST["mpwcheck"]) OR
		!isset($_POST["memail"]) OR
		!isset($_POST["recaptcha_response_field"])) {
		die ("<div class=\"alert alert-error\"><b>Error A:</b> Please fill in the correct ReCAPTCHA code!<br/><a href=\"?cype=main&page=register\" class=\"areg\">&laquo; Go Back</a></div>");
	}
	
	$getusername = $mysqli->real_escape_string($_POST["musername"]); # Get Username
	$username = preg_replace("/[^A-Za-z0-9 ]/", '', $getusername); # Escape and Strip
	$getpassword = $mysqli->real_escape_string($_POST["mpass"]); # Get Password
	$password = preg_replace("/[^A-Za-z0-9 ]/", '', $getpassword); # Escape and Strip
	$getconfirm_password = $mysqli->real_escape_string($_POST["mpwcheck"]); # Get Confirm Password
	$confirm_password = preg_replace("/[^A-Za-z0-9 ]/", '', $getconfirm_password); # Escape and Strip
	$email = $mysqli->real_escape_string($_POST["memail"]);
	$birth = "1990-01-01";
	$ip = getRealIpAddr();
	
	$continue = false;
	
	require_once('assets/config/recaptchalib.php');

	$publickey = "6LemqAwAAAAAAF4dIpSjTB3GJt1ax0MRQ9FvOX_T";
	$privatekey = "6LemqAwAAAAAAO69RT3j9M1eHPX_ahhmC6Gakuwb";
	
	$resp = null;
	$error = null;

	if ($_POST["recaptcha_response_field"]) {
			$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
			if ($resp->is_valid) {
					$continue = true;
			}
	}
	
	if (!$continue) {
		echo ("<div class=\"content\"><div class=\"contentbg registerbg\"></div><div class=\"body_register\"><div class=\"alert alert-error\"><b>Error:</b> Please fill in the correct ReCAPTCHA code!<br/><a href=\"?cype=main&page=register\" class=\"areg\">&laquo; Go Back</a></div></div></div>");
	} else {
		$select_user_result = $mysqli->query("SELECT id FROM accounts WHERE name='".$username."' OR email='".$email."' LIMIT 1");
		$returned = $select_user_result->num_rows;	
		if ($returned > 0) {
			echo ("<div class=\"content\"><div class=\"contentbg registerbg\"></div><div class=\"body_register\"><div class=\"alert alert-error\"><b>Error:</b> This username or email is already used!<br/><a href=\"?cype=main&page=register\" class=\"areg\">&laquo; Go Back</a></div></div></div>");
		} else if ($password != $confirm_password) {
			echo ("<div class=\"content\"><div class=\"contentbg registerbg\"></div><div class=\"body_register\"><div class=\"alert alert-error\">Passwords didn't match!<br/><a href=\"?cype=main&page=register\" class=\"areg\">&laquo; Go Back</a></div></div></div>");
		} else if (strlen($password) < 4 || strlen($password) > 12) {
			echo ("<div class=\"content\"><div class=\"contentbg registerbg\"></div><div class=\"body_register\"><div class=\"alert alert-error\">Your password must be between 4-12 characters<br/><a href=\"?cype=main&page=register\" class=\"areg\">&laquo; Go Back</a></div></div></div>");
		} else if (strlen($username) < 4 || strlen($username) > 12) {
			echo ("<div class=\"content\"><div class=\"contentbg registerbg\"></div><div class=\"body_register\"><div class=\"alert alert-error\">Your username must be between 4-12 characters<br/><a href=\"?cype=main&page=register\" class=\"areg\">&laquo; Go Back</a></div></div></div>");
		} else if (!strstr($email, '@')) {
			echo ("<div class=\"content\"><div class=\"contentbg registerbg\"></div><div class=\"body_register\"><div class=\"alert alert-error\">You have filled in a wrong email address<br/><a href=\"?cype=main&page=register\" class=\"areg\">&laquo; Go Back</a></div></div></div>");
		} else {
			//All data is ok
			$insert_user_query = "INSERT INTO accounts (`name`, `password`, `ip`, `email`, `birthday`) VALUES ('".$username."', '".hash("sha1", $password)."', '".$ip."', '".$email."', '".$birth."')";
			$mysqli->query($insert_user_query);
		echo"
			<br/><div class=\"alert alert-success\"><b>Success!</b> Please login, and head to the downloads page to get started!</div>
		";
			}
		}
	}
}
?>