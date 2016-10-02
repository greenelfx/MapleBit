<?php
if(basename($_SERVER["PHP_SELF"]) == "buynx.php") {
    die("403 - Access Forbidden");
}

if(!isset($_POST['buyNX'])) {
	echo "<form name=\"buynx\" method=\"post\">
	<h2 class=\"text-left\">Buy NX</h2><hr/>
	<h4>Select a Character <small>(Must have enough mesos on this character)</small></h4>";
	$fetchChar = $mysqli->query("SELECT * FROM `characters` WHERE `accountid` = '".$_SESSION['id']."'") or die();
	$countChar = $fetchChar->num_rows;
	if($countChar == 0) {
		echo "<div class=\"alert alert-danger\">Oops! You don't have any characters!</div></form>";
	}
	else {
		while($getChar = $fetchChar->fetch_assoc())	{
			echo '
				<div class="radio">
					<label class="radio">
						<input type="radio" name="selChar" value="'.$getChar['id'].'">'.$getChar['name'].'
					</label>
				</div>
			';
		}
		echo "<hr/><h4>Select a Package</h4>";
		$fetchPack = $mysqli->query("SELECT * FROM `".$prefix."buynx`");
		if($fetchPack->num_rows == 0) {
			echo "<div class=\"alert alert-danger\">Oops! Looks like there's no NX packages available right now!</div></form>";
		}
		else {
			while($getPack = $fetchPack->fetch_assoc()) {
				echo '
					<div class="radio">
						<label class="radio">
							<input type="radio" name="selPack" value="'.$getPack['meso'].'">'.number_format($getPack['nx']).' NX for '.number_format($getPack['meso']).' Mesos
						</label>
					</div>
				';
			}
			echo "
				<br/><input type=\"submit\" name=\"buyNX\" value=\"Buy NX &raquo\" class=\"btn btn-primary\"/>
				</form><br/>
			";
		}
	}
}
else {
	$selChar = isset($_POST['selChar']) ? $mysqli->real_escape_string( $_POST['selChar'] ) : '';
	$selPack = isset($_POST['selPack']) ? $mysqli->real_escape_string( $_POST['selPack'] ) : '';
	$hasMeso = $mysqli->query("SELECT * FROM `characters` WHERE `id` = '".$selChar."'") or die();
	$getMeso = $hasMeso->fetch_assoc();
	$fetchNX = $mysqli->query("SELECT * FROM `".$prefix."buynx` WHERE `meso` = '".$selPack."'") or die();
	$selNX = $fetchNX->fetch_assoc();
	if(empty($selChar)) {
		echo "<div class=\"alert alert-danger\">You need to select a character to pay for the NX.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
	}
	elseif(empty($selPack)) {
		echo "<div class=\"alert alert-danger\">You need to select a package that you want to buy.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
	}
	elseif($getMeso['meso'] < $selPack) {
		echo "<div class=\"alert alert-danger\">The character you chose does not have enough mesos to buy this package.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
	}
	else {
		$fetchCharId = $mysqli->query("SELECT * FROM `characters` WHERE `id` = '".$selChar."'") or die();
		$getCharId = $fetchCharId->fetch_assoc();
		$mysqli->query("UPDATE `characters` SET `meso` = meso - ".$selPack." WHERE `id` = ".$selChar."") or die();
		$mysqli->query("UPDATE `accounts` SET $colnx = $colnx + ".$selNX['nx']." WHERE `id` = ".$getCharId['accountid']."") or die();
		echo "<div class=\"alert alert-success\">You have purchased <b>".number_format($selNX['nx'])." NX</b> for <b>".number_format($selPack)." Mesos</b>. The mesos have been taken from <b>".$getCharId['name']."</b>.<hr/>Thank you for your purchase!</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
	}
}