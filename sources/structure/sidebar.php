<div class="well well2">
<?php
if(isset($_SESSION['id'])){
		echo "
	<h3 class=\"text-center\">Control Panel</h3><hr/>
	
	<a href=\"?cype=ucp\" class=\"btn btn-default btn-block\">Control Panel</a>
	";
	if(isset($_SESSION['admin'])){
		echo "
		<a href=\"?cype=admin\" class=\"btn btn-default btn-block\">Admin Panel</a>
		";
	}
	if(isset($_SESSION['gm'])){
		echo "
		<a href=\"?cype=gmcp\" class=\"btn btn-default btn-block\">GM Panel</a>
		";
	}
	if(@$_SESSION['pname'] == "checkpname"){
		echo "
		<a href=\"?cype=ucp&amp;page=profname\" class=\"btn btn-default btn-block\">Set Profile Name</a>
		";
	}else{
		echo "
		<a href=\"?cype=main&amp;page=members&amp;name=".$_SESSION['pname']."\" class=\"btn btn-default btn-block\">My Profile</a>
		";
	}
	echo "
		<a href=\"?cype=main&amp;page=members\" class=\"btn btn-default btn-block\">Members List</a>
		<a href=\"?cype=misc&amp;script=logout\" class=\"btn btn-primary btn-block\">Log Out</a>
		";
		} else {
?>
    <form name="loginform" id="loginform" method="post" action="?cype=misc&script=login" autocomplete="off">	
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" name="username" maxlength="12" class="form-control" placeholder="Username" id="username" required/>
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" name="password" maxlength="12" class="form-control" placeholder="Password" id="password" required/>
		</div>
		<a id="login" class="btn btn-primary btn-block">Login</a>
		<a href="/register" class="btn btn-info btn-block">Register</a>
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
Version <a href='?cype=main&amp;page=download'><b>".$version."</b></a><br/>
Experience Rate: <b>".$exprate."</b><br/>
Meso Rate: <b>".$mesorate."</b><br/>
Drop Rate: <b>".$droprate."</b><br/>
";
?>
</div>