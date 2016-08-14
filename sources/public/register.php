<?php
if(basename($_SERVER["PHP_SELF"]) == "register.php") {
	die("403 - Access Forbidden");
}
require_once('assets/config/recaptchalib.php');
require "assets/libs/gump.class.php";

GUMP::add_validator("recaptcha", function($field, $input, $param = NULL) use ($privatekey) {
	$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $input['recaptcha_challenge_field'], $input[$field]);
	return $resp->is_valid;
});

GUMP::add_validator("exists", function($field, $input, $param = NULL) use ($mysqli) {
	return $mysqli->query("SELECT COUNT(*) FROM accounts WHERE $param ='".$mysqli->real_escape_string($input[$field])."'")->fetch_row()[0] == 0;
});

if(isset($_SESSION['id'])) {
    echo "<meta http-equiv=refresh content=\"0; url=?base=ucp\">";
    return;
}

if (isset($_POST['submit'])) {
	$gump = new GUMP();
	$_POST = $gump->sanitize($_POST);
	$gump->validation_rules(array(
    	'username' => 'required|alpha_numeric|exists,name|max_len,12|min_len,4',
    	'password' => 'required|min_len,6',
    	'email' => 'required|valid_email|exists,email',
    	'recaptcha_response_field' => 'required|recaptcha',
	));
	$gump->filter_rules(array(
	    'username' => 'trim|sanitize_string',
	    'password' => 'trim',
	    'email'    => 'trim|sanitize_email',
	));
	$validated_data = $gump->run($_POST);
	
	if($validated_data === false) {
		echo '<div class="alert alert-danger">';
		foreach($gump->get_errors_array() as $error) {
			echo $error . '<br/>';
		}
		echo '</div>';
	} else {
		$insert_user_query = "INSERT INTO accounts (`name`, `password`, `ip`, `email`, `birthday`) VALUES ('".$validated_data['username']."', '".sha1($validated_data['password'])."', '".getRealIpAddr()."', '".$validated_data['email']."', '1990-01-01')";
		$mysqli->query($insert_user_query);
		echo '<div class="alert alert-success"><b>Success!</b> Please login, and head to the downloads page to get started!</div><script>$(function() {$("#register").fadeOut();});</script>';
	}
}
?>
<h2 class="text-left">Registration</h2><hr/>
<form action="?base=main&amp;page=register" method="POST" id="register">
	<div class="form-group">
		<label for="inputUsername">Username</label>
		<input type="text" name="username" maxlength="12" class="form-control" id="inputUsername" autocomplete="off" placeholder="Username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>" required>
	</div>
	<div class="form-group">
		<label for="inputPassword">Password</label>
		<input type="password" name="password" maxlength="100" class="form-control" id="inputPassword" autocomplete="off" placeholder="Password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : '' ?>" required>
	</div>
	<div class="form-group">
		<label for="inputEmail">Email</label>
		<input type="email" name="email" class="form-control" id="inputEmail" autocomplete="off" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>" required>
	</div>
	<b>reCAPTCHA</b>
	<?php
		$error = null;
		echo recaptcha_get_html($publickey, $error);
	?>
	<br/>
	<input type="submit" class="btn btn-primary" name="submit" value="Register &raquo;"> 
</form>