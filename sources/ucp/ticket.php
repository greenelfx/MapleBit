<script src="assets/libs/cksimple/ckeditor.js"></script>
<?php
	require_once 'assets/libs/HTMLPurifier.standalone.php';
	$ticketconfig = HTMLPurifier_Config::createDefault();
	$ticketconfig->set('HTML.Allowed', 'p, b, u, s, ol, li, ul, i, em, strong, blockquote, small, hr'); 
	$ticketpurifier = new HTMLPurifier($ticketconfig);
if(isset($_SESSION['id'])){
	if(isset($_SESSION['pname']) && $_SESSION['pname'] != "checkpname"){
	if(!isset($_GET['ticket'])){
	$pname = $_SESSION['pname'];
	echo "
		<h2 class=\"text-left\">Your Tickets</h2><hr/>
		<table class=\"table table-striped table-hover table-bordered\">
		<thead>
			<tr>
				<th>Ticket Number</th>
				<th>Ticket Name</th>
				<th>Last Reply</th>
				<th>Status</th>
			</tr>
		</thead>";
			$gettickets = $mysqli->query("SELECT * FROM ".$prefix."tickets WHERE name = '".$pname."' ORDER BY ticketid DESC");
			$getnumer = $gettickets->num_rows;
			$NumberTicket = 0;
			while($tickets = $gettickets->fetch_assoc()){
				echo "
					<tr>
						<th>
							" . ++$NumberTicket . "
						</th>
						<td>
							<a href =\"?base=ucp&amp;page=ticket&amp;a=$tickets[ticketid]&amp;ticket=Yes\">
								" . $tickets['title'] . "
							</a>
						</td>
						<td>
							" . $tickets['date'] . "
						</td>
						<th>";
							if($tickets['status'] == 1){echo "Open";} elseif($tickets['status'] == 0) {echo "Closed"; } else {echo "Unknown";}
						echo "</th>
					</tr>
				";
			}
		echo "
		</table>";
			echo "<hr/><a href=\"?base=ucp&amp;page=ticket&amp;ticket=create\" class=\"btn btn-primary\">Create Ticket</a>&nbsp;<a href=\"?base=ucp&amp;page=ticket&amp;ticket=closed\" class=\"btn btn-info\">View Closed Tickets</a>";
		
	}
	if(@$_GET['ticket'] == "create"){
	//Create a new ticket. Only limits 5 tickets per user.
		echo " 
			<h2 class=\"text-left\">Create Ticket</h2><hr/>
				<form method=\"post\" role=\"form\">
				<div class=\"form-group\">
					<label for=\"ticketCategory\">Ticket Category</label>
					<select name=\"type\" id=\"ticketCategory\" class=\"form-control\">
						<option value=\"Game\">Game Help</option>
						<option value=\"Website\">WebSite Help</option>
						<option value=\"Abuse\">Account Help</option>
						<option value=\"Account\">Banning Appeal</option>
					</select>
				</div>
				<div class=\"form-group\">
					<label for=\"typeTicket\">Select Type</label>";
							echo "
									<select name=\"support\" id=\"typeTicket\" class=\"form-control\">
										<option selected=\"selected\">&middot; Ticket Subgroup &middot;</option>
									<optgroup label=\"Game Help\">
										<option value=\"Bug\" >Bug Report</option>
										<option value=\"NPC Bug\" >NPC Bug</option>
										<option value=\"Connection\">Connection</option>
									<optgroup label=\"Website\">
										<option value=\"Missing / Broken Link\">Missing / Broken Link</option>
										<option value=\"Error on Page\">Error on a Page</option>
										<option value=\"Page is not functioning\">Page not functioning correctly</option>
									<optgroup label=\"Account Help\">
										<option value=\"Account\" >Account issue</option>
										<option value=\"Abuse\" >User Abuse</option>
									<optgroup label=\"Banning Help\">
										<option value=\"Appeal\" >Ban Appeal</option>
								</select>
				</div>
				<div class=\"form-group\">
					<label for=\"ticketTitle\">Title</label>
						<input type=\"text\" name=\"title\" maxlength=\"30\" class=\"form-control\" id=\"ticketTitle\" required><br/>
				</div>
				<div class=\"form-group\">
					<label for=\"ticketDetails\">Details and Information</label>
					<textarea name=\"details\" style=\"height:200px;\" class=\"form-control\" id=\"ticketDetails\" required></textarea><br/>
					<input type=\"submit\" name=\"ticket\" value=\"Send Ticket &raquo;\" class=\"btn btn-primary\"/>
				</div>
				</form>";
				if(isset($_POST['ticket'])){
					$type = mysql_escape($_POST['type']);
					$support = mysql_escape($_POST['support']);
					$title = mysql_escape($_POST['title']);
					$details = mysql_escape($_POST['details']);
					
					if($type == ""){
						echo "<div class=\"alert alert-danger\">Please select the type of ticket you inquiry about.</div>";
					}
					elseif($support == ""){
						echo "<div class=\"alert alert-danger\">Please select the type of statement for this ticket.</div>";
					}
					elseif($title == "" || strlen($title) < "5"){
						echo "<div class=\"alert alert-danger\">You did not enter a title name or the title name is too short.</div>";
					}
					elseif(strlen($details) < 25 || $details == ""){
						echo "<div class=\"alert alert-danger\">Please supply more information about the problem you are having. Make sure to include details.</div>";
					}
					else{
						$newticket = $mysqli->query("INSERT INTO ".$prefix."tickets (title, type, support_type, details, date, ip, name, status) 
							VALUES ('".$title."', '".$type."', '".$support."', '".$details."', '".date('F d - g:i A')."', '".$_SERVER['REMOTE_ADDR']."', '".$_SESSION['pname']."', 1)");
							
						if($newticket){
							echo "<meta http-equiv=\"refresh\" content=\"0; url=?base=ucp&amp;page=ticket\"/>";
						}
						else{
							echo "<div class=\"alert alert-danger\">The ticket you have created was not able to be completed due to an error. Please make sure you have selected the correct type of ticket.</div>";
						}
					}
				}
	}
	elseif(@$_GET['ticket'] == "Yes"){
		$GrabTicket = $mysqli->query("SELECT * FROM ".$prefix."tickets LEFT JOIN ".$prefix."tcomments ON ".$prefix."tickets.ticketid = ".$prefix."tcomments.ticketid WHERE ".$prefix."tickets.ticketid = '".$mysqli->real_escape_string($_GET['a'])."'");
		$viewTicket = $GrabTicket->fetch_assoc();
		$getResponse = $mysqli->query("SELECT * FROM ".$prefix."tcomments WHERE ticketid = '".sql_sanitize($_GET['a'])."'");
		$countTicket = $getResponse->num_rows;
	//View the ticket
		if($_SESSION['pname'] != $viewTicket['name']){
			echo "
				<div class=\"alert alert-danger\">You are not allowed to view this ticket. Your actions have been logged.</div>
				<meta http-equiv=\"refresh\" content=\"1; url=?base=main\"/>
			";
			exit();
		}
		echo "
			<h2 class=\"text-left\">Viewing Ticket</h2>
			<hr/>
				<b>Created By:</b> $viewTicket[name]<br/>
				<b>Date:</b> $viewTicket[date]<br/>
				<b>Ticket Details:</b><br/> 
				$viewTicket[details]<hr/>";
				while($c = $getResponse->fetch_assoc()){
				$clean_ticket = $ticketpurifier->purify($c['content']);
				// Get webadmin status
				$queryadmin = $mysqli->query("SELECT ".$prefix."tcomments.user, ".$prefix."profile.name, ".$prefix."profile.accountid, accounts.webadmin FROM ".$prefix."tcomments INNER JOIN ".$prefix."profile ON ".$prefix."tcomments.user = ".$prefix."profile.name INNER JOIN accounts ON ".$prefix."profile.accountid = accounts.id WHERE ".$prefix."tcomments.user = '".$c['user']."'");
				$adminstatus = $queryadmin->fetch_assoc();
				if($adminstatus['webadmin'] > 0){
					echo "<div class=\"well well2\">";
				} else {
					echo "<div class=\"well\">";
				}
					echo $c['user'] . " posted on " . $c['date_com'] . "<br/><br/> " . $clean_ticket . "</div><hr/>";
				}
				/*if($countTicket < 1){
					echo "There is currently no responces to this ticket yet. If you need to add more details, go ahead and add one more!";
				}*/
				if($viewTicket['status'] == "Closed"){
					echo "<div class=\"alert alert-info\">This ticket is closed. If your solution is not here, please open another ticket.</div>";
				}
				else {
				echo "
					<form method=\"post\">
					 <div class=\"form-group\">
						<label for=\"ticketDetails\">Response:</label>
						<textarea name=\"comment\" style=\"height:150px;\" class=\"form-control\" id=\"ticketDetails\"></textarea>
						<hr/>
						<input type=\"submit\" name=\"subcomment\" value=\"Submit Response\" class=\"btn btn-primary\"/>
					</div>
					</form>
				";
				}
				if(isset($_POST['subcomment'])){
					$postComment = $mysqli->real_escape_string($_POST['comment']);
					if(strlen($postComment) < 10){
						echo "Please provide more information.";
					}
					else{
						$insertComment = $mysqli->query("INSERT INTO `".$prefix."tcomments` (ticketid, user, content, date_com)
							VALUES "."('".sql_sanitize($_GET['a'])."', '".$_SESSION['pname']."', '".$postComment."', '".date('F d - g:i A')."')") or die(mysql_error());
						
						$insertComment = $mysqli->query("UPDATE `".$prefix."tickets` SET `date` = '".date('F d - g:i A')."' WHERE `ticketid` = '".sql_sanitize($_GET['a'])."'");
							
						if($insertComment){
							echo "<meta http-equiv=\"refresh\" content=\"0; url=\"/>";
						}
						else{
							echo "<div class=\"alert alert-danger\">There was an error processing your update. Please notify the admin.</div>";
						}
					}
				}
	}
	elseif(isset($_GET['ticket']) == "closed"){
	echo "<h2 class=\"text-left\">Closed Tickets</h2><hr/>";
		$getclosedtickets = $mysqli->query("SELECT * FROM ".$prefix."tickets WHERE name = '".$_SESSION['pname']."' AND status = 0");
		$countclosedtickets = $getclosedtickets->num_rows;
		if($countclosedtickets == 0) {
			echo "<div class=\"alert alert-danger\">Oops! You don't have any closed tickets!";
		}
		else {
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
							<a href = \"?base=ucp&amp;page=ticket&amp;ticket=Yes&amp;a=$viewTickets[ticketid]\">
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
		<br /><a href=\"?base=ucp&amp;page=ticket\" class=\"btn btn-primary\">&laquo; Go Back</a>";
		}
	}
} else {
	echo "<div class=\"alert alert-danger\">You must assign a profile name before you can submit tickets.</div>";
}
}else {
	header('Location:?base=ucp');
}
?>
<script>
<?php
	if(isset($_SESSION['id'])){
?>
CKEDITOR.replace( 'ticketDetails', {
    allowedContent: 'b i u li ol ul blockquote anchor hr small'
});
$(function() {
for ( var i in CKEDITOR.instances ){
   var currentInstance = i;
   break;
}
var oEditor = CKEDITOR.instances[currentInstance];
});
<?php
	}
?>
</script>