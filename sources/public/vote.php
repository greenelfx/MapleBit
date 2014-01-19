<h2 class="text-left">Vote</h2><hr/>
<?php
    $earnedpoints = false;
	$insertnew = false; 
    $time = $mysqli->real_escape_string(time());  
	$redirect = "";
    $getaccount = $mysqli->real_escape_string(@$_POST['name']);  
	$account = preg_replace("/[^A-Za-z0-9 ]/", '', $getaccount);
	$checkacc = $mysqli->query("SELECT * FROM accounts WHERE name = '$account'");
	$countcheckacc = $checkacc->num_rows;
	if($countcheckacc == 0 && isset($_POST['submit'])) { $funct_error =  "This account doesn't exist!"; }
	elseif ($account == '' && isset($_POST['submit'])) {$funct_error = 'You need to put in a username!';} 
    elseif(isset($_POST['submit'])) { 
        $result = $mysqli->query("SELECT *, SUM(times) as amount FROM ".$prefix."votingrecords WHERE NOT account='' AND NOT account='0' AND account='".$account."' OR ip='".$ipaddress."'") or die('Error - Could not look up vote record!');  
        $row = $result->fetch_assoc();
        $timecalc = $time - $row['date']; 

        if ($row['amount'] == '' || $timecalc > $vtime) { 
            if($row['amount'] == '') { 
                $result = $mysqli->query("INSERT INTO ".$prefix."votingrecords (ip, account, date, times) VALUES ('".$ipaddress."', '".$account."', '".$time."', '1')") or die ('Error - Could not update vote records!'); 
            } 
            else { 
                $result = $mysqli->query("UPDATE ".$prefix."votingrecords SET ip='".$ipaddress."', account='".$account."', date='".$time."', times='1' WHERE ip='".$ipaddress."' OR account='".$account."'") or die ('Error - Could not update vote records!'); 
            } 
            $earnedpoints = true;  
            if ($earnedpoints == true) { 
                if ($account != '') {$result = $mysqli->query("UPDATE accounts SET $colvp = $colvp + $gvp, $colnx = $colnx + $gnx WHERE name='".$account."'") or die ('Error - Could not update vote points!');} 
				$funct_msg = '<meta http-equiv="refresh" content="0; url='.$vlink.'">'; 
                $redirect = true; 
            } 
        } 
        elseif($timecalc < $vtime && $row['amount'] != '') { 
            $funct_msg = 'You\'ve already voted within the last '.round($vtime/3600).' hours!'; 
            $funct_msg .= '<br />Vote time: '. date('M d\, h:i A', $row['date']); 
        } 
        else { 
            $funct_error = 'Unknown Error'; 
        } 
    } 
    if($redirect == true) { 
        echo $funct_msg; 
    } 
     
    else { ?> 
<div class="alert alert-info">You can vote 1 time every <?php echo round($vtime/3600) . " hours for " . $gvp . " votepoints and " . round($gnx/1000) . "k NX. Make sure to be logged off while voting!</div>"; ?>
<form method="post">  
	<?php  
		if(isset($funct_msg)) {echo '<div class="alert alert-danger">'.$funct_msg.'</div>';}  
		if(isset($funct_error)) {echo '<div class="alert alert-danger">'.$funct_error.'</div>';}
		if(!isset($_SESSION['id'])) {
			echo "<input type=\"text\" name=\"name\" maxlength=\"15\" class=\"form-control\" placeholder=\"Username\" required autocomplete=\"off\"/><br/>";
		} else {
			echo "<input type=\"text\" name=\"name\" maxlength=\"15\" class=\"form-control\" placeholder=\"".$_SESSION['name']."\" value=\"".$_SESSION['name']."\"required autocomplete=\"off\" readonly=\"readonly\"/><br/>";
		}
	?>
	
	<input type="submit" name="submit" value="Submit &raquo;" class="btn btn-primary"/>
</form> 
<br/>
<?php } ?>
