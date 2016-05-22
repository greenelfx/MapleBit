<?php
if(basename($_SERVER["PHP_SELF"]) == "sidebar.php") {
    die("403 - Access Forbidden");
}
$online = mysqli_fetch_assoc($mysqli->query("SELECT COUNT(*) AS o FROM accounts where loggedin = 2"));
$accounts = mysqli_fetch_assoc($mysqli->query("SELECT COUNT(*) AS a FROM accounts"));
$characters = mysqli_fetch_assoc($mysqli->query("SELECT COUNT(*) AS c FROM characters"));
$links = "";
?>
<div class="well well2">
<?php
if(isset($_SESSION['id'])) {
	if(isset($_SESSION['admin'])) {
		$links .= "<a href=\"?base=admin\" class=\"btn btn-default btn-block\">Admin Panel</a>";
	}
	if(isset($_SESSION['gm']) || isset($_SESSION['admin'])) {
		$links .= "<a href=\"?base=gmcp\" class=\"btn btn-default btn-block\">GM Panel</a>";
	}
	if(isset($_SESSION['pname']) && $_SESSION['pname'] == "checkpname") {
		$links .= "<a href=\"?base=ucp&amp;page=profname\" class=\"btn btn-default btn-block\">Set Profile Name</a>";
	} else {
		$links .= "<a href=\"?base=main&amp;page=members&amp;name=".$_SESSION['pname']."\" class=\"btn btn-default btn-block\">My Profile</a>";
	}
?>
	<h3 class="text-center">Control Panel</h3>
	<hr/>
	<a href="?base=ucp" class="btn btn-default btn-block">Control Panel</a>
	<?php echo $links;?>
	<a href="?base=main&amp;page=members" class="btn btn-default btn-block">Members List</a>
	<a href="?base=misc&amp;script=logout" class="btn btn-primary btn-block">Log Out</a>
<?php
} else {
?>
	<form name="loginform" id="loginform" autocomplete="off">
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" name="username" maxlength="12" class="form-control" placeholder="Username" id="username" required/>
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" name="password" maxlength="12" class="form-control" placeholder="Password" id="password" required/>
		</div>
		<input id="login" type="submit" class="btn btn-primary btn-block" value="Login"/>
		<a href="?base=main&amp;page=register" class="btn btn-info btn-block">Register</a>
	</form>
	<div id="message"></div>
<?php
}
?>
</div>
<div class="well well2">
	<h3 class="text-center">Server Info</h3>
	<hr/>
	Players Online: <b><?php echo $online['o'];?></b><br/>
	Accounts: <b><?php echo $accounts['a'];?></b><br/>
	Characters: <b><?php echo $characters['c'];?></b><br/>
	<hr/>
	Version <a href="?base=main&amp;page=download"><b><?php echo $version;?></b></a><br/>
	Experience Rate: <b><?php echo $exprate;?></b><br/>
	Meso Rate: <b><?php echo $mesorate;?></b><br/>
	Drop Rate: <b><?php echo $droprate;?></b><br/>
</div>