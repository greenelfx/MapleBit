<?php
if(basename($_SERVER["PHP_SELF"]) == "profile-edit.php") {
	die("403 - Access Forbidden");
}
?>
<script src="assets/libs/cksimple/ckeditor.js"></script>
<?php
if($_SESSION['pname'] === "checkpname") {
	echo "<div class=\"alert alert-danger\">You must assign a profile name before you can edit your public profile.</div>";
}
else {
	echo "<h2 class=\"text-left\">My Profile</h2><hr/>";
	if(!isset($_POST['edit'])) {
		$gp = $mysqli->query("SELECT * FROM ".$prefix."profile WHERE accountid='".$_SESSION['id']."'") or die();
		$p = $gp->fetch_assoc();
		$gc = $mysqli->query("SELECT * FROM characters WHERE accountid='".$_SESSION['id']."'") or die();
		$cgc = $gc->num_rows;
		echo "
			<form method=\"post\" role=\"form\">
			<b>Profile Name: </b>".$p['name']."
			<div class=\"form-group\">
		";
		if($cgc > 0) {
			echo "
				<label for=\"mainChar\">Main Character:</label>
				<select name=\"mainchar\" class=\"form-control\" id=\"mainChar\">
			";
			while($c = $gc->fetch_assoc()) {
				echo "<option value=\"".$c['id']."\">".$c['name']."</option>";
			}
			echo "</select>";
		}
		else {
			echo "<hr/><div class=\"alert alert-danger\">You don't have any characters!</div><hr/>";
		}
		echo "
			</div>
			<div class=\"form-group\">
				<label for=\"realName\">Real Name:</label>
				<input type=\"text\" class=\"form-control\" name=\"realname\" value=\"".htmlspecialchars($p['realname'], ENT_QUOTES, 'UTF-8')."\" id=\"realName\"/>
			</div>
			<div class=\"form-group\">
				<label for=\"myAge\">Age: </label>
				<select name=\"age\" class=\"form-control\" id=\"myAge\">
					<option value=\"".$p['age']."\">".$p['age']."</option>
		";
		$i = 7;
		while($i < 50) {
			echo "<option value=\"".$i."\">".$i."</option>";
			$i++;
		}
		echo "
				</select>
			</div>
			<div class=\"form-group\">
			<label for=\"Country\">Country:</label>
				<select id=\"Country\" name=\"country\" class=\"form-control\">
		";
		$countries = getCountries();
		foreach($countries as $country) {
			echo "<option value=\"".$country."\">".$country."</option>";
		}
		echo "
				</select>
			</div>
			<div class=\"form-group\">
				<label for=\"Motto\">Motto:</label>
				<input type=\"text\" class=\"form-control\" name=\"motto\" value=\"".htmlspecialchars($p['motto'], ENT_QUOTES, 'UTF-8')."\" id=\"Motto\"/>
			</div>
			<div class=\"form-group\">
				<label for=\"favJob\">Favorite Job:</label>
					<select name=\"favjob\" class=\"form-control\" id=\"favJob\">
		";
		if(isset($p['favjob'])) {
			echo "<option value=\"".$p['favjob']."\">".$p['favjob']."</option>";
		}
		echo "
			<optgroup label=\"Beginner\">
				<option value=\"Beginner\">Beginner</option>
				<option value=\"PermaNoob\">PermaNoob</option>
			</optgroup>
			<optgroup label=\"Warrior\">
				<option value=\"Swordman\">Swordman</option>
				<option value=\"Fighter\">Fighter</option>
				<option value=\"Spearman\">Spearman</option>
				<option value=\"Page\">Page</option>
				<option value=\"Crusader\">Crusader</option>
				<option value=\"Dragon Knight\">Dragon Knight</option>
				<option value=\"White Knight\">White Knight</option>
				<option value=\"Hero\">Hero</option>
				<option value=\"Dark Knight\">Dark Knight</option>
				<option value=\"Paladin\">Paladin</option>
			</optgroup>
			<optgroup label=\"Bowman\">
				<option value=\"Archer\">Archer</option>
				<option value=\"Hunter\">Hunter</option>
				<option value=\"Crossbowman\">Crossbowman</option>
				<option value=\"Ranger\">Ranger</option>
				<option value=\"Sniper\">Sniper</option>
				<option value=\"Bowmaster\">Bowmaster</option>
				<option value=\"Marksman\">Marksman</option>
			</optgroup>
			<optgroup label=\"Magician\">
				<option value=\"Magician\">Magician</option>
				<option value=\"I/L Wizard\">I/L Wizard</option>
				<option value=\"F/P Wizard\">F/P Wizard</option>
				<option value=\"Cleric\">Cleric</option>
				<option value=\"I/L Mage\">I/L Mage</option>
				<option value=\"F/P Mage\">F/P Mage</option>
				<option value=\"Priest\">Priest</option>
				<option value=\"I/L Arch Mage\">I/L Arch Mage</option>
				<option value=\"F/P Arch Mage\">F/P Arch Mage</option>
				<option value=\"Bishop\">Bishop</option>
				</optgroup>
			<optgroup label=\"Theif\">
				<option value=\"Rogue\">Rogue</option>
				<option value=\"Assassin\">Assassin</option>
				<option value=\"Bandit\">Bandit</option>
				<option value=\"Hermit\">Hermit</option>
				<option value=\"Chief Bandit\">Chief Bandit</option>
				<option value=\"Night Lord\">Night Lord</option>
				<option value=\"Shadower\">Shadower</option>
			</optgroup>
			<optgroup label=\"Pirate\">
				<option value=\"Pirate\">Pirate</option>
				<option value=\"Infighter\">Infighter</option>
				<option value=\"Gunslinger\">Gunslinger</option>
				<option value=\"Valkyrie\">Valkyrie</option>
				<option value=\"Buccaneer\">Buccaneer</option>
				<option value=\"Viper\">Viper</option>
				<option value=\"Captain\">Captain</option>
			</optgroup>
			</select>
			</div>
			<div class=\"form-group\">
				<label>About Me:</label>
				<textarea name=\"text\" style=\"height:200px\" maxlength=\"200\" class=\"form-control\" id=\"textCount\">".stripslashes($p['text'])."</textarea>
			</div>
			<p id=\"counter\">Characters left: 200</p>
			<div class=\"alert alert-info\">Please keep in mind that all of this information will be public.</div>
			<input type=\"submit\" name=\"edit\" value=\"Update &raquo;\" class=\"btn btn-primary\"/>
			</form>
		";
	}
	else {
		$pname = $mysqli->real_escape_string(isset($_POST['pname']));
		if(isset($_POST['mainchar'])) {
			$mainchar = $mysqli->real_escape_string($_POST['mainchar']);
		} else {
			$mainchar = "";
		}
		$realname = $mysqli->real_escape_string($_POST['realname']);
		$age = $mysqli->real_escape_string($_POST['age']);
		$country = $mysqli->real_escape_string($_POST['country']);
		$motto = $mysqli->real_escape_string($_POST['motto']);
		$favjob = $_POST['favjob'];
		$text = $mysqli->real_escape_string($_POST['text']);
		$u = $mysqli->query("UPDATE `".$prefix."profile` SET `mainchar`='".$mainchar."',`realname`='".$realname."',`age`='".$age."',`country`='".$country."',`motto`='".$motto."',`favjob`='".$favjob."',`text`='".$text."' WHERE `accountid`='".$_SESSION['id']."'") or die(mysql_error());
		echo "<div class=\"alert alert-success\">Your public profile has been updated<br />";
		echo "Click <a href=\"?base=main&amp;page=members&name=".$_SESSION['pname']."\" class=\"alert-link\">here</a> to go to your profile.</div>";
	}
}

?>
<script>
	CKEDITOR.replace('textCount');
	CKEDITOR.instances.textCount.on("key", function (event) {
		var s = CKEDITOR.instances.textCount.getData().length;
		var left = 200 - s;
		$('#counter').html('Characters left: ' + left);
	});
</script>