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

if(isset($_SESSION['pname'])){
	if(!$_GET['ticket']){
	//If you are logged in, you will be sent to the main ticket page.
	echo "
		<legend>Your Tickets</legend>
		<table class=\"table table-striped table-hover table-bordered\">
		<thead>
			<tr>
				<th>Ticket Number</th>
				<th>Ticket Name</th>
				<th>Last Reply</th>
				<th>Status</th>
			</tr>
		</thead>";
			$gettickets = $mysqli->query("SELECT * FROM `cype_tickets` WHERE `name` = '{$_SESSION['pname']}' AND `status` = 'Open' ORDER BY `ticketid` DESC")or die(mysql_error());
			$getnumer = $gettickets->num_rows;
			$NumberTicket = 0;
			while($tickets = $gettickets->fetch_assoc()){
				echo "
					<tr>
						<th>
							" . ++$NumberTicket . "
						</th>
						<td>
							<a href =\"?cype=ucp&amp;page=ticket&amp;a=$tickets[ticketid]&amp;ticket=Yes\">
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
		echo "
		</table>";
		//Check to see if the user has more than five tickets open.
		@mysql_data_seek($gettickets, 0);
		$opentick = $gettickets->fetch_assoc();
		if($getnumber >= 5 && $opentick['status'] == "Open"){
			echo "<br /><a href=\"?cype=ucp&amp;page=ticket&amp;ticket=closed\" class=\"btn btn-primary\">View Closed Tickets</a>";
		} else{
		echo "
			<center>
				<br /><br /><a href=\"?cype=ucp&amp;page=ticket&amp;ticket=closed\" class=\"btn btn-primary\">View Closed Tickets</a>  <a href=\"?cype=ucp&amp;page=ticket&amp;ticket=create\" class=\"btn btn-info\">Create Ticket &raquo;</a>
			</center>
			";
		}
	}
	if($_GET['ticket'] == "create"){
	//Create a new ticket. Only limits 5 tickets per user.
		echo " 
			<legend>Ticket Creation</legend>
				<form method=\"post\" action=\"\">
				<b>Type of Ticket</b><br/>
					<select name=\"type\">
						<option value=\"Game\">Game Help</option>
						<option value=\"Website\">WebSite Help</option>
						<option value=\"Abuse\">Account Help</option>
						<option value=\"Account\">Banning Appeal</option>
					</select><br/>
				Select Ticket Type :<br/>";
							//You can add more here if you like. However, make sure everything has a value.
							//More options will come along as we progress.
							echo "
									<select name=\"support\">
										<option selected=\"selected\">&middot; Ticket Subgroup &middot;</option>
									<optgroup label=\"Game\">Game Help</optgroup
										<option value=\"Bug\" >Bug Report</option>
										<option value=\"NPC Bug\" >NPC Bug</option>
										<option value=\"Connection\">Connection</option>
									<optgroup label=\"Website\">WebSite Help
										<option value=\"Missing / Broken Link\">Missing / Broken Link</option>
										<option value=\"Error on Page\">Error on a Page</option>
										<option value=\"Page is not functioning\">Page not functioning correctly</option>
									<optgroup label=\"Account\">Account Help
										<option value=\"Account\" >Account issue</option>
										<option value=\"Abuse\" >User Abuse</option>
									<optgroup label=\"Banning\">Banning Help
										<option value=\"Appeal\" >Ban Appeal</option>
								</select><br/>
						
				<b>Title :</b><br/>
				<input type=\"text\" name=\"title\" maxlength=\"20\" required><br/>
				<b>Details / Information :</b><br/>
				<textarea name=\"details\" rows=\"7\" style=\"height:200px;width:100%;\"></textarea><br/>
				<input type=\"submit\" name=\"ticket\" value=\"Send Ticket &raquo;\" class=\"btn btn-primary\"/>
				</form>";
				if(isset($_POST['ticket'])){
					$type = $_POST['type'];
					$support = $_POST['support'];
					$title = sql_sanitize($_POST['title']);
					$details = $mysqli->real_escape_string($_POST['details']);
					$nowtickets = $mysqli->query("SELECT * FROM `cype_tickets` WHERE `name` = '{$_SESSION['pname']}' AND `status` = 'Open'");
					$checktickets = $nowtickets->num_rows;
					
					if($type == ""){
						echo "<div class=\"alert alert-error\">Please select the type of ticket you inquiry about.</div>";
					}
					elseif($support == ""){
						echo "<div class=\"alert alert-error\">Please select the type of statement for this ticket.</div>";
					}
					elseif($title == "" || strlen($title) < "5"){
						echo "<div class=\"alert alert-error\">You did not enter a title name or the title name is too short.</div>";
					}
					elseif(strlen($details) < 25 || $details == ""){
						echo "<div class=\"alert\">Please supply more information about the problem you are having. Make sure to include details.</div>";
					}
					elseif($checktickets > 5){
						echo "<div class=\"alert\">We're very sorry, however, you are only allowed 5 tickets on your account.</div>";
					}
					else{
						$newticket = $mysqli->query("INSERT INTO `cype_tickets` (title, type, support_type, details, date, ip, name, status) 
							VALUES "."('".$title."', '".$type."', '".$support."', '".$details."', '".date('F d - g:i A')."', '".$_SERVER['REMOTE_ADDR']."', '".$_SESSION['pname']."', 'Open')");
							
						if($newticket){
							echo "<meta http-equiv=\"refresh\" content=\"0; url=?cype=ucp&amp;page=ticket\"/>";
						}
						else{
							echo "<div class=\"alert alert-error\">The ticket you have created was not able to be completed due to an error. Please contact the admin.</div>";
						}
					}
				}
	}
	elseif($_GET['ticket'] == "Yes"){
		$GrabTicket = $mysqli->query("SELECT * FROM `cype_tickets` LEFT JOIN `cype_tcomments` ON cype_tickets.ticketid = cype_tcomments.ticketid WHERE cype_tickets.ticketid = '".mysql_real_escape_string($_GET['a'])."'");
		$viewTicket = $GrabTicket->fetch_assoc();
		$getResponce = $mysqli->query("SELECT * FROM `cype_tcomments` WHERE `ticketid` = '".sql_sanitize($_GET['a'])."'");
		$countTicket = $getResponce->num_rows;
	//View the ticket
		if($_SESSION['pname'] != $viewTicket['name']){
			echo "
				<div class=\"alert alert-error\">You are not allowed to view this ticket. Your actions have been logged.</div>
				<meta http-equiv=\"refresh\" content=\"1; url=?cype=main\"/>
			";
			exit();
		}
		echo "
			<legend>Viewing Ticket</legend>
				<b>Created By:</b> $viewTicket[name]<br/>
				<b>Date:</b> $viewTicket[date]<br/>
				<hr/>
				<b>Ticket Details:</b><br/> 
				$viewTicket[details]<br/><br/>
				<b>Responses:</b><br/>
				";
				while($c = $getResponce->fetch_assoc()){
					echo "<pre>";
					echo $c['user'] . " posted on " . $c['date_com'] . "<br/><br/> " . stripslashes($c['content']) . "<p></p></pre><hr/>";
				}
				/*if($countTicket < 1){
					echo "There is currently no responces to this ticket yet. If you need to add more details, go ahead and add one more!";
				}*/
				if($viewTicket['status'] == "Closed"){
					echo "<div class=\"alert alert-info\">This ticket is closed. If your solution is not here, please open another ticket.</DIV>";
				}
				else {
				echo "
					
					Make a comment to this ticket:<br/>
					<form method=\"post\" action\"\">
						<textarea name=\"comment\" style=\"height:150px; width:97%;\"/></textarea>
						<center>
							<input type=\"submit\" name=\"subcomment\" value=\"Submit Response\" class=\"btn btn-primary\"/>
						</form>
					</center>
				";
				}
				if(isset($_POST['subcomment'])){
					$postComment = sanitize_space($_POST['comment']);
						
					if(strlen($postComment) < 25){
						echo "Please provide more information.";
					}
					else{
						$insertComment = $mysqli->query("INSERT INTO `cype_tcomments` (ticketid, user, content, date_com)
							VALUES "."('".sql_sanitize($_GET['a'])."', '".$_SESSION['pname']."', '".$postComment."', '".date('F d - g:i A')."')") or die(mysql_error());
						
						$insertComment = $mysqli->query("UPDATE `cype_tickets` SET `date` = '".date('F d - g:i A')."' WHERE `ticketid` = '".sql_sanitize($_GET['a'])."'");
							
						if($insertComment){
							echo "<meta http-equiv=\"refresh\" content=\"0; url=\"/>";
						}
						else{
							echo "There was an error processing your update. Please notify the admin.";
						}
					}
				}
			echo "
			</fieldset>
		";
	}
	elseif($_GET['ticket'] == "closed"){
	echo "
		<legend>My Closed Tickets</legend>
			";
			$getclosedTickets = $mysqli->query("SELECT * FROM `cype_tickets` WHERE `name` = '{$_SESSION['pname']}' AND `status` = 'Closed'");
			echo "
		<table class=\"table table-bordered table-hover table-striped\">
			<thead>
				<tr>
					<td>
						Ticket Number
					</td>
					<td>
						Ticket Name
					</td>
					<td>
						Last Reply
					</td>
					<td>
						Status
					</td>
				</tr>
			</thead>";
			$TicketNumber = 0;
			while($viewTickets = $getclosedtickets->fetch_assoc()){
				echo "
					<tr>
						<td>
							" . ++$TicketNumber . "
						</td>
						<td>
							<a href = \"?cype=ucp&amp;page=ticket&amp;ticket=Yes&amp;a=$viewTickets[ticketid]\">
								" . $viewTickets['title'] . "
							</a>
						</td>
						<td>
							" . $viewTickets['date'] . "
						</td>
						<th>
							" . $viewTickets['status'] . "
						</th>
					</tr>
				";
			}
			echo "
		</table>
		<br /><a href=\"?cype=ucp&amp;page=ticket\" class=\"btn btn-primary\">&laquo; Go Back</a>
		";
	}
} else {
	header('Location:?cype=ucp');
}
?>