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
?>
<legend>Vote for <?php echo $servername; ?></legend>
<?php
    $settings['require-account'] = false; /* Change to true, if you want to require accounts */ 
    $settings['action'] = 'vote.php'; /* The page your script is located */ 
	#######################################################################
    $earnedpoints = false;
	$insertnew = false; 
    $time = $mysqli->real_escape_string(time());  
    $getaccount = $mysqli->real_escape_string($_POST['name']);  
	$account = preg_replace("/[^A-Za-z0-9 ]/", '', $getaccount);
    if ($account == '' && isset($_POST['submit']) && $settings['require-account'] == true) {$funct_error = 'You need to put in a username!';} 
	if ($account == '' && isset($_POST['submit'])) {$funct_error = 'You need to put in a username!';} 
    elseif(isset($_POST['submit'])) { 
        $result = $mysqli->query("SELECT *, SUM(times) as amount FROM votingrecords WHERE NOT account='' AND NOT account='0' AND account='".$account."' OR ip='".$ip."'");
		$result1 = $mysqli->query("SELECT id FROM accounts WHERE name = '$account' LIMIT 1");
        $row = $result->fetch_assoc();
        $timecalc = $time - $row['date']; 
        $accid = $result1->fetch_assoc();
        $accid1 = $accid['id'];
		$times = $row['times'] + 1;
		$output .= "$accid1";
        if ($row['amount'] == '' || $timecalc > 21600) { 
            if($row['amount'] == '') { 
                $result = $mysqli->query("INSERT INTO votingrecords (ip, account, date, times) VALUES ('".$ip."', '".$account."', '".$time."', '1')"); 
            } 
            else { 
                $result = $mysqli->query("UPDATE votingrecords SET ip='".$ip."', account='".$account."', date='".$time."', times='".$times."' WHERE ip='".$ip."' OR account='".$account."'"); 
            } 
            $earnedpoints = true;  
            if ($earnedpoints == true) { 
                if ($account != '' && $output != '') {
                    $result = $mysqli->query("INSERT INTO vote_transfer (id, targetid) VALUES (DEFAULT, ".$output.")");
                } 
                $funct_msg = '<meta http-equiv="refresh" content="0; url='.vote.'">'; 
            } 
        } 
        elseif($timecalc < 21600 && $row['amount'] != '') { 
            $funct_msg = 'You\'ve already voted within the last 6 hours!'; 
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
You can vote 1 time every 6 hours for 1 vote point and 10k NX. Make sure to be logged off while voting!<br/>
            <form action="<?php $settings['action']; ?>" method="post">  
                <?php  
                    if($funct_msg != '') {echo '<div class="alert alert-danger">'.$funct_msg.'</div>';}  
                    if($funct_error != '') {echo '<div class="alert alert-danger">'.$funct_error.'</div>';}  
                ?><br/>
                    <input type="text" name="name" maxlength="15" class="vinput" required autocomplete="off" placeholder="Account Name/Login ID"/><br/>
					<input type="submit" name="submit" value="Submit" class="btn btn-primary"/> 
            </form> 
<?php } ?>
