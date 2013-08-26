<?php 
echo '
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>CypeReboot Installation</title>
<link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link rel="icon" href="../favicon.ico" type="image/x-icon" />
</head>
<body>

<div class="container">
	<div class="form-install">
    <h2 class="form-install-heading">Cype Installation</h2>
	';
if(isset($_GET['install'])){
	$install = $_GET['install'];
}else{
	$install = "";
}
if(file_exists('installdone.txt')){
	echo "<h4>An error has occurred</h4>
			<hr />
			CypeCMS has already been installed.";
}else{
	switch($install){
		case NULL:
			echo '<h4>Welcome to Cype.</h4>
					<hr />
					Welcome to Cype. Before you can use Cype, you need to give me your database information. Please make sure you have the following information handy:<br/><br/>
					<ul><li>Database name</li><li>Database username</li><li>Database password</li><li>Database host (usually localhost)</li><li>Table Prefix</li></ul>
					<br/>If the installer doesn\'t work for you, you can rename database.sample.php to database.php and fill out the information manually.
					<hr/>
					<form action="?install=1" method="post" style="float:right;">
						<input type="submit" class="btn btn-primary btn-lg" value="Begin &raquo;" />
					</form>
					<br/><br/>';
			exit;
			break;
		case 1:
			echo '<h4>Configure your database settings</h4>
					<hr />
						<form action="?install=2" method="post" class="form-horizontal" role="form">
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
								<input type="text" class="form-control" id="inputPrefix" placeholder="Database Prefix" name="DBprefix" value="cype_">
							</div>
						</div>						
						<hr/>
							<input type="submit" class="btn btn-default btn-lg" value="Continue &raquo;" style="float:right"/>
						</form>
						<br/><br/>';
			break;
		case 2:
	echo '<h4>SQL Connection</h4>
			<hr/>';
		error_reporting(0);
		$host = $_POST["host"];
		$db = $_POST["DB"];
		$dbuser = $_POST["DBuser"];
		$dbpass = $_POST["DBpass"];
		//$dbprefix = $_POST["DBprefix"];
		$dbprefix = "cype_";
		$mysqli = new mysqli("$host", "$dbuser", "$dbpass", "$db");
if ($mysqli->connect_errno) {
    printf("<div class=\"alert alert-danger\">Connect failed: %s\n", $mysqli->connect_error);
	echo "</div><hr/><a href=\"?install=1\" class=\"btn btn-danger btn-lg\" value=\"Continue &raquo;\" style=\"float:right\">&laquo; Go Back</a><br/><br/>";
    exit();
}
if($host == "" || $db == "") {
	echo"<div class=\"alert alert-danger\">Please enter the correct information</div>";
	echo "<hr/><a href=\"?install=1\" class=\"btn btn-danger btn-lg\" value=\"Continue &raquo;\" style=\"float:right\">&laquo; Go Back</a><br/><br/>";
	exit();
}
file_put_contents('../database.php', '<?php
if(basename($_SERVER["PHP_SELF"]) == "database.php"){
    die("403 - Access Forbidden");
}
//SQL Information
$host[\'hostname\'] = \''.$host.'\'; // Hostname [Usually locahost]
$host[\'user\'] = \''.$dbuser.'\'; // Database Username [Usually root]
$host[\'password\'] = \''.$dbpass.'\'; // Database Password [Leave blank if unsure]
$host[\'database\'] = \''.$db.'\'; // Database Name

//Database Prefix
$prefix = "'.$dbprefix.'";
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
				<hr/>
					<form action="?install=3" method="post" style="float:right">
						<input type="submit" class="btn btn-default btn-lg" value="Execute SQL &raquo;" />
					</form>
					<br/><br/>';

			break;
			
		case 3:
			include '../database.php';
mysqli_multi_query($mysqli, "DROP TABLE IF EXISTS `".$prefix."properties`;
CREATE TABLE `".$prefix."properties` (
  `version` int(11) NOT NULL DEFAULT '0',
  `name` text,
  `client` text,
  `forumurl` text,
  `vote` text,
  `exprate` text,
  `mesorate` text,
  `droprate` text,
  `flood` tinyint(4) NOT NULL DEFAULT '1',
  `floodint` int(11) DEFAULT NULL,
  `pcap` text,
  `maxaccounts` tinyint(4) NOT NULL DEFAULT '3',
  `gmlevel` int(11) NOT NULL DEFAULT '1',
  `theme` text NOT NULL,
  `nav` text NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `".$prefix."pages`;
CREATE TABLE `".$prefix."pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `dir` text NOT NULL,
  `header` text NOT NULL,
  `footer` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `".$prefix."mail`;
CREATE TABLE  `".$prefix."mail` (
  `mailid` int(10) unsigned NOT NULL auto_increment,
  `to` varchar(50) NOT NULL,
  `from` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL default '-1',
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `ipaddress` varchar(15) NOT NULL default '127.0.0.1',
  `timestamp` varchar(40) NOT NULL default '-',
  `dateadded` varchar(30) default 'NULL DATE',
  PRIMARY KEY  (`mailid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `".$prefix."tcomments`;
CREATE TABLE `".$prefix."tcomments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ticketid` int(10) unsigned NOT NULL,
  `user` varchar(30) NOT NULL,
  `content` longtext NOT NULL,
  `date_com` varchar(100) NOT NULL,
  PRIMARY KEY (`id`,`ticketid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `".$prefix."tickets`;
CREATE TABLE `".$prefix."tickets` (
  `ticketid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `support_type` varchar(20) NOT NULL,
  `details` longtext NOT NULL,
  `date` varchar(100) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `status` varchar(45) NOT NULL,
  PRIMARY KEY (`ticketid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `".$prefix."buynx`;
CREATE TABLE `".$prefix."buynx` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meso` int(11) NOT NULL,
  `nx` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `".$prefix."pcomments`;
 CREATE TABLE `".$prefix."pcomments` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
`accountid` INT( 10 ) NOT NULL ,
`commenter` VARCHAR( 16 ) NOT NULL ,
`feedback` INT( 1 ) NOT NULL ,
`date` VARCHAR( 32 ) NOT NULL ,
`comment` TEXT NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM ;

DROP TABLE IF EXISTS `".$prefix."ncomments`;
 CREATE TABLE `".$prefix."ncomments` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
`nid` INT ( 10 ) NOT NULL ,
`author` VARCHAR( 16 ) NOT NULL ,
`feedback` INT( 1 ) NOT NULL ,
`date` VARCHAR( 32 ) NOT NULL ,
`comment` TEXT NOT NULL ,
`dateadded` VARCHAR(30) NOT NULL DEFAULT 0 ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM ;

DROP TABLE IF EXISTS `".$prefix."ecomments`;
 CREATE TABLE `".$prefix."ecomments` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
`eid` INT ( 10 ) NOT NULL ,
`author` VARCHAR( 16 ) NOT NULL ,
`feedback` INT( 1 ) NOT NULL ,
`date` VARCHAR( 32 ) NOT NULL ,
`comment` TEXT NOT NULL ,
`dateadded` VARCHAR(30) NOT NULL DEFAULT 0 ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM ;

DROP TABLE IF EXISTS `".$prefix."bcomments`;
 CREATE TABLE `".$prefix."bcomments` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
`bid` INT ( 10 ) NOT NULL ,
`author` VARCHAR( 16 ) NOT NULL ,
`feedback` INT( 1 ) NOT NULL ,
`date` VARCHAR( 32 ) NOT NULL ,
`comment` TEXT NOT NULL ,
`dateadded` VARCHAR(30) NOT NULL DEFAULT 0 ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM ;

DROP TABLE IF EXISTS `".$prefix."news`;
 CREATE TABLE `".$prefix."news` (
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

DROP TABLE IF EXISTS `".$prefix."events`;
 CREATE TABLE `".$prefix."events` (
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

DROP TABLE IF EXISTS `".$prefix."gmblog`;
 CREATE TABLE `".$prefix."gmblog` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
`title` VARCHAR( 50 ) NOT NULL ,
`author` VARCHAR( 16 ) NOT NULL ,
`date` VARCHAR( 32 ) NOT NULL ,
`content` TEXT NOT NULL ,
`views` INT( 10 ) NOT NULL DEFAULT '0',
`locked` int(10) unsigned NOT NULL DEFAULT '0',
PRIMARY KEY ( `id` )
) ENGINE = MYISAM ;

DROP TABLE IF EXISTS `".$prefix."profile`;
CREATE TABLE `".$prefix."profile` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
`accountid` INT( 10 ) NOT NULL ,
`name` VARCHAR( 16 ) NOT NULL ,
`mainchar` INT( 10 ) NOT NULL ,
`realname` VARCHAR( 32 ) NOT NULL ,
`age` INT( 2 ) NOT NULL ,
`country` TEXT NULL DEFAULT NULL,
`motto` TEXT NULL DEFAULT NULL,
`favjob` TEXT NULL DEFAULT NULL,
`text` TEXT NULL DEFAULT NULL,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM ;


ALTER TABLE `accounts` MODIFY COLUMN `nick` TEXT NULL DEFAULT NULL;

ALTER TABLE `accounts` MODIFY COLUMN `sitelogged` TEXT NULL DEFAULT NULL;

ALTER TABLE `".$prefix."properties` ADD COLUMN `gmlevel` INTEGER NOT NULL DEFAULT 1 AFTER `maxaccounts`;
");
echo "<META http-equiv=\"refresh\" content=\"0;URL=?install=4\">";
		break;
		case 4:
		include('../database.php');
			if(isset($_POST['submit'])){
				$sservername = $mysqli->real_escape_string(stripslashes($_POST['servername']));
				$sclient = $mysqli->real_escape_string(stripslashes($_POST['client']));
				$sforumurl = $mysqli->real_escape_string(stripslashes($_POST['forumurl']));
				$svote = $mysqli->real_escape_string(stripslashes($_POST['vote']));
				$sexp = $mysqli->real_escape_string(stripslashes($_POST['exprate']));
				$smeso = $mysqli->real_escape_string(stripslashes($_POST['mesorate']));
				$sdrop = $mysqli->real_escape_string(stripslashes($_POST['droprate']));
				$sgmlevel = $mysqli->real_escape_string(stripslashes($_POST['gmlevel']));
				$sversion = $_POST['version'];
			
				$stop = "false";
				if(empty($sservername)){
					echo '<font color="red">Your server doesn&apos;t have a name?</font>';
					$stop = "true";
					echo '<meta http-equiv="refresh" content="1; url=?install=4" />';
				}
				if($stop == "false"){
					if(empty($sclient)){
						echo '<font color="red">You need a client link.</font>';
						$stop = "true";
						echo '<meta http-equiv="refresh" content="1; url=?install=4" />';
					}
				}
				if($stop == "false"){
					if(empty($sforumurl)){
						echo '<font color="red">You need to enter a forum URL. If you don&apos; have one, just put a &apos;#&apos; in the text box.</font>';
						$stop = "true";
						echo '<meta http-equiv="refresh" content="1; url=?install=4" />';
					}
				}
				if($stop == "false"){
					if(empty($svote)){
						echo '<font color="red">Enter a voting link. If you are unsure, put a &apos;#&apos; in the text box.</font>';
						$stop = "true";
						echo '<meta http-equiv="refresh" content="1; url=?install=4" />';
					}
				}
				if($stop == "false"){
					if(empty($sexp)){
						echo '<font color="red">Enter an exp rate. Don&apos;t put an x in the text box!</font>';
						$stop = "true";
						echo '<meta http-equiv="refresh" content="1; url=?install=4" />';
					}
				}
				if($stop == "false"){
					if(empty($smeso)){
						echo '<font color="red">Enter a meso rate. Don&apos;t put an x in the text box!</font>';
						$stop = "true";
						echo '<meta http-equiv="refresh" content="1; url=?install=4" />';
					}
				}
				if($stop == "false"){
					if(empty($sdrop)){
						echo '<font color="red">Enter an drop rate. Don&apos;t put an x in the text box!</font>';
						$stop = "true";
						echo '<meta http-equiv="refresh" content="1; url=?install=4" />';
					}
				}
				if($stop == "false"){
					if(empty($sgmlevel)){
						echo '<font color="red">Enter the level that you must be to be GM (Usually 1)</font>';
						$stop = "true";
						echo '<meta http-equiv="refresh" content="1; url=?install=4" />';
					}
				}			
				if($stop == "false"){
					$mysqli->query("INSERT cype_properties SET version='$sversion', name='$sservername', client='$sclient', forumurl='$sforumurl', vote='$svote', exprate='$sexp', mesorate='$smeso', droprate='$sdrop', flood='1', floodint='20', pcap='100', maxaccounts='3', gmlevel='$sgmlevel', theme='Flatly', nav='0'");
					echo "Working...";
					echo "<meta http-equiv=\"refresh\" content=\"1; url=?install=done\" />";
				}
			}else{
				include('../properties.php');
				echo "
				<h4>Site Configuration</h4>
				<form method=\"post\" action=\"\" role=\"form\">
				<div style='height:100%; width:500px;'>
				<div class=\"form-group\">
					<label for=\"serverName\">Server Name</label>
					<input name=\"servername\" type=\"text\" maxlength=\"100\" value='Cype' class='form-control' id=\"serverName\" required/>
				</div>
				<div class=\"form-group\">
					<label for=\"clientDL\">Client Link</label>
					<input name=\"client\" type=\"text\" maxlength=\"100\" class='form-control' id=\"clientDL\" required/>
				</div>
				<div class=\"form-group\">
					<label for=\"verion\">Version</label>
				<select name=\"version\" class=\"form-control\">
					<option value=\"55\">55</option>
					<option value=\"60\">62</option>
					<option value=\"75\">75</option>
					<option value=\"83\" selected>83</option>
					<option value=\"90\">90</option>
					<option value=\"111\">111</option>
					<option value=\"117\">117</option>
				</select>
				</div>
				<div class=\"form-group\">
					<label for=\"forum\">Forum URL</label>
					<input name=\"forumurl\" type=\"text\" maxlength=\"100\" class='form-control' id=\"forum\" placeholder=\"/forums\" required/>
				</div>
				<div class=\"form-group\">
					<label for=\"vote\">Vote URL</label>
					<input name=\"vote\" type=\"text\" maxlength=\"100\" class='form-control' id=\"vote\" placeholder=\"http://www.gtop100.com/maplestory\" required/>
				</div>
				<div class=\"form-group\">
					<label for=\"expRate\">Experience Rate</label>
					<input name=\"exprate\" type=\"text\" maxlength=\"10\" class='form-control' id=\"expRate\" placeholder=\"100x\" required/>
				</div>
				<div class=\"form-group\">
					<label for=\"mesoRate\">Meso Rate</label>
					<input name=\"mesorate\" type=\"text\" maxlength=\"10\" class='form-control' id=\"mesoRate\" placeholder=\"100x\" required/>
				</div>
				<div class=\"form-group\">
					<label for=\"dropRate\">Drop Rate</label>
					<input name=\"droprate\" type=\"text\" maxlength=\"10\" class='form-control' id=\"dropRate\" placeholder=\"100x\" required/>
				</div>
				<div class=\"form-group\">
					<label for=\"gmAccess\">GM Access</label>
					<input name=\"gmlevel\" type=\"text\" maxlength=\"10\" class='form-control' id=\"gmAccess\" placeholder=\"3\" value=\"3\" required/>
					<span class=\"help-block\">What level GM should be allowed to access the GM panel?</span>
				</div>
				</div>
				<hr/>
					<input name='submit' type='submit' value='Submit &raquo;' class=\"btn btn-primary btn-lg\" style=\"float:right\"/>
					<br/><br/>
				</form>
				";
			}
			break;
		case "done":
			echo "<h4>Woohoo! You're done installing Cype!</h4>
			<hr/>
				<form action=\"../../../?cype=main\" method=\"post\">
					<input type=\"submit\" class=\"btn btn-success btn-lg\" value=\"Ok, let's go! &raquo;\" style=\"float:right;\"/><br/><br/>
				</form>";
			$content = "Congratulations on completing your Cype Installation! Leave this file here, or delete it if you would like to reconfigure your website.";
			$fp = fopen("installdone.txt","wb");
			fwrite($fp,$content);
			fclose($fp);
			break;
	}
}
echo '</div></div>
</body>';
?>