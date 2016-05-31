<?php
if(basename($_SERVER["PHP_SELF"]) == "characters.php") {
	die("403 - Access Forbidden");
}

if(isset($_SESSION['id'])) {
	$checkchar = $mysqli->query("SELECT * from characters WHERE accountid = '".$_SESSION['id']."'");
	$countchar = $checkchar->num_rows;
	if($countchar > 0) {
		echo "<h2 class=\"text-left\">My Characters</h2><hr/>";
		$i = 0;
		while($c = $checkchar->fetch_assoc()) {
			if($i % 3 == 0) {
				echo "<div class=\"row\">";
			}
			echo "
				<div class=\"col-md-4\">
					<div class=\"well\">
						<h3 class=\"text-center\"> " . $c['name'] . "</h3>
						<hr/>
						<img src=\"assets/img/GD/create.php?name=".$c['name']."\" alt='".$c['name']."' class=\"avatar img-responsive\" style=\"margin: 0 auto;\">
						<hr/>
						<b>Job:</b> " . $c['job'] . "<br/>
			";
			if($servertype == 1) {
				echo "<b>Rebirths:</b> " . $c['reborns'] . "<br/>";
			}
			echo "
						<b>Level:</b> " . $c['level'] . "<br/>
						<b>EXP:</b> " . $c['exp'] . "<br/>
					</div>
				</div>
			";
			$i++;
			if($i % 3 == 0) {
				echo "</div>";
			}
		}
		echo "</div>";
	}
	else {
		echo "<div class=\"alert alert-danger\">Oops! You don't have any characters.</div>";
	}
}
else {
	redirect("?base=main");
}