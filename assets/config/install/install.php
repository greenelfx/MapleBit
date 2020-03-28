<?php
session_start()
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>MapleBit Installation</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet" type="text/css" />
    <script>
        function goBack() {
            window.history.back()
        }
    </script>
    <link rel="icon" href="../favicon.ico" type="image/x-icon" />
</head>

<body>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col col-lg-7">
                <div class="card">
                    <h5 class="card-header">MapleBit Installation</h5>
                    <div class="card-body">
                        <?php
                        if (isset($_GET['install'])) {
                            $install = $_GET['install'];
                        } else {
                            $install = "";
                        }
                        if (file_exists('installdone.txt')) {
                            echo "<div class=\"alert alert-info\">Oops! Looks like MapleBit has already been installed! If you'd like, you can delete everything in the install folder, except for installdone.txt</div>";
                        } else {
                            switch ($install) {
                                case null:
                                    echo '
				<h5 class="card-title">Welcome to MapleBit.</h5>
				<hr/>
				<p class="card-text">Before you can use your new website, MapleBit must be installed. Please make sure you have the following information handy:</p>
				<ul>
					<li>Database name</li>
					<li>Database username</li>
					<li>Database password</li>
					<li>Database host <small>(Usually localhost)</small></li>
					<li>Table Prefix <small>(Optional)</small></li>
				</ul>
				<hr/>
				<form action="?install=1" method="post">
					<input type="submit" class="btn btn btn-outline-primary float-right" value="Begin &raquo;" />
				</form>
				<br/><br/>';
                                    exit;
                                    break;
                                case 1:
                                    echo '
				<h5 class="card-title">Configure your database settings</h5>
				<hr/>
				<form action="?install=2" method="post" role="form">
					<div class="form-group">
						<label for="inputHost" class="col-lg-4 control-label">MySQL Host</label>
						<div class="col-lg-5">
							<input type="text" class="form-control" id="inputHost" placeholder="localhost" name="host" required>
						</div>
					</div>
					<div class="form-group">
						<label for="inputDB" class="col-lg-4 control-label">Database Name</label>
						<div class="col-lg-5">
							<input type="text" class="form-control" id="inputDB" placeholder="Database Name" name="DB" required>
						</div>
					</div>
					<div class="form-group">
						<label for="inputDBU" class="col-lg-4 control-label">Database Username</label>
						<div class="col-lg-5">
							<input type="text" class="form-control" id="inputDBU" placeholder="Database Username" name="DBuser" required>
						</div>
					</div>
					<div class="form-group">
						<label for="inputDBPWD" class="col-lg-4 control-label">Database Password</label>
						<div class="col-lg-5">
							<input type="text" class="form-control" id="inputDBPWD" placeholder="Database Password" name="DBpass">
						</div>
					</div>
					<div class="form-group">
						<label for="inputPrefix" class="col-lg-4 control-label">Database Prefix</label>
						<div class="col-lg-5">
							<input type="text" class="form-control" id="inputPrefix" placeholder="Database Prefix" name="DBprefix" value="bit_">
						</div>
					</div>
					<hr/>
					<input type="submit" class="btn btn-outline-primary float-right" value="Continue &raquo;"/>
				</form>
				<br/><br/>';
                                    break;
                                case 2:
                                    echo '<h5 class="card-title">SQL Connection Check</h5><hr/>';
                                    error_reporting(0);
                                    $host = $_POST["host"];
                                    $db = $_POST["DB"];
                                    $dbuser = $_POST["DBuser"];
                                    $dbpass = $_POST["DBpass"];
                                    $dbprefix = $_POST["DBprefix"];
                                    $mysqli = new mysqli($host, $dbuser, $dbpass, $db);
                                    if ($mysqli->connect_errno) {
                                        printf("<div class=\"alert alert-danger\">Connect failed: %s\n", $mysqli->connect_error);
                                        echo "</div><hr/><a href=\"?install=1\" class=\"btn btn-danger float-right\" value=\"Continue &raquo;\">&laquo; Go Back</a><br/><br/>";
                                        exit();
                                    }
                                    if ($host == "" || $db == "") {
                                        echo "<div class=\"alert alert-danger\">Please enter the correct information</div>";
                                        echo "<hr/><a href=\"?install=1\" class=\"btn btn-danger float-right\" value=\"Continue &raquo;\">&laquo; Go Back</a><br/><br/>";
                                        exit();
                                    }
                                    file_put_contents('../database.php', '<?php
if(basename($_SERVER["PHP_SELF"]) == "database.php") {
    die("403 - Access Forbidden");
}
//SQL Information
$host[\'hostname\'] = \'' . $host . '\'; // Hostname [Usually locahost]
$host[\'user\'] = \'' . $dbuser . '\'; // Database Username [Usually root]
$host[\'password\'] = \'' . $dbpass . '\'; // Database Password [Leave blank if unsure]
$host[\'database\'] = \'' . $db . '\'; // Database Name

//Database Prefix
$prefix = "' . $dbprefix . '";
// What is your server`s log in port - Don`t change if you aren`t sure.
$loginport = "7575";
// What is your server`s world port - Don`t change if you aren`t sure.
$worldport = "8484";

/* Don`t touch. */
$mysqli = new MySQLi($host[\'hostname\'],$host[\'user\'],$host[\'password\'],$host[\'database\']);

?>
                        ');
                                    echo '
                        <div class="alert alert-success">Successfully connected to MySQL.</div>
                        <hr />
                        <form action="?install=3" method="post" `>
                            <input type="submit" class="btn btn-outline-primary float-right"
                                value="Execute SQL &raquo;" />
                        </form>
                        <br /><br />';
                                    break;
                                case 3:
                                    include '../database.php';
                                    if ($mysqli->query("SHOW TABLES LIKE 'accounts'")->num_rows != 1) {
                                        echo "
                        <hr />
                        <div class=\"alert alert-danger\">(1) You need to have a valid game database installed before
                            installing MapleBit!</div>
                        <hr /><a href=\"?install=1\" class=\"btn btn-danger btn-lg\" value=\"Continue &raquo;\"
                            style=\"float:right\">&laquo; Go Back</a><br /><br />";
                                        exit();
                                    }
                                    if ($mysqli->query("SHOW TABLES LIKE 'characters'")->num_rows != 1) {
                                        echo "
                        <hr />
                        <div class=\"alert alert-danger\">(2) You need to have a valid game database installed before
                            installing MapleBit!</div>
                        <hr /><a href=\"?install=1\" class=\"btn btn-danger btn-lg\" value=\"Continue &raquo;\"
                            style=\"float:right\">&laquo; Go Back</a><br /><br />";
                                        exit();
                                    }
                                    $queryaccounts = $mysqli->query("SELECT * FROM `accounts`");
                                    $getcolumns = $queryaccounts->fetch_assoc();

                                    if (!isset($getcolumns['webadmin'])) {
                                        $mysqli->query("ALTER TABLE accounts ADD `webadmin` int(1) DEFAULT 0;");
                                        echo "Added webadmin<br />";
                                    }
                                    if (!isset($getcolumns['nick'])) {
                                        $mysqli->query("ALTER TABLE accounts ADD `nick` varchar(20);");
                                        echo "Added nick<br />";
                                    }
                                    if (!isset($getcolumns['mute'])) {
                                        $mysqli->query("ALTER TABLE accounts ADD `mute` int(1) DEFAULT 0;");
                                        echo "Added mute<br />";
                                    }
                                    if (!isset($getcolumns['email'])) {
                                        $mysqli->query("ALTER TABLE accounts ADD `email` VARCHAR(45) DEFAULT NULL;");
                                        echo "Added email<br />";
                                    }
                                    if (!isset($getcolumns['ip'])) {
                                        $mysqli->query("ALTER TABLE accounts ADD `ip` text;");
                                        echo "Added ip<br />";
                                    }
                                    if (!isset($getcolumns['birthday'])) {
                                        $mysqli->query("ALTER TABLE accounts ADD `birthday` DATE;");
                                        echo "Added birthday<br />";
                                    }
                                    mysqli_multi_query(
                                        $mysqli,
                                        "DROP TABLE IF EXISTS `" . $prefix . "properties`;
                        CREATE TABLE `" . $prefix . "properties` (
                        `name` text,
                        `type` TINYINT(1) NOT NULL DEFAULT '1',
                        `client` text,
                        `server` text,
                        `version` int(11) NOT NULL DEFAULT '0',
                        `forumurl` text,
                        `siteurl` text,
                        `exprate` text,
                        `mesorate` text,
                        `droprate` text,
                        `banner` text,
                        `background` text,
                        `bgcolor` varchar(6) DEFAULT NULL,
                        `bgrepeat` varchar(20) DEFAULT NULL,
                        `bgcenter` tinyint(1) DEFAULT NULL,
                        `bgfixed` tinyint(1) DEFAULT NULL,
                        `bgcover` tinyint(1) DEFAULT NULL,
                        `flood` tinyint(4) NOT NULL DEFAULT '1',
                        `floodint` int(11) DEFAULT NULL,
                        `gmlevel` int(11) NOT NULL DEFAULT '1',
                        `theme` text NOT NULL,
                        `nav` text NOT NULL,
                        `colnx` TEXT NOT NULL,
                        `colvp` TEXT NOT NULL,
                        `homecontent` text,
                        `jailmaps` text,
                        `githubapi` INT(12) DEFAULT NULL,
                        `status` tinyint(1) DEFAULT NULL,
                        `recaptcha_public` text DEFAULT NULL,
                        `recaptcha_private` text DEFAULT NULL,
                        `hash_algorithm` varchar(45),
                        PRIMARY KEY (`version`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                        INSERT INTO " . $prefix . "properties (version, theme, nav, colnx, colvp, homecontent) VALUES
                        (83, 'bootstrap', 1, 'paypalNX', 'votepoints', 'Admins: Click here to edit');

                        DROP TABLE IF EXISTS `" . $prefix . "pages`;
                        CREATE TABLE `" . $prefix . "pages` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `title` text NOT NULL,
                        `slug` text NOT NULL,
                        `author` text NOT NULL,
                        `content` text NOT NULL,
                        `visible` tinyint(1) NOT NULL DEFAULT '1',
                        PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

                        DROP TABLE IF EXISTS `" . $prefix . "mail`;
                        CREATE TABLE `" . $prefix . "mail` (
                        `mailid` int(10) unsigned NOT NULL auto_increment,
                        `to` varchar(50) NOT NULL,
                        `from` varchar(50) NOT NULL,
                        `status` tinyint(4) NOT NULL default '-1',
                        `title` varchar(50) NOT NULL,
                        `content` text NOT NULL,
                        `ipaddress` varchar(15) NOT NULL default '127.0.0.1',
                        `timestamp` varchar(40) NOT NULL default '-',
                        `dateadded` varchar(30) default 'NULL DATE',
                        PRIMARY KEY (`mailid`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

                        DROP TABLE IF EXISTS `" . $prefix . "buynx`;
                        CREATE TABLE `" . $prefix . "buynx` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `meso` int(11) NOT NULL,
                        `nx` int(11) NOT NULL,
                        PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

                        DROP TABLE IF EXISTS `" . $prefix . "vote`;
                        CREATE TABLE `" . $prefix . "vote` (
                        `id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
                        `name` VARCHAR(45) NOT NULL,
                        `link` TEXT NOT NULL ,
                        `gnx` INT(11) UNSIGNED NOT NULL DEFAULT 10,
                        `gvp` INT(11) UNSIGNED NOT NULL DEFAULT 1,
                        `waittime` INT(11) UNSIGNED NOT NULL DEFAULT 21600,
                        PRIMARY KEY ( `id` )
                        ) ENGINE = MYISAM ;

                        DROP TABLE IF EXISTS `" . $prefix . "ncomments`;
                        CREATE TABLE `" . $prefix . "ncomments` (
                        `id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
                        `nid` INT ( 10 ) NOT NULL ,
                        `author` VARCHAR( 16 ) NOT NULL ,
                        `feedback` INT( 1 ) NOT NULL ,
                        `date` VARCHAR( 32 ) NOT NULL ,
                        `comment` TEXT NOT NULL ,
                        PRIMARY KEY ( `id` )
                        ) ENGINE = MYISAM ;

                        DROP TABLE IF EXISTS `" . $prefix . "ecomments`;
                        CREATE TABLE `" . $prefix . "ecomments` (
                        `id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
                        `eid` INT ( 10 ) NOT NULL ,
                        `author` VARCHAR( 16 ) NOT NULL ,
                        `feedback` INT( 1 ) NOT NULL ,
                        `date` VARCHAR( 32 ) NOT NULL ,
                        `comment` TEXT NOT NULL ,
                        PRIMARY KEY ( `id` )
                        ) ENGINE = MYISAM ;

                        DROP TABLE IF EXISTS `" . $prefix . "bcomments`;
                        CREATE TABLE `" . $prefix . "bcomments` (
                        `id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
                        `bid` INT ( 10 ) NOT NULL ,
                        `author` VARCHAR( 16 ) NOT NULL ,
                        `feedback` INT( 1 ) NOT NULL ,
                        `date` VARCHAR( 32 ) NOT NULL ,
                        `comment` TEXT NOT NULL ,
                        PRIMARY KEY ( `id` )
                        ) ENGINE = MYISAM ;

                        DROP TABLE IF EXISTS `" . $prefix . "news`;
                        CREATE TABLE `" . $prefix . "news` (
                        `id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
                        `title` VARCHAR( 50 ) NOT NULL ,
                        `author` VARCHAR( 16 ) NOT NULL ,
                        `date` VARCHAR( 32 ) NOT NULL ,
                        `type` VARCHAR( 50 ) NOT NULL ,
                        `content` TEXT NOT NULL ,
                        `views` INT ( 10 ) NOT NULL DEFAULT '0',
                        `locked` int(10) unsigned NOT NULL DEFAULT '0',
                        PRIMARY KEY ( `id` )
                        ) ENGINE = MYISAM ;

                        DROP TABLE IF EXISTS `" . $prefix . "events`;
                        CREATE TABLE `" . $prefix . "events` (
                        `id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
                        `title` VARCHAR( 50 ) NOT NULL ,
                        `author` VARCHAR( 16 ) NOT NULL ,
                        `date` VARCHAR( 32 ) NOT NULL ,
                        `type` VARCHAR( 100 ) NOT NULL ,
                        `status` VARCHAR( 32 ) NOT NULL ,
                        `content` TEXT NOT NULL ,
                        `views` INT ( 10 ) NOT NULL DEFAULT '0',
                        `locked` int(10) unsigned NOT NULL DEFAULT '0',
                        PRIMARY KEY ( `id` )
                        ) ENGINE = MYISAM ;

                        DROP TABLE IF EXISTS `" . $prefix . "gmblog`;
                        CREATE TABLE `" . $prefix . "gmblog` (
                        `id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
                        `title` VARCHAR( 50 ) NOT NULL ,
                        `author` VARCHAR( 16 ) NOT NULL ,
                        `date` VARCHAR( 32 ) NOT NULL ,
                        `content` TEXT NOT NULL ,
                        `views` INT( 10 ) NOT NULL DEFAULT '0',
                        `locked` int(10) unsigned NOT NULL DEFAULT '0',
                        PRIMARY KEY ( `id` )
                        ) ENGINE = MYISAM ;

                        DROP TABLE IF EXISTS `" . $prefix . "profile`;
                        CREATE TABLE `" . $prefix . "profile` (
                        `id` int(10) NOT NULL AUTO_INCREMENT,
                        `accountid` int(10) DEFAULT NULL,
                        `name` varchar(255) NOT NULL DEFAULT '',
                        `mainchar` int(10) DEFAULT NULL,
                        `realname` varchar(255) DEFAULT NULL,
                        `age` int(2) DEFAULT NULL,
                        `country` varchar(255) DEFAULT NULL,
                        `motto` varchar(255) DEFAULT NULL,
                        `favjob` varchar(255) DEFAULT NULL,
                        `text` varchar(200) DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        UNIQUE KEY `accountid_UNIQUE` (`accountid`)
                        ) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

                        DROP TABLE IF EXISTS `" . $prefix . "gdcache`;
                        CREATE TABLE " . $prefix . "gdcache (
                        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                        `hash` varchar(32) NOT NULL,
                        `name` varchar(20) NOT NULL,
                        PRIMARY KEY (`id`)
                        ) ENGINE = MYISAM ;

                        DROP TABLE IF EXISTS `" . $prefix . "votingrecords`;
                        CREATE TABLE `" . $prefix . "votingrecords` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `ip` varchar(30) NOT NULL DEFAULT '0',
                        `siteid` int(11) DEFAULT NULL,
                        `account` varchar(13) NOT NULL DEFAULT '0',
                        `date` int(11) NOT NULL DEFAULT '0',
                        `times` bigint(20) unsigned NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                        ) ENGINE=MYISAM;"
                                    );
                                    echo "
                        <META http-equiv=\"refresh\" content=\"0;URL=?install=4\">";
                                    break;
                                case 4:
                                    include('../database.php');
                                    if (isset($_POST['submit'])) {
                                        $sservername = $mysqli->real_escape_string(stripslashes($_POST['servername']));
                                        $sclient = $mysqli->real_escape_string(stripslashes($_POST['client']));
                                        $sserver = $mysqli->real_escape_string(stripslashes($_POST['setup']));
                                        $sforumurl = $mysqli->real_escape_string(stripslashes($_POST['forumurl']));
                                        $sexp = $mysqli->real_escape_string(stripslashes($_POST['exprate']));
                                        $smeso = $mysqli->real_escape_string(stripslashes($_POST['mesorate']));
                                        $sdrop = $mysqli->real_escape_string(stripslashes($_POST['droprate']));
                                        $sgmlevel = $mysqli->real_escape_string(stripslashes($_POST['gmlevel']));
                                        $ssiteurl = str_replace(
                                            '/assets/config/install/install.php?install=4',
                                            '',
                                            $_SERVER["REQUEST_URI"]
                                        ) . "/";
                                        $sversion = $mysqli->real_escape_string(stripslashes($_POST['version']));
                                        $sservertype = $mysqli->real_escape_string($_POST['servertype']);
                                        $scolnx = $mysqli->real_escape_string(stripslashes($_POST['colnx']));
                                        $scolvp = $mysqli->real_escape_string(stripslashes($_POST['colvp']));
                                        $shashalgorithm = $mysqli->real_escape_string(stripslashes($_POST['hash-algorithm']));
                                        $continue = true;

                                        if (empty($sservername)) {
                                            echo '<div class="alert alert-danger">Your server doesn&apos;t have a name?</div>';
                                            $continue = false;
                                        } elseif (empty($sclient)) {
                                            echo '<div class="alert alert-danger">You need a client link.</div>';
                                            $continue = false;
                                        } elseif (empty($sserver)) {
                                            echo '<div class="alert alert-danger">You need a server link.</div>';
                                            $continue = false;
                                        } elseif (empty($sforumurl)) {
                                            echo '<div class="alert alert-danger">You need to enter a forum URL. If you don&apos; have one,
                            just put a &apos;#&apos; in the text box.</div>';
                                            $continue = false;
                                        } elseif (empty($sexp)) {
                                            echo '<div class="alert alert-danger">Enter an exp rate. Don&apos;t put an x in the text box!
                        </div>';
                                            $continue = false;
                                        } elseif (empty($smeso)) {
                                            echo '<div class="alert alert-danger">Enter a meso rate. Don&apos;t put an x in the text box!
                        </div>';
                                            $continue = false;
                                        } elseif (empty($sdrop)) {
                                            echo '<div class="alert alert-danger">Enter an drop rate. Don&apos;t put an x in the text box!
                        </div>';
                                            $continue = false;
                                        } elseif (empty($sgmlevel)) {
                                            echo '<div class="alert alert-danger">Enter the level that you must be to be GM (Usually 1)
                        </div>';
                                            $continue = false;
                                        } elseif (!is_numeric($sversion)) {
                                            echo '<div class="alert alert-danger">Enter a numeric value for the server version</div>';
                                            $continue = false;
                                        } elseif (empty($scolnx)) {
                                            echo '<div class="alert alert-danger">Please enter your NX column name.</div>';
                                            $continue = false;
                                        } elseif (empty($scolvp)) {
                                            echo '<div class="alert alert-danger">Please enter your VP column name.</div>';
                                            $continue = false;
                                        }
                                        if (!$continue) {
                                            echo "
                        <hr /><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
                                        } else {
                                            $mysqli->query("UPDATE " . $prefix . "properties SET name='" . $sservername . "', type = '" .
                                                $sservertype . "', client='" . $sclient . "', server = '" . $sserver . "', version='" .
                                                $sversion . "', forumurl='" . $sforumurl . "', siteurl='" . $ssiteurl . "', exprate='" . $sexp .
                                                "', mesorate='" . $smeso . "', droprate='" . $sdrop . "', gmlevel = '" . $sgmlevel . "',
                        flood='1', floodint='5', theme='bootstrap', nav='1', colnx = '" . $scolnx . "', colvp = '" .
                                                $scolvp . "', hash_algorithm = '" . $shashalgorithm . "'");
                                            echo "Working...";
                                            echo "
                        <meta http-equiv=\"refresh\" content=\"1; url=?install=5\" />";
                                        }
                                    } else {
                                        include('../properties.php');
                                        $url = $_SERVER["REQUEST_URI"];
                                        $url = str_replace('/assets/config/install/install.php?install=4', '', $url) . "/";
                                        echo "
                        <h5 class=\"card-title\">Site Configuration</h5>
                        <hr />
                        <form method=\"post\" action=\"\" role=\"form\">
                            <div class=\"form-group\">
                                <label for=\"serverName\">Server Name</label>
                                <input name=\"servername\" type=\"text\" maxlength=\"100\" value='MapleBit'
                                    class='form-control' id=\"serverName\" required />
                            </div>
                            <div class=\"form-group\">
                                <label for=\"serverType\">Server Type</label>
                                <select name=\"servertype\" class=\"form-control\">
                                    <option value=\"1\">Rebirth</option>
                                    <option value=\"0\">Level</option>
                                </select>
                            </div>
                            <div class=\"form-group\">
                                <label for=\"clientDL\">Client Link</label>
                                <input name=\"client\" type=\"text\" maxlength=\"100\" class='form-control'
                                    id=\"clientDL\" required />
                            </div>
                            <div class=\"form-group\">
                                <label for=\"setupLink\">Setup Link</label>
                                <input name=\"setup\" type=\"text\" maxlength=\"100\" class='form-control'
                                    id=\"setupLink\" required />
                            </div>
                            <div class=\"form-group\">
                                <label for=\"verion\">Version</label>
                                <input name=\"version\" type=\"text\" maxlength=\"6\" class='form-control' id=\"verion\"
                                    placeholder=\"83\" required />
                            </div>
                            <div class=\"form-group\">
                                <label for=\"forum\">Forum URL</label>
                                <input name=\"forumurl\" type=\"text\" maxlength=\"100\" class='form-control'
                                    id=\"forum\" placeholder=\"/forums\" required />
                            </div>
                            <div class=\"form-group\">
                                <label for=\"expRate\">Experience Rate</label>
                                <input name=\"exprate\" type=\"text\" maxlength=\"10\" class='form-control'
                                    id=\"expRate\" placeholder=\"100x\" required />
                            </div>
                            <div class=\"form-group\">
                                <label for=\"mesoRate\">Meso Rate</label>
                                <input name=\"mesorate\" type=\"text\" maxlength=\"10\" class='form-control'
                                    id=\"mesoRate\" placeholder=\"100x\" required />
                            </div>
                            <div class=\"form-group\">
                                <label for=\"dropRate\">Drop Rate</label>
                                <input name=\"droprate\" type=\"text\" maxlength=\"10\" class='form-control'
                                    id=\"dropRate\" placeholder=\"100x\" required />
                            </div>
                            <hr />
                            <div class=\"form-group\">
                                <label for=\"nxCol\">NX Column</label>
                                <input name=\"colnx\" type=\"text\" maxlength=\"30\" class='form-control' id=\"nxCol\"
                                    placeholder=\"paypalNX\" required />
                                <small id=\"nxColumnHelp\" class=\"form-text text-muted\">What column in the accounts
                                    table holds the NX value?</small>
                            </div>
                            <div class=\"form-group\">
                                <label for=\"vpCol\">Vote Point Column</label>
                                <input name=\"colvp\" type=\"text\" maxlength=\"30\" class='form-control' id=\"vpCol\"
                                    placeholder=\"votepoints\" required />
                                <small id=\"vpColumnHelp\" class=\"form-text text-muted\">What column in the accounts
                                    table holds the Vote Points value?</small>
                            </div>
                            <hr />
                            <div class=\"form-group\">
                                <label for=\"gmAccess\">GM Access</label>
                                <input name=\"gmlevel\" type=\"text\" maxlength=\"10\" class='form-control'
                                    id=\"gmAccess\" placeholder=\"3\" value=\"3\" required />
                                <small id=\"vpColumnHelp\" class=\"form-text text-muted\">What level GM should be
                                    allowed to access the GM panel?</small>
                            </div>
                            <hr />
                            <div class=\"form-group\">
                                <label for=\"hashAlgorithm\">Password Hashing Algorithm</label>
                                <select name=\"hash-algorithm\" class='form-control'>
                                    <option value=\"bcrypt\">bcrypt</option>
                                    <option value=\"sha1\" selected>SHA1</option>
                                    <option value=\"sha512\">SHA512</option>
                                </select>
                                <small id=\"hashAlgorithmHelp\" class=\"form-text text-muted\">What algorithm should be
                                    used to hash passwords? If you're not sure, choose SHA1.</small>
                            </div>
                            <hr />
                            <input name='submit' type='submit' value='Submit &raquo;' class=\"btn btn-outline-primary
                                float-right\" />
                        </form>
                        ";
                                    }
                                    break;
                                case 5:
                                    echo '<h5 class="card-title">Extra Steps</h5>
                        <hr />';
                                    if (isset($_POST['myself'])) {
                                        echo "
                        <meta http-equiv=\"refresh\" content=\"0; url=?install=6\" />";
                                    } else {
                                        echo "
                        <h6>Registration</h6>
                        <p>
                            For Registration to work, go to the <a
                                href='https://www.google.com/recaptcha/admin#list'>Google reCAPTCHA</a> page, register
                            your domain, and then submit your private and public keys in the Admin Panel -> Site
                            Settings -> Site Configuration page.
                        </p>
                        <h6>Rankings</h6>
                        <p>
                            For the rankings to work, you need the GD archive to be extracted. This can take some time.
                            Go to assets/img/GD and extract the .zip archive.
                        </p>
                        <hr />
                        <form method=\"post\">
                            <input type=\"submit\" name=\"myself\" class=\"btn btn-outline-primary float-right\"
                                value=\"OK, I&#39;ll do it! &raquo;\" />
                        </form>";
                                    }
                                    break;
                                case 6:
                                    include('../database.php');
                                    echo '<h5 class="card-title">Create Administrator Account</h5>
                        <hr />';
                                    if (!isset($_POST['submit'])) {
                                        $_SESSION['flash'] = "";
                                        echo "
                        <form method=\"post\" action=\"\" role=\"form\">
                            <div class=\"form-group\">
                                <label for=\"accName\">Your Account Name</label>
                                <input name=\"accname\" type=\"text\" class=\"form-control\" id=\"accName\"
                                    placeholder=\"Username\" required />
                                <small id=\"accnameHelp\" class=\"form-text text-muted\">If you don't yet have an
                                    account, skip this step for now. Or, create a new account directly in your database
                                    and proceed.</small>
                            </div>
                            <hr />
                            <a href=\"?install=done\" class=\"btn btn-outline-secondary float-left\">Skip &raquo;</a>
                            <input name=\"submit\" type=\"submit\" value=\"Submit &raquo;\" class=\"btn
                                btn-outline-primary float-right\" />
                        </form>
                        ";
                                    } else {
                                        $name = $mysqli->real_escape_string($_POST['accname']);
                                        $getaccount = $mysqli->query("SELECT * from accounts WHERE name = '" . $name . "'");
                                        $count = $getaccount->num_rows;
                                        if ($count == 1) {
                                            $mysqli->query("UPDATE accounts SET webadmin = 1 WHERE name = '" . $name . "'");
                                            echo "
                        <meta http-equiv=\"refresh\" content=\"0; url=?install=done\" />";
                                            $_SESSION['flash'] = "<div class=\"alert alert-success\">" . $name . " is now a web
                            administrator</div>";
                                        } else {
                                            echo "<div class=\"alert alert-danger\">Invalid account.</div>
                        <hr /><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
                                        }
                                    }
                                    break;
                                case "done":
                                    echo "<h5 class=\"card-title\">Woohoo! You're done installing MapleBit!</h5>
                        " . $_SESSION['flash'] . "
                        <hr />
                        <form action=\"../../../?base=main\" method=\"post\">
                            <input type=\"submit\" class=\"btn btn-success float-right\" value=\"Ok, let's go!
                                &raquo;\" />
                        </form>";
                                    $content = "Congratulations on completing your MapleBit Installation! Leave this file here, or
                        delete it if you would like to reconfigure your website.";
                                    $fp = fopen("installdone.txt", "wb");
                                    fwrite($fp, $content);
                                    fclose($fp);
                                    session_destroy();
                                    break;
                            }
                        }
                        echo '
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>';
