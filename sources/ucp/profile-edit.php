<?php 
if($_SESSION['id']){
	if($_SESSION['pname'] == NULL){
		echo "<div class=\"alert alert-danger\">You must assign a profile name before you can edit your public profile.</div>";
	}else{
		echo "
				<legend>
					<a href=\"?cype=main&amp;page=members&name=".$_SESSION['pname']."\">
						My Profile
					</a>
				</legend>";
		if(!isset($_POST['edit'])){
			$gp = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$_SESSION['id']."'") or die(mysql_error());
			$p = $gp->fetch_assoc();
			$gc = $mysqli->query("SELECT * FROM `characters` WHERE `accountid`='".$_SESSION['id']."'") or die(mysql_error());
			echo "
		<form method=\"POST\" role=\"form\">
		<b>Profile Name: </b>
			".$p['name']."
		<div class=\"form-group\">
			<label for=\"mainChar\">Main Character:</label>
				<select name=\"mainchar\" class=\"form-control\" id=\"mainChar\">";
			while($c = $gc->fetch_assoc()){
				echo "
					<option value=\"".$c['id']."\">".$c['name']."</option>";
			}
			echo "
				</select>
		</div>
		<div class=\"form-group\">
			<label for=\"realName\">Real Name:</label>
			<input type=\"text\" class=\"form-control\" name=\"realname\" value=\"".$p['realname']."\" required id=\"realName\"/>
		</div>
		<div class=\"form-group\">
			<label for=\"myAge\">Age:</label>
			<select name=\"age\" class=\"form-control\" id=\"myAge\">
				<option value=\"".$p['age']."\">".$p['age']."</option>";
			$i = 7;
			while($i < 50){
				echo "
					<option value=\"".$i."\">".$i."</option>";
				$i++;
			}
			echo "
			</select>
		</div>
		<div class=\"form-group\">
			<label for=\"Country\">Country:</label>
			<input type=\"text\" class=\"form-control\" name=\"country\" value=\"".$p['country']."\" id=\"Country\"/>
		</div>
		<div class=\"form-group\">
			<label for=\"Motto\">Motto:</label>
			<input type=\"text\" class=\"form-control\" name=\"motto\" value=\"".$p['motto']."\" id=\"Motto\"/>
		</div>
		<div class=\"form-group\">
			<label for=\"favJob\">Favorite Job:</label>
				<select name=\"favjob\" class=\"form-control\" id=\"favJob\">
					<option value=\"".$p['favjob']."\">".$p['favjob']."</option>
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
			<label for=\"aboutMe\">About Me:</label>
				<textarea name=\"text\" style=\"height:200px\" maxlength=\"200\" class=\"form-control\" id=\"textCount\">".stripslashes($p['text'])."</textarea>
				<p id=\"counter\"></p>
			<div class=\"alert alert-info\">Please keep in mind that all of this information will be public.</div>
			<input type=\"submit\" name=\"edit\" value=\"Update &raquo;\" class=\"btn btn-primary\"/>
			</form>
		</div>
			<script type=\"text/javascript\">
			$('#textCount').keyup(function () {
			var left = 200 - $(this).val().length;
				if (left < 0) {
					left = 0;
				}
				$('#counter').text('Characters left: ' + left);
			});
			</script>";
		}else{
			$pname = sql_sanitize(isset($_POST['pname']));
			$mainchar = sql_sanitize($_POST['mainchar']);
			$realname = sanitize_space($_POST['realname']);
			$age = sql_sanitize($_POST['age']);
			$country = sanitize_space($_POST['country']);
			$motto = sanitize_space($_POST['motto']);
			$favjob = sanitize_space($_POST['favjob']);
			$text = sanitize_space($_POST['text']);
			$u = $mysqli->query("UPDATE `".$prefix."profile` SET `mainchar`='".$mainchar."',`realname`='".$realname."',`age`='".$age."',`country`='".$country."',`motto`='".$motto."',`favjob`='".$favjob."',`text`='".$text."' WHERE `accountid`='".$_SESSION['id']."'") or die(mysql_error());
				echo "Your public profile has been updated<br />";
				echo "Click <a href=\"?cype=main&amp;page=members&name=".$_SESSION['pname']."\">here</a> to go to your profile.";
			}
		}
	}else{
		echo "
		<legend>Error</legend>
		No login session present.
		";
}
?>