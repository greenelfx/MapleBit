<?php 
/*
    Copyright (C) 2009  Murad <Murawd>
						Josh L. <Josho192837>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if($_SESSION['id']){
	if($_SESSION['pname'] == NULL){
		echo "You must assign a profile name before you can edit your public profile.";
	}else{
		echo "
				<legend>
					<a href=\"?cype=main&amp;page=members&name=".$_SESSION['pname']."\">
						My Profile
					</a>
				</legend>";
		if(!isset($_POST['edit'])){
			$gp = $mysqli->query("SELECT * FROM `cype_profile` WHERE `accountid`='".$_SESSION['id']."'") or die(mysql_error());
			$p = $gp->fetch_assoc();
			$gc = $mysqli->query("SELECT * FROM `characters` WHERE `accountid`='".$_SESSION['id']."'") or die(mysql_error());
			echo "
		<form method=\"POST\">
			<b>Profile name:</b><br/>
			".$p['name']."<br/><br/>
			<b>Main character:</b><br/>
				<select name=\"mainchar\">";
			while($c = $gc->fetch_assoc()){
				echo "
					<option value=\"".$c['id']."\">".$c['name']."</option>";
			}
			echo "
				</select><br/>
			<b>Real name:</b><br/>
			<input type=\"text\" class=\"input-large\" name=\"realname\" value=\"".$p['realname']."\" required/><br/>
			<b>Age:</b><br/>
			<select name=\"age\">
				<option value=\"".$p['age']."\">".$p['age']."</option>
				<option value=\"---\">---</option>";
			$i = 7;
			while($i < 50){
				echo "
					<option value=\"".$i."\">".$i."</option>";
				$i++;
			}
			echo "
			</select><br/>
			<b>Country:</b><br/>
			<input type=\"text\" class=\"input-large\" name=\"country\" value=\"".$p['country']."\" /><br/>
			<b>Motto:</b><br/>
			<input type=\"text\" class=\"input-large\" name=\"motto\" value=\"".$p['motto']."\" /><br/>
			<b>Favorite job:</b><br/>
				<select name=\"favjob\">
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
						</select><br/>
			<b>About Me:</b><br/>
				<textarea name=\"text\" style=\"height:150px; width:97%;\"  maxlength=\"200\" id=\"textCount\">".stripslashes($p['text'])."</textarea>
				<p id=\"counter\"></p>
			<div class=\"alert alert-info\">Please keep in mind that all of this information will be public.</div>
			<input type=\"submit\" name=\"edit\" value=\"Update &raquo;\" class=\"btn btn-primary\"/>
			</form>
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
			$u = $mysqli->query("UPDATE `cype_profile` SET `mainchar`='".$mainchar."',`realname`='".$realname."',`age`='".$age."',`country`='".$country."',`motto`='".$motto."',`favjob`='".$favjob."',`text`='".$text."' WHERE `accountid`='".$_SESSION['id']."'") or die(mysql_error());
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