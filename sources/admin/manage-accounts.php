<?php
if(basename($_SERVER["PHP_SELF"]) == "manage-accounts.php") {
	die("403 - Access Forbidden");
}

if(isset($_GET['action']) && $_GET['action'] === "view") {
	if(isset($_GET['user'])) {
		$user = $mysqli->real_escape_string($_GET['user']);
		$count = $mysqli->query("SELECT * FROM accounts WHERE name = '".$user."'");
		if($count->num_rows == 1) {
			$row = $count->fetch_assoc();
			if($row['loggedin'] > 0) {
				$status = "<span class=\"label label-success\">Online</span>";
			}
			elseif($row['loggedin'] == 0 && $row['banned'] > 0) {
				$status = "<span class=\"label label-danger\">Banned</span>";
			}
			elseif($row['loggedin'] == 0) {
				$status = "<span class=\"label label-default\">Ofline</span>";
			}
			else {
				$status = "<span class=\"label label-warning\">Unknown</span>";
			}

			if($row['webadmin'] == 1) {
				$webchecked = "checked";
			} else {
				$webchecked = "";
			}
			if($row['mute'] > 0) {
				$mutechecked = "checked";
			} else {
				$mutechecked = "";
			}
			echo "<h2 class=\"text-left\">Viewing ".$user."</h2><hr/>";
			$continue = true;
			$reason = "";
			if(!array_key_exists($colvp, $row)) { $continue = false; $reason = "VP column";}
			if(!array_key_exists($colnx, $row)) { $continue = false; $reason = "NX column";}
			if(!array_key_exists('gm', $row)) { $continue = false; $reason = "GM column";}
			if($continue) {
				if(!isset($_POST['submit'])) {
					echo "<form role=\"form\" method=\"POST\">
						<div class=\"form-group\">
							<label for=\"username\">Username:</label>
							".$row['name']."
						</div>
						<div class=\"form-group\">
							<label for=\"inputEmail\">Email:</label>
							 <input type=\"email\" name=\"email\" class=\"form-control\" id=\"inputEmail\" value=\"".$row['email']."\" placeholder=\"Email\"\">
						</div>
						<div class=\"form-group\">
							<label for=\"password\">New Password:</label><small>&nbsp;Leave empty to keep the old password</small>
							 <input type=\"password\" name=\"password\" class=\"form-control\" id=\"password\" placeholder=\"Password\">
						</div>
						<div class=\"form-group\">
							<label for=\"inputNX\">NX Amount:</label>
							 <input type=\"text\" name=\"nx\" class=\"form-control\" id=\"inputNX\" placeholder=\"NX Amount\" value=\"".$row[$colnx]."\">
						</div>
						<div class=\"form-group\">
							<label for=\"inputVP\">VP Amount:</label>
							 <input type=\"text\" name=\"vp\" class=\"form-control\" id=\"inputVP\" placeholder=\"VP Amount\" value=\"".$row[$colvp]."\">
						</div>
						<div class=\"form-group\">
							<label for=\"gmLevel\">GM Level:</label>
							 <input type=\"text\" name=\"gm\" class=\"form-control\" id=\"gmLevel\" placeholder=\"GM Level\" value=\"".$row['gm']."\">
						</div>
						<div class=\"form-group\">
							<div class=\"checkbox\">
								<label>
									<input type=\"checkbox\" name=\"webadmin\" ".$webchecked."> Web Administrator
								</label>
							</div>
						</div>
						<div class=\"form-group\">
							<div class=\"checkbox\">
								<label>
									<input type=\"checkbox\" name=\"mute\" ".$mutechecked."> Muted
								</label>
							</div>
						</div>
						<button class=\"btn btn-primary\" name=\"submit\" type=\"submit\">Edit User &raquo;</button>
					</form>";
				}
				else {
					$email = $mysqli->real_escape_string($_POST["email"]);
					$nx = $mysqli->real_escape_string($_POST["nx"]);
					$vp = $mysqli->real_escape_string($_POST["vp"]);
					$gm = $mysqli->real_escape_string($_POST["gm"]);
					$password = $mysqli->real_escape_string(sha1($_POST["password"]));
					$webadmin = 0;
					$muted = 0;
					$err = 0;

					if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
						echo "<div class=\"alert alert-danger\">You must enter a valid email!</div>";
						$err = 1;
					}
					if(!isset($nx) || !is_numeric($nx)) {
						echo "<div class=\"alert alert-danger\">You must enter an integer for NX!</div>";
						$err = 1;
					}
					if(!isset($vp) || !is_numeric($vp)) {
						echo "<div class=\"alert alert-danger\">You must enter an integer for Vote Points!</div>";
						$err = 1;
					}
					if(!isset($gm) || !is_numeric($gm) || $gm < 0) {
						echo "<div class=\"alert alert-danger\">You must enter a positive integer for the GM Level</div>";
						$err = 1;
					}
					if(isset($_POST['webadmin'])) {
						$webadmin = 1;
					}
					if(isset($_POST['mute'])) {
						$muted = 1;
					}
					if($err) {
						echo "<hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
					}
					if(empty($_POST['password']) && !$err) {
						$mysqli->query("UPDATE accounts SET email = '".$email."', ".$colnx." = '".$nx."', ".$colvp." = '".$vp."', gm = '".$gm."', webadmin = '".$webadmin."', mute = '".$muted."' WHERE name = '".$user."'");
						echo "<div class=\"alert alert-success\">".$user." successfully edited</div>";
						redirect_wait5("?base=admin&page=manageaccounts&action=view&user=".$user."");
					}
					elseif(!$err) {
						$mysqli->query("UPDATE accounts SET password = '".$password."', email = '".$email."', ".$colnx." = '".$nx."', ".$colvp." = '".$vp."', gm = '".$gm."', webadmin = '".$webadmin."', mute = '".$muted."' WHERE name = '".$user."'");
						echo "<div class=\"alert alert-success\">".$user." successfully edited</div>";
						redirect_wait5("?base=admin&page=manageaccounts&action=view&user=".$user."");
					}
				}
			}
			else {
				echo "<div class=\"alert alert-danger\">This feature is unavailable. The ".$reason." is misconfigured.</div>";
			}
		}
		else {
			echo "
			<h2 class=\"text-left\">Error</h2><hr/>
			<div class=\"alert alert-danger\">This user doesn't exist!</div>";
			redirect_wait5("?base=admin&page=manageaccounts");
		}
	}
}
else {
	if(isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 0) {
		$page = $mysqli->real_escape_string($_GET['p']);
	}
	else {
		redirect("?base=admin&page=manageaccounts&p=1");
	}

	require 'assets/libs/Zebra_Pagination.php';
	$pagination = new Zebra_Pagination();

	$records_per_page = 15;
	$pagination->variable_name('p');
	$query = $mysqli->query("SELECT * FROM accounts LIMIT ". (($pagination->get_page() - 1) * $records_per_page) . ", " . $records_per_page . "");
	$count = $mysqli->query("SELECT count(*) FROM accounts");
	$pagination->records($count->fetch_assoc()["count(*)"]);
	$pagination->records_per_page($records_per_page);

	if(!$query->num_rows) {
		redirect("?base=admin&page=manageaccounts&p=1"); // If page is out of range
	}
	echo "
		<h2 class=\"text-left\">Manage Accounts</h2><hr/>
		<table class=\"table\">
			<thead>
				<tr>
					<th>Username</th>
					<th>Email</th>
					<th>GM Level</th>
					<th>NX</th>
					<th>Vote Points</th>
					<th>Status</th>
				</tr>
		  	</thead>
			<tbody>
	";	
	while ($row = $query->fetch_assoc()) {
		if(isset($row['banned']) && $row['banned'] > 0) {
			$status = "<span class=\"label label-danger\">Banned</span>";
		}
		elseif(isset($row['loggedin']) && $row['loggedin'] > 0) {
			$status = "<span class=\"label label-success\">Online</span>";
		}
		elseif(isset($row['loggedin']) && $row['loggedin'] == 0) {
			$status = "<span class=\"label label-default\">Ofline</span>";
		}
		else {
			$status = "<span class=\"label label-warning\">Unknown</span>";
		}
		echo "
			<tr>
			<td><a href=\"?base=admin&amp;page=manageaccounts&amp;action=view&amp;user=".$row['name']."\">".$row['name']." &raquo;</td>
		";
		if(array_key_exists('email', $row)) { echo "<td>".$row['email']."</td>"; } else { echo "<td>Unknown</td>";}
		if(array_key_exists('gm', $row)) { echo "<td>".$row['gm']."</td>"; } else { echo "<td>Unknown</td>";}
		if(array_key_exists($colnx, $row)) { echo "<td>".$row[$colnx]."</td>"; } else { echo "<td>Unknown</td>";}
		if(array_key_exists($colvp, $row)) { echo "<td>".$row[$colvp]."</td>"; } else { echo "<td>Unknown</td>";}
		echo "
			<td>".$status."</td>
			</tr>
		";
	}
	echo "
			</tbody>
		</table>
		<div class=\"text-center\">
	";
	$pagination->render();
	echo "</div>";
}