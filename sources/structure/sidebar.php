<?php
if (basename($_SERVER['PHP_SELF']) == 'sidebar.php') {
    exit('403 - Access Forbidden');
}
$online = mysqli_fetch_assoc($mysqli->query('SELECT COUNT(*) AS o FROM accounts where loggedin = 2'));
$accounts = mysqli_fetch_assoc($mysqli->query('SELECT COUNT(*) AS a FROM accounts'));
$characters = mysqli_fetch_assoc($mysqli->query('SELECT COUNT(*) AS c FROM characters'));
$links = '';
?>
<div class="card">
	<div class="card-header">Account</div>
	<?php
    if (isset($_SESSION['id'])) {
        if (isset($_SESSION['admin'])) {
            $links .= '<li class="list-group-item"><a href="?base=admin">Admin Panel</a></li>';
        }
        if (isset($_SESSION['gm']) || isset($_SESSION['admin'])) {
            $links .= '<li class="list-group-item"><a href="?base=gmcp">GM Panel</a></li>';
        }
        if (isset($_SESSION['pname']) && $_SESSION['pname'] == 'checkpname') {
            $links .= '<li class="list-group-item"><a href="?base=ucp&amp;page=profname">Set Profile Name</a></li>';
        } else {
            $links .= '<li class="list-group-item"><a href="?base=main&amp;page=members&amp;name='.$_SESSION['pname'].'">My Profile</a></li>';
        } ?>
		<ul class="list-group list-group-flush">
			<li class="list-group-item"><a href="?base=ucp">Control Panel</a></li>
			<?php echo $links; ?>
			<li class="list-group-item"><a href="?base=main&amp;page=members">Members List</a></li>
			<li class="list-group-item"><a href="?base=misc&amp;script=logout">Log Out</a></li>
		</ul>
	<?php
    } else {
        ?>
		<div class="card-body">
			<form name="loginform" id="loginform" autocomplete="off">
				<div class="form-group">
					<input type="text" name="username" maxlength="12" class="form-control" placeholder="Username" id="username" required />
				</div>
				<div class="form-group">
					<input type="password" name="password" maxlength="12" class="form-control" placeholder="Password" id="password" required />
				</div>
				<input id="login" type="submit" class="btn btn-primary btn-sm btn-block" value="Login" />
			</form>
			<div id="message"></div>
		</div>
	<?php
    }
    ?>
</div>
<div class="card mt-4 mb-4">
	<div class="card-header">Server Info</div>
	<div class="card-body">
		Players Online: <b><?php echo $online['o']; ?></b><br />
		Accounts: <b><?php echo $accounts['a']; ?></b><br />
		Characters: <b><?php echo $characters['c']; ?></b><br />
		<hr />
		Version <a href="?base=main&amp;page=download"><b><?php echo $version; ?></b></a><br />
		Experience Rate: <b><?php echo $exprate; ?></b><br />
		Meso Rate: <b><?php echo $mesorate; ?></b><br />
		Drop Rate: <b><?php echo $droprate; ?></b><br />
	</div>
</div>