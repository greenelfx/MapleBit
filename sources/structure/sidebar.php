<?php
if(basename($_SERVER["PHP_SELF"]) == "sidebar.php"){
    die("403 - Access Forbidden");
}
?>
<div class="well well2">
<?php
if(isset($_SESSION['id'])){
		echo "
	<h3 class=\"text-center\">Control Panel</h3><hr/>

	<a href=\"?base=ucp\" class=\"btn btn-default btn-block\">Control Panel</a>
	";
	if(isset($_SESSION['admin'])){
		echo "
		<a href=\"?base=admin\" class=\"btn btn-default btn-block\">Admin Panel</a>
		";
	}
	if(isset($_SESSION['gm']) || isset($_SESSION['admin'])){
		echo "
		<a href=\"?base=gmcp\" class=\"btn btn-default btn-block\">GM Panel</a>
		";
	}
	if(isset($_SESSION['pname']) && $_SESSION['pname'] == "checkpname"){
		echo "
		<a href=\"?base=ucp&amp;page=profname\" class=\"btn btn-default btn-block\">Set Profile Name</a>
		";
	}else{
		echo "
		<a href=\"?base=main&amp;page=members&amp;name=".$_SESSION['pname']."\" class=\"btn btn-default btn-block\">My Profile</a>
		";
	}
	echo "
		<a href=\"?base=main&amp;page=members\" class=\"btn btn-default btn-block\">Members List</a>
		<a href=\"?base=misc&amp;script=logout\" class=\"btn btn-primary btn-block\">Log Out</a>
		";
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
<?php
echo "
<h3 class=\"text-center\">Server Info</h3><hr/>";
	$accounts = $mysqli->query("SELECT * FROM accounts");
		$saccounts = $accounts->num_rows;
	$characters = $mysqli->query("SELECT * FROM characters");
		$scharacters = $characters->num_rows;
	$online = $mysqli->query("SELECT * FROM accounts where loggedin = 2");
		$sonline = $online->num_rows;
				echo "
	Players Online: <b>".$sonline."</b><br/>
	Accounts: <b>".$saccounts."</b><br/>
	Characters: <b>".$scharacters."</b><br/>";

echo "<hr/>
Version <a href='?base=main&amp;page=download'><b>".$version."</b></a><br/>
Experience Rate: <b>".$exprate."</b><br/>
Meso Rate: <b>".$mesorate."</b><br/>
Drop Rate: <b>".$droprate."</b><br/>
";
?>
</div>