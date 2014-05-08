<?php
if(basename($_SERVER["PHP_SELF"]) == "debug.php"){
    die("403 - Access Forbidden");
}
if($_SESSION['admin']){
		$query = $mysqli->query("SELECT * from ".$prefix."vote");
		$count = $query->num_rows;
		echo "<h2 class=\"text-left\">Debug Mode</h2><hr/>";
		if(!isset($_POST['submit'])){
			echo "
			MapleBit is an advanced CMS, built on PHP. From time to time, you may notice some PHP errors which are fairly useless. If you enable debugging mode, you will be able to edit the files that create these errors. <b>This will put your website in maintenance mode!</b><hr/>
			<form method=\"post\">
				<div class=\"form-group\">
					<label for=\"pass\">Account Password</label><small> Please verify your account password</small>
					<input name=\"password\" type=\"password\" class='form-control' id=\"pass\" required/>
				</div>
				<div class=\"checkbox\">
					<label>
						<input type=\"checkbox\" name=\"toggle\"> Debug Mode
					</label>
				</div>
				<input type=\"submit\" name=\"submit\" value=\"Submit &raquo;\" class=\"btn btn-primary\">
			</form>
			";
		}
		else {
			$error = false;
			if(empty($_POST['password'])){
				echo "<div class=\"alert alert-danger\">Please enter your account password.</div> <button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
				$error = true;
			}
			else {
				$password = sha1($mysqli->real_escape_string(strip_tags($_POST['password'])));
				$verify = $mysqli->query("SELECT id FROM accounts WHERE password = '$password'")->num_rows;
			}
			
			if(isset($_POST['toggle']) && $error == false && $verify == 1){
				$mysqli->query("UPDATE ".$prefix."properties SET debug = 1");
				echo "<div class=\"alert alert-success\">Successfully enabled debug mode.</div>";
			}
			elseif(!isset($_POST['toggle']) && $error == false && $verify == 1){
				$mysqli->query("UPDATE ".$prefix."properties SET debug = 0");
				echo "<div class=\"alert alert-success\">Successfully disabled debug mode.</div>";
			}
			else {
				echo "<hr/><div class=\"alert alert-danger\">Error</div>";
			}
		}
} else {
	redirect ("?base=admin&page=voteconfig");
}
?>