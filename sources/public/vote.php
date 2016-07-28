<?php
if(basename($_SERVER["PHP_SELF"]) == "vote.php") {
	die("403 - Access Forbidden");
}
echo "<h2 class=\"text-left\">Vote</h2><hr/>";
$earnedpoints = false;
$insertnew = false;
$time = time();
$redirect = "";
$account = $mysqli->real_escape_string(preg_replace("/[^A-Za-z0-9 ]/", '', @$_POST['name']));
$siteid = $mysqli->real_escape_string(@$_POST['votingsite']);
$checkacc = $mysqli->query("SELECT * FROM accounts WHERE name = '$account'");
$countcheckacc = $checkacc->num_rows;
$row = $checkacc->fetch_assoc();
if($countcheckacc == 0 && isset($_POST['submit'])) { $funct_error =  "This account doesn't exist!";}
if($row['loggedin'] > 0 && isset($_POST['submit'])) { $funct_error =  "This account is logged in!";}
elseif ($account == '' && isset($_POST['submit'])) {$funct_error = 'You need to put in a username!';}
elseif(empty($_POST['votingsite']) && isset($_POST['submit'])){
	$funct_error = "Please select a voting site";
}
elseif(isset($_POST['submit'])) {
   	$checksite = $mysqli->query("SELECT * FROM ".$prefix."vote WHERE id = ".$siteid."");
	$countchecksite = $checksite->num_rows;
	if($countchecksite == 0 && isset($_POST['submit'])) {
		$funct_error = "Invalid voting site.";
	}
	else {
        $result = $mysqli->query("SELECT *, SUM(times) as amount FROM ".$prefix."votingrecords WHERE NOT account='' AND NOT account='0' AND account='".$account."' AND siteid = '".$siteid."'") or die('Error - Could not look up vote record!');
        $row = $result->fetch_assoc();
		$sitequery = $mysqli->query("SELECT * FROM ".$prefix."vote WHERE id = '".$siteid."'");
		$vsite = $sitequery->fetch_assoc();
		$gvp = $vsite['gvp'];
		$gnx = $vsite['gnx'];
        $timecalc = $time - $row['date'];
        if ($row['amount'] == '' || $timecalc > $vsite['waittime']) {
            if($row['amount'] == '') {
                $result = $mysqli->query("INSERT INTO ".$prefix."votingrecords (siteid, ip, account, date, times) VALUES ('".$siteid."', '".$ipaddress."', '".$account."', '".$time."', '1')") or die ('Error - Could not insert vote records!');
            }
            else {
                $result = $mysqli->query("UPDATE ".$prefix."votingrecords SET siteid = '".$siteid."', ip='".$ipaddress."', account='".$account."', date='".$time."', times='1' WHERE account='".$account."' AND siteid = '".$siteid."'") or die ('Error - Could not update vote records!');
            }
            $earnedpoints = true;
            if ($earnedpoints == true) {
                if ($account != '') {$result = $mysqli->query("UPDATE accounts SET $colvp = $colvp + $gvp, $colnx = $colnx + $gnx WHERE name='".$account."'") or die ('Error - Could not give rewards. Your site administrator needs to configure the NX and VP settings.');}
				$funct_msg = '<meta http-equiv="refresh" content="0; url='.$vsite['link'].'">';
                $redirect = true;
            }
        }
        elseif($timecalc < $vsite['waittime'] && $row['amount'] != '') {
            $funct_msg = 'You\'ve already voted for '.$vsite['name'].' within the last '.round($vsite['waittime']/3600).' hours!';
            $funct_msg .= '<br />Vote time: '. date('M d\, h:i A', $row['date']);
        }
        else {
            $funct_error = 'Unknown Error';
        }
   	}
}

if($redirect == true) {
	echo $funct_msg;
}
else {
	if(isset($funct_msg)) {echo '<div class="alert alert-danger">'.$funct_msg.'</div>';}
	if(isset($funct_error)) {echo '<div class="alert alert-danger">'.$funct_error.'</div>';}
	$query = $mysqli->query("SELECT * from ".$prefix."vote");
	if($query->num_rows == 0){
		echo "<div class=\"alert alert-danger\">Your administrator has not added any voting sites yet!</div>";
	}
	else {
		echo "
		<form method=\"post\">
		<div class=\"form-group\">
		<label for=\"voteSite\">Select Site:</label>
		<select name=\"votingsite\" class=\"form-control\" id=\"voteSite\" required>
		<option value=\"\" disabled selected>Select Site...</option>";
		while($row = $query->fetch_assoc()){
			echo "<option value=\"".$row['id']."\">".$row['name']."</option>";
		}
		echo "</select>
		</div>";
		if(!isset($_SESSION['id'])) {
			echo "<input type=\"text\" name=\"name\" maxlength=\"15\" class=\"form-control\" placeholder=\"Username\" required autocomplete=\"off\"/><br/>";
		} else {
			echo "<input type=\"text\" name=\"name\" maxlength=\"15\" class=\"form-control\" placeholder=\"".$_SESSION['name']."\" value=\"".$_SESSION['name']."\" required autocomplete=\"off\"/><br/>";
		}
		echo "
			<input type=\"submit\" name=\"submit\" value=\"Submit &raquo;\" class=\"btn btn-primary\"/>
			</form>
		";
	}
}
