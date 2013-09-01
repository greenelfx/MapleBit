<script type="text/javascript" src="assets/js/login.js"></script>
<?php
if(isset($_SESSION['id'])){
		echo "
	<h3 class=\"text-center\">Control Panel</h3>
	
	<a href=\"?cype=ucp\">Control Panel</a><br/>
	";
	if(isset($_SESSION['admin'])){
		echo "
		<a href=\"?cype=admin\">Admin Panel</a><br/>
		";
	}
	if(isset($_SESSION['gm'])){
		echo "
		<a href=\"?cype=gmcp\">GM Panel</a><br/>
		";
	}
	if(@$_SESSION['pname'] == NULL){
		echo "
		<a href=\"?cype=ucp&amp;page=profname\">Set Profile Name</a><br/>
		";
	}else{
		echo "
		<a href=\"?cype=main&amp;page=members&amp;name=".$_SESSION['pname']."\">My Profile</a><br/>
		";
	}
	echo "
		<a href=\"?cype=main&amp;page=members\">Members List</a><br/>
		<a href=\"?cype=misc&amp;script=logout\">Log Out</a><br/>
		";
		} else {
?>
	<h3 class="text-center">Login Panel</h3>
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