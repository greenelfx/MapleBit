<?php

if (basename($_SERVER['PHP_SELF']) == 'logout.php') {
    exit('403 - Access Forbidden');
}

if ($_SESSION['id']) {
    session_destroy();
    $_SESSION = [];
}
redirect('?base=main');
