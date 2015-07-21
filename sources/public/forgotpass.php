<?php
if(basename($_SERVER["PHP_SELF"]) == "forgotpass.php") {
    die("403 - Access Forbidden");
}
	echo "<h2 class=\"text-left\">Reset Password</h2>
	<hr/>";
	if(!isset($_POST['submit'])) {
?>
		<form role="form" method="post">
			<div class="form-group">
				<label for="inputUser">Username:</label>
				<input type="text" class="form-control" id="inputUser" placeholder="Enter Username" required>
			</div>
			<button class="btn btn-primary" name="submit">Submit &raquo;</button>
		</form>
<?php 
	}
	else {
		echo "<div class=\"alert alert-success\">An email has been sent to the email account associated with that username</div>";
	}
?>