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

if(isset($_SESSION['gm'])){
	if($_GET['ticket'] == ""){
	//If you are logged in, you will be sent to the main ticket page.
	echo "
	<fieldset>
		<legend>
			<strong>Your Tickets</strong>
		</legend>
		<table border=\"1\" width=\"100%\">
			<tr>
				<td width = \"15%\">
					Ticket Number
				</td>
				<td width = \"45%\">
					Ticket Name
				</td>
				<td width = \"25%\">
					Last Reply
				</td>
				<td width = \"15%\">
					Status
				</td>
			</tr>";
			$gettickets = mysql_query("SELECT * FROM `cype_tickets` WHERE `status` = 'Open' ORDER BY `ticketid` ASC")or die(mysql_error());
			$getnumber = mysql_num_rows($gettickets);
			while($tickets = mysql_fetch_array($gettickets)){
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
	error_reporting(E_ALL);
		$GrabTicket = mysql_query("SELECT * FROM `cype_tickets` LEFT JOIN `cype_tcomments` ON cype_tickets.ticketid = cype_tcomments.ticketid WHERE cype_tickets.ticketid = '{$_GET['a']}'");
		$viewTicket = mysql_fetch_array($GrabTicket);
		$getResponce = mysql_query("SELECT * FROM `cype_tcomments` WHERE `ticketid` = '{$_GET['a']}'");
		$countTicket = mysql_num_rows($getResponce);
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
				Responces: <br/><br/>
				";
				while($c = mysql_fetch_array($getResponce)){
					include('sources/parser/bbcode.php');
					echo $c['user'] . " posted on " . $c['date_com'] . "<br/><br/> " . stripslashes($c['content']) . "<hr/>";
				}
				if($countTicket < 1){
					echo "Please make a responce to this ticket.";
				}
				echo "
					<hr/>
					Make a comment to this ticket:<br/>
					<form method=\"post\" action\"\">
						<textarea name=\"comment\" rows=\"5\" cols=\"86%\"/></textarea>
						<center>
							<input type=\"submit\" name=\"subcomment\" value=\"Submit Responce\"/>
							<input type=\"submit\" name=\"close\" value=\"Close Ticket\"/>
						</center>
					</form>
				";
				if(isset($_POST['subcomment'])){
					$postComment = mysql_real_escape_string($_POST['comment']);
						
					if(strlen($postComment) < 25){
						echo "Please provide more information.";
					}
					else{
						$insertComment = mysql_query("INSERT INTO `cype_tcomments` (ticketid, user, content, date_com)
							VALUES "."('".$_GET['a']."', '".$_SESSION['pname']."', '".$postComment."', '".date('F d - g:i A')."')") or die(mysql_error());
						$insertComment = mysql_query("UPDATE `cype_tickets` SET `date` = '".date('F d - g:i A')."' WHERE `ticketid` = '{$_GET['a']}'") or die(mysql_error());
						if($insertComment){
							echo "<meta http-equiv=\"refresh\" content=\"0; url=\"/>";
						}
						else{
							echo "There was an error processing your update. Please notify the admin.";
						}
					}
				}
				if(isset($_POST['close;'])){
					$closeTicket = mysql_query("UPDATE `cype_tickets` SET `status` = 'Closed' WHERE `id` = '{$_GET['a']}'");
					if($closeTicket){
						echo "This ticket was successfuly closed! You will be redirected in two seconds.
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