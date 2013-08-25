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

if(isset($_SESSION['admin'])){
	if($_GET['ticket'] == ""){
	//If you are logged in, you will be sent to the main ticket page.
	echo "
	<legend>Your Tickets</legend>
		<table class=\"table table-striped table-bordered table-hover\">
		<thead>
			<tr>
				<th>Ticket Number</th>
				<th>Ticket Name</th>
				<th>Last Reply</th>
				<th>Status</th>
			</tr>
		</thead>";
			$gettickets = $mysqli->query("SELECT * FROM `cype_tickets` WHERE `status` = 'Open' ORDER BY `ticketid` ASC")or die(mysql_error());
			$getnumer = $gettickets->num_rows;
			while($tickets = $gettickets->fetch_assoc()){
				echo "
					<tr>
						<th>
							" . $tickets['ticketid'] . "
						</th>
						<td>
							<a href =\"?cype=admin&amp;page=ticket&amp;a=$tickets[ticketid]&amp;ticket=Yes\">
								" . $tickets['title'] . "
							</a>
						</td>
						<td>
							" . $tickets['date'] . "
						</td>
						<th>
							" . $tickets['status'] . "
						</th>
					</tr>
				";
			}
		echo "</table>";
	}
	elseif($_GET['ticket'] == "Yes"){
		$GrabTicket = $mysqli->query("SELECT * FROM `cype_tickets` LEFT JOIN `cype_tcomments` ON cype_tickets.ticketid = cype_tcomments.ticketid WHERE cype_tickets.ticketid = '".mysql_real_escape_string($_GET['a'])."'");
		$viewTicket = $GrabTicket->fetch_assoc();
		$getResponse = $mysqli->query("SELECT * FROM `cype_tcomments` WHERE `ticketid` = '".sql_sanitize($_GET['a'])."'");
		$countTicket = $getResponse->num_rows;
	//View the ticket
		echo "
			<fieldset>
				<legend>
					Viewing Your Ticket
				</legend>
				Created By: $viewTicket[name]<br/>
				Date: $viewTicket[date]<br/>
				<br/>
				Ticket Details:<br/> 
				$viewTicket[details] <br/><br/>
				<br/>
				Responses: <br/><br/>
				";
				while($c = $getResponse->fetch_assoc()){
					echo "<pre>";
					echo $c['user'] . " posted on " . $c['date_com'] . "<br/><br/> " . $mysqli->real_escape_string($c['content']) . "<p></p></pre><hr/>";
				}
				if($countTicket < 1){
					echo "<hr/>Please make a response to this ticket.<hr/>";
				}
				echo "
					Make a comment to this ticket:<br/>
					<form method=\"post\" action\"\">
						<textarea name=\"comment\" style=\"height:150px; width:97%;\" /></textarea>
						<center>
							<input type=\"submit\" name=\"subcomment\" value=\"Submit Response\" class=\"btn btn-primary\"/>
							<input type=\"submit\" name=\"close\" value=\"Close Ticket\" class=\"btn btn-inverse\"/>
						</center>
					</form>
				";
				if(isset($_POST['subcomment'])){
					$postComment = sanitize_space($_POST['comment']);
						
					if(strlen($postComment) < 25){
						echo "Please provide more information.";
					}
					else{
						$insertComment = $mysqli->query("INSERT INTO `cype_tcomments` (ticketid, user, content, date_com)
							VALUES "."('".$_GET['a']."', '".$_SESSION['pname']."', '".$postComment."', '".date('F d - g:i A')."')") or die(mysql_error());
						$insertComment = $mysqli->query("UPDATE `cype_tickets` SET `date` = '".date('F d - g:i A')."' WHERE `ticketid` = '".sql_sanitize($_GET['a'])."'") or die(mysql_error());
						if($insertComment){
							echo "<meta http-equiv=\"refresh\" content=\"0; url=\"/>";
						}
						else{
							echo "There was an error processing your update. Please notify the admin.";
						}
					}
				}
				if(isset($_POST['close'])){
					$closeTicket = $mysqli->query("UPDATE `cype_tickets` SET `status` = 'Closed' WHERE `ticketid` = '".sql_sanitize($_GET['a'])."'");
					if($closeTicket){
						echo "This ticket was successfully closed! You will be redirected in two seconds.
						<meta http-equiv=\"refresh\" content=\"2; url=?cype=admin&amp;page=ticket\"/>
						";
					}
				}
			echo "
			</fieldset>
		";
	}
} else {
	header('Location:?cype=admin');
}
?>