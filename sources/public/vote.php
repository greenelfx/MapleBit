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
    $earnedpoints = false;
	$insertnew = false; 
    $time = $mysqli->real_escape_string(time());  
    $getaccount = $mysqli->real_escape_string($_POST['name']);  
	$account = preg_replace("/[^A-Za-z0-9 ]/", '', $getaccount);
	if ($account == '' && isset($_POST['submit'])) {$funct_error = 'You need to put in a username!';} 
	
    elseif(isset($_POST['submit'])) { 
        $result = $mysqli->query("SELECT *, SUM(times) as amount FROM votingrecords WHERE NOT account='' AND NOT account='0' AND account='".$account."' OR ip='".$ipaddress."'") or die('Error - Could not look up vote record!');  
        $row = $result->fetch_assoc();
        $timecalc = $time - $row['date']; 

        if ($row['amount'] == '' || $timecalc > $vtime) { 
            if($row['amount'] == '') { 
                $result = $mysqli->query("INSERT INTO votingrecords (ip, account, date, times) VALUES ('".$ipaddress."', '".$account."', '".$time."', '1')") or die ('Error - Could not update vote records!'); 
            } 
            else { 
                $result = $mysqli->query("UPDATE votingrecords SET ip='".$ipaddress."', account='".$account."', date='".$time."', times='1' WHERE ip='".$ipaddress."' OR account='".$account."'") or die ('Error - Could not update vote records!'); 
            } 
            $earnedpoints = true;  
            if ($earnedpoints == true) { 
                if ($account != '') {$result = $mysqli->query("UPDATE accounts SET $colvp = $colvp + $gvp, $colnx = $colnx + $gnx WHERE name='".$account."'") or die ('Error - Could not update vote points!');} 
                $funct_msg = '<meta http-equiv="refresh" content="0; url='.$vlink.'">'; 
                $redirect = true; 
            } 
        } 
        elseif($timecalc < $vtime && $row['amount'] != '') { 
            $funct_msg = 'You\'ve already voted within the last 12 hours!'; 
            $funct_msg .= '<br />Vote time: '. date('M d\, h:i A', $row['date']); 
        } 
        else { 
            $funct_error = 'Unknown Error'; 
        } 
    } 
     
    mysql_close($conn); 

    if($redirect == true) { 
        echo $funct_msg; 
    } 
     
    else { ?> 
<div class="alert alert-info">You can vote 1 time every <?php echo round($vtime/3600) . " hours for " . $gvp . " votepoints and " . round($gnx/1000) . "k NX. Make sure to be logged off while voting!</div>"?>
<form action="<?php $settings['action']; ?>" method="post">  
	<?php  
		if($funct_msg != '') {echo '<div class="alert alert-danger">'.$funct_msg.'</div>';}  
		if($funct_error != '') {echo '<div class="alert alert-danger">'.$funct_error.'</div>';}  
	?>
	<input type="text" name="name" maxlength="15" class="form-control" placeholder="Username" required autocomplete="off" style="width:50%;"/><br/>
	<input type="submit" name="submit" value="Submit &raquo;" class="btn btn-primary"/>
</form> 
<br/>
<?php } ?>
