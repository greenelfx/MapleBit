<?php 
if(isset($_SESSION['id'])){
	if(isset($_SESSION['admin'])){
		if(empty($_GET['action'])){
			echo "<h2 class=\"text-left\">Manage Accounts</h2><hr/>
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
				  <tbody>";
			$per_page = 15;
			$pages_query = $mysqli->query("SELECT id FROM `accounts`")->num_rows;
			$pages = ceil($pages_query/$per_page);
			if(isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p']>0){
				$page = $mysqli->real_escape_string($_GET['p']);
			}
			else {
				redirect("?base=admin&page=manuser&p=1");
			}
			$start = ($page - 1) * $per_page;

			$query = $mysqli->query("SELECT * FROM accounts ORDER BY name ASC LIMIT $start, $per_page");
			while ($row = $query->fetch_assoc()) {
			if($row['loggedin'] > 0) {
				$status = "<span class=\"label label-success\">Online</span>";
			}
			elseif($row['loggedin'] == 0 && $row['banned'] > 0){
				$status = "<span class=\"label label-danger\">Banned</span>";
			}
			elseif($row['loggedin'] == 0){
				$status = "<span class=\"label label-default\">Ofline</span>";
			}
			else {
				$status = "<span class=\"label label-warning\">Unknown</span>";
			}
				echo "<tr>
					<td><a href=\"?base=admin&amp;page=manuser&amp;action=view&amp;user=".$row['name']."\">".$row['name']."</td>
					<td>".$row['email']."</td>
					<td>".$row['gm']."</td>
					<td>".$row[$colnx]."</td>
					<td>".$row[$colvp]."</td>
					<td>".$status."</td>
					</tr>";
			}
			$minus1 = $page - 1;
			echo "</tbody>
			</table>
			<div class=\"text-center\">
			<ul class=\"pagination\">";
			if ($pages >=1 && $page <=$pages) {
			if($page-5 <= 0){
				$x = 1;
			}
			else {
				$x = $page-5;
			}
			  for ($x; $x<=$page+5; $x++) {
				if($x == $page){
					echo "<li class=\"active\">";
				} else{
					echo "<li>";
				}
				echo "<a href=\"?base=admin&amp;page=manuser&amp;p=".$x."\">".$x."</a></li>";
			  }
			}
			echo "</ul>
			</div>";
		}
		elseif($_GET['action']=="view"){
			if(isset($_GET['user'])){
				$user = $mysqli->real_escape_string(preg_replace("/[^A-Za-z0-9 ]/", '', $_GET['user']));
				$count = $mysqli->query("SELECT * FROM accounts WHERE name = '".$user."'");
				if($count->num_rows == 1) {
					$row = $count->fetch_assoc();
					if($row['loggedin'] > 0) {
						$status = "<span class=\"label label-success\">Online</span>";
					}
					elseif($row['loggedin'] == 0 && $row['banned'] > 0){
						$status = "<span class=\"label label-danger\">Banned</span>";
					}
					elseif($row['loggedin'] == 0){
						$status = "<span class=\"label label-default\">Ofline</span>";
					}
					else {
						$status = "<span class=\"label label-warning\">Unknown</span>";
					}
					
					if($row['webadmin'] == 1) {
						$checked = "checked";
					} else {
						$checked = "";
					}
					echo "<h2 class=\"text-left\">Viewing ".$user."</h2><hr/>";
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
										<input type=\"checkbox\" name=\"webadmin\" ".$checked." value=\"1\"> Web Administrator
									</label>
								</div>
							</div>
							<button class=\"btn btn-primary\" name=\"submit\" type=\"submit\">Edit User &raquo;</button>
						</form>";
					} else {
						$email = $mysqli->real_escape_string(strip_tags($_POST["email"]));
						$password = $mysqli->real_escape_string(strip_tags($_POST["password"]));
						$nx = $mysqli->real_escape_string(strip_tags($_POST["nx"]));
						$vp = $mysqli->real_escape_string(strip_tags($_POST["vp"]));
						$gm = $mysqli->real_escape_string(strip_tags($_POST["gm"]));
						if(isset($_POST['webadmin'])){
							$webadmin = 1;
						}
						else {
							$webadmin = 0;
						}
						if($_POST['password'] == "") {
							$mysqli->query("UPDATE accounts SET email = '".$email."', ".$colnx." = '".$nx."', ".$colvp." = '".$vp."', gm = '".$gm."', webadmin = '".$webadmin."' WHERE name = '".$user."'");
							echo "<div class=\"alert alert-success\">".$user." successfully edited</div>";
							redirect_wait5("?base=admin&page=manuser&action=view&user=".$user."");
						}
						else {
							$mysqli->query("UPDATE accounts SET password = '".$password."', email = '".$email."', ".$colnx." = '".$nx."', ".$colvp." = '".$vp."', gm = '".$gm."', webadmin = '".$webadmin."' WHERE name = '".$user."'");
							echo "<div class=\"alert alert-success\">".$user." successfully edited</div>";
							redirect_wait5("?base=admin&page=manuser&action=view&user=".$user."");
						}					
					}
				}
				else {
					echo "
					<h2 class=\"text-left\">Error</h2><hr/>
					<div class=\"alert alert-danger\">This user doesn't exist!</div>";
					redirect_wait5("?base=admin&page=manuser");
				}
			}
		} else {
			redirect("?base");
		}
	}
}else{
	redirect("?base");
}
?>