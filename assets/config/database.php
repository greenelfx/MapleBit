<?php
if(basename($_SERVER["PHP_SELF"]) == "database.php"){
    die("403 - Access Forbidden");
}
//SQL Information
$host['hostname'] = 'localhost'; // Hostname [Usually locahost]
$host['user'] = 'root'; // Database Username [Usually root]
$host['password'] = ''; // Database Password [Leave blank if unsure]
$host['database'] = 'mapleblade'; // Database Name

//Database Prefix
$prefix = "bit_";
// What is your server`s log in port - Don`t change if you aren`t sure.
$loginport = "7575";
// What is your server`s world port - Don`t change if you aren`t sure.
$worldport = "8484";

/* Don`t touch. */
$mysqli = new MySQLi($host['hostname'],$host['user'],$host['password'],$host['database']);

?>
