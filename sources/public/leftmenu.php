<script type="text/javascript" src="assets/js/login.js"></script>
<?php
if(isset($_SESSION['id'])){
		echo "
	<h3>Control Panel</h3>
	<ul class=\"unstyled\">
	<li><a href=\"?cype=ucp\">Control Panel</a></li>
	";
	if(isset($_SESSION['admin'])){
		echo "
		<li><a href=\"?cype=admin\">Admin Panel</a></li>
		";
	}
	if(isset($_SESSION['gm'])){
		echo "
		<li><a href=\"?cype=gmcp\">GM Panel</a></li>
		";
	}
	if(@$_SESSION['pname'] == NULL){
		echo "
		<li><a href=\"?cype=ucp&amp;page=profname\">Set Profile Name</a></li>
		";
	}else{
		echo "
		<li><a href=\"?cype=main&amp;page=members&amp;name=".$_SESSION['pname']."\">Your Profile</a></li>
		";
	}
	echo "
		<li><a href=\"?cype=main&amp;page=members\">Members List</a></li>
		<li><a href=\"?cype=misc&amp;script=logout\">Log Out</a></li>
		";
		} else {
?>
	<h3>Login Panel</h3>
    <form name="loginform" id="loginform" method="post" action="?cype=misc&script=login">	
		<input type="text" name="username" maxlength="12" class="form-control" placeholder="Username" id="username" required/>
		<input type="password" name="password" maxlength="12" class="form-control" placeholder="Password" id="password" required style="margin-top:10px;"/>
		<p>
		<div class="btn-group btn-group-justified">
            <a type="button" id="login" name="login" class="btn btn-primary">Login</a>
			<a type="button" class="btn btn-info" href="?cype=main&amp;page=register">Register</a>
		</div>
    </form>
		<div id="message"></div>
<?php
	}
?>