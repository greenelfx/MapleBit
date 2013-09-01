<?php 
if($_SESSION['id']){
	echo "
		<legend>
			Account Settings
		</legend>";
	if(!isset($_POST['modify'])){
		$query = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$_SESSION['id']."'") or die(mysql_error());
		$row = $query->fetch_assoc();
		echo "
		<div class=\"alert alert-warning\">If you want to keep your current password, leave the password fields blank! <a class=\"close\" data-dismiss=\"alert\" href=\"#\" aria-hidden=\"true\">&times;</a></div>
		<form method=\"post\" action='' role=\"form\">
			<b><abbr title=\"You can't change this!\">Username</abbr></b>
				".$row['name']."
		<div class=\"form-group\">
			<label for=\"cPassword\">Current Password</label>
			<input type=\"password\" class=\"form-control\" id=\"cPassword\" placeholder=\"Current Password\" maxlength=\"12\" name=\"current\" />
		</div>
		<div class=\"form-group\">
			<label for=\"nPassword\">New Password</label>
			<input type=\"password\" class=\"form-control\" id=\"nPassword\" placeholder=\"New Password\" maxlength=\"12\" name=\"password\" />
		</div>
		<div class=\"form-group\">
			<label for=\"coPassword\">Confirm Password</label>
			<input type=\"password\" class=\"form-control\" id=\"coPassword\" placeholder=\"Confirm Password\" maxlength=\"12\" name=\"copassword\" />
		</div>
		<div class=\"form-group\">
			<label for=\"Email\">Email</label>
			<input type=\"email\" class=\"form-control\" id=\"Email\" placeholder=\"email@dot.com\" maxlength=\"12\" name=\"email\" value=\"".$row['email']."\" />
		</div>
		<div class=\"form-group\">
			<label for=\"Birthday\">Birthday</label>
			<input type=\"text\" class=\"form-control\" id=\"Birthday\" placeholder=\"1990-01-01\" name=\"birth\" value=\"".$row['birthday']."\" />
		</div>
			<input type=\"submit\" name=\"modify\" class=\"btn btn-primary\" value=\"Modify &raquo;\" />
		</form><br/>";

	}else{
		$u = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$_SESSION['id']."'") or die(mysql_error());
		$userz = $u->fetch_assoc();
		$current = sql_sanitize($_POST['current']);
		$pass = sql_sanitize($_POST['password']);
		$cpass = sql_sanitize($_POST['copassword']);
		$email = sql_sanitize($_POST['email']);
		$birth = sql_sanitize($_POST['birth']);
		
		if($current){
			if($userz['password'] == hash('sha512',$current.$userz['salt']) || sha1($current) == $userz['password']){
				if($pass != $cpass){
					echo "Passwords do not match.";
				}else{
					if(strlen($pass) < 6){
						echo "Your password must be between 6 and 12 characters.";
					}elseif(strlen($pass) > 12){
						echo "Your password must be between 6 and 12 characters.";
					}else{
						$u = $mysqli->query("UPDATE `accounts` SET `password`='".sha1($pass)."',`salt`=NULL WHERE `name`='".$userz['name']."'") or die(mysql_error());
						echo "Your changes have successfully been saved.";
					}
				}
			}else{
				echo "The password you have entered is incorrect.";
			}
		}elseif($email == ""){
			echo "Please supply an email address.";
		}else{
			$u = $mysqli->query("UPDATE `accounts` SET `email`='".$email."',`birthday`='".$birth."' WHERE `name`='".$userz['name']."'") or die(mysql_error());
			echo "Your changes have successfully been saved.";
		}
	};
}else{
	include('sources/public/login.php');
}
?>
