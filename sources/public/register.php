<?php
if (basename($_SERVER['PHP_SELF']) == 'register.php') {
    exit('403 - Access Forbidden');
}
require 'assets/libs/recaptcha/autoload.php';
require 'assets/libs/gump.class.php';

if ($recaptcha_public == null || $recaptcha_private == null) {
    echo '<div class="alert alert-danger">Your administrator has not setup reCATPCHA yet!</div>';

    return;
}
GUMP::add_validator('recaptcha', function ($field, $input, $param = null) use ($recaptcha_private) {
    $recaptcha = new \ReCaptcha\ReCaptcha($recaptcha_private);
    $resp = $recaptcha->setExpectedAction('register')
        ->setScoreThreshold(0.5)
        ->verify($input['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

    return $resp->isSuccess();
});

GUMP::add_validator('exists', function ($field, $input, $param = null) use ($mysqli) {
    return $mysqli->query("SELECT COUNT(*) FROM accounts WHERE $param ='".$mysqli->real_escape_string($input[$field])."'")->fetch_row()[0] == 0;
});

if (isset($_SESSION['id'])) {
    echo '<meta http-equiv=refresh content="0; url=?base=ucp">';

    return;
}

if (isset($_POST['submit'])) {
    $gump = new GUMP();
    $_POST = $gump->sanitize($_POST);
    $gump->validation_rules([
        'username'             => 'required|alpha_numeric|exists,name|max_len,12|min_len,4',
        'password'             => 'required|min_len,6',
        'email'                => 'required|valid_email|exists,email',
        'g-recaptcha-response' => 'required|recaptcha',
    ]);
    $gump->filter_rules([
        'username' => 'trim|sanitize_string',
        'password' => 'trim',
        'email'    => 'trim|sanitize_email',
    ]);
    GUMP::set_field_name('g-recaptcha-response', 'reCAPTCHA');
    $validated_data = $gump->run($_POST);

    if ($validated_data === false) {
        echo '<div class="alert alert-danger">';
        foreach ($gump->get_errors_array() as $error) {
            echo $error.'<br/>';
        }
        echo '</div>';
    } else {
        $hashed_password = hashPassword($validated_data['password'], $hash_algorithm, null);
        $insert_user_query = "INSERT INTO accounts (`name`, `password`, `ip`, `email`, `birthday`) VALUES ('".$validated_data['username']."', '".$hashed_password."', '".getRealIpAddr()."', '".$validated_data['email']."', '1990-01-01')";
        $mysqli->query($insert_user_query);
        if (!empty($mysqli->error)) {
            echo '<div class="alert alert-danger"><b>Error!</b> There was a problem registering your account.</div>';
        } else {
            echo '<div class="alert alert-success"><b>Success!</b> Please login, and head to the downloads page to get started!</div><script>$(function() {$("#register").fadeOut();});</script>';
        }
    }
}
?>
<script src="<?php echo 'https://www.google.com/recaptcha/api.js?render='.$recaptcha_public; ?>"></script>
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute("<?php echo $recaptcha_public; ?>", {action: 'register'}).then(function(token) {
            document.getElementById('g-recaptcha-response').value = token;
        });
    });
</script>
<h2 class="text-left">Registration</h2>
<hr />
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
    <input name="g-recaptcha-response" id="g-recaptcha-response" type="hidden" />
    <hr />
    <input type="submit" class="btn btn-primary" name="submit" value="Register &raquo;">
</form>