<?php
header('Content-Type: application/json');

// generic failure response
$FAILED_RESP = json_encode(array("response" => "failed"));

$username = $mysqli->real_escape_string($_REQUEST['username']);
$password = $_REQUEST['password'];

if ($username == '' || $password == '') {
    echo $FAILED_RESP;
    exit();
}

$statement = $mysqli->prepare("SELECT * FROM accounts WHERE name = ?");
$statement->bind_param("s", $username);
$statement->execute();
$account = $statement->get_result()->fetch_assoc();

if (!isset($account)) {
    echo $FAILED_RESP;
    exit();
}

$salt = in_array('salt', $account) ? $account['salt'] : null;
if (verifyPassword($password, $account['password'], $hash_algorithm, $salt)) {
    $statement = $mysqli->prepare("SELECT * FROM ".$prefix."profile WHERE accountid = ?");
    $statement->bind_param("i", $account['id']);
    $statement->execute();
    $pname = $statement->get_result()->fetch_assoc();

    $_SESSION['pname'] = $pname ? $pname['name'] : 'checkpname';
    $_SESSION['id'] = $account['id'];
    $_SESSION['name'] = $account['name'];
    $_SESSION['mute'] = $account['mute'];
    $_SESSION['email'] = $account['email'];

    if ($account['webadmin'] == "1") {
        $_SESSION['admin'] = $account['webadmin'];
    }
    if ($account['gm'] >= $gmlevel) {
        // Used to abstract gm levels to a boolean
        // For some reason, this is set to the value instead of a bool
        $_SESSION['gm'] = $account['gm'];
    }
    echo json_encode(array("response" => "success"));
} else {
    echo $FAILED_RESP;
}
