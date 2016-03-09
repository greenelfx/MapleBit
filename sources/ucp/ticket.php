<?php
if(basename($_SERVER["PHP_SELF"]) == "ticket.php"){
    die("403 - Access Forbidden");
}
?>
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
			while($tickets = $gettickets->fetch_assoc()){
				echo "
					<tr>
						<td>
							" . $tickets['ticketid'] . "
						</td>
						<td>
							<a href =\"?base=ucp&amp;page=ticket&amp;a=$tickets[ticketid]&amp;ticket=Yes\">
								" . htmlspecialchars($tickets['title'], ENT_QUOTES, 'UTF-8') . "
							</a>
						</td>
						<td>
							" . $tickets['date'] . "
						</td>
						<td>";
							if($tickets['status'] == 1){echo "<span class=\"label label-success\">Open</span>";} elseif($tickets['status'] == 0) {echo "<span class=\"label label-default\">Closed</span>"; } else {echo "<span class=\"label label-warning\">Unknown</span>";}
						echo "</td>
					</tr>
				";
			}
		echo "
		</table>";
			echo "<hr/><a href=\"?base=ucp&amp;page=ticket&amp;ticket=create\" class=\"btn btn-primary\">Create Ticket</a>&nbsp;<a href=\"?base=ucp&amp;page=ticket&amp;ticket=closed\" class=\"btn btn-info\">View Closed Tickets</a>";

	}
	if(@$_GET['ticket'] == "create"){
		$gettickets = $mysqli->query("SELECT * FROM ".$prefix."tickets WHERE name = '".$_SESSION['pname']."' AND status = 1 ORDER BY ticketid DESC");
		$opentickets = $gettickets->num_rows;
		if($opentickets < 5) {
		echo "
			<h2 class=\"text-left\">Create Ticket</h2><hr/>
				<form method=\"post\" role=\"form\">
				<div class=\"form-group\">
					<label for=\"ticketCategory\">Ticket Category</label>
					<select name=\"type\" id=\"ticketCategory\" class=\"form-control\">
						<option selected=\"true\" disabled=\"disabled\">Choose Category</option>
						<option value=\"Game\">Game Help</option>
						<option value=\"Website\">Website Help</option>
						<option value=\"Abuse\">Account Help</option>
						<option value=\"Account\">Banning Appeal</option>
					</select>
				</div>
				<div class=\"form-group\">
					<label for=\"typeTicket\">Select Type</label>";
							echo "
									<select name=\"support\" id=\"typeTicket\" class=\"form-control\">
										<option selected=\"true\" disabled=\"disabled\">Choose Type</option>
									<optgroup label=\"Game Help\">
										<option value=\"Bug\" >Bug Report</option>
										<option value=\"NPC Bug\" >NPC Bug</option>
										<option value=\"Connection\">Connection</option>
									<optgroup label=\"Website Help\">
										<option value=\"Missing / Broken Link\">Missing / Broken Link</option>
										<option value=\"Error on Page\">Error on a Page</option>
										<option value=\"Page is not functioning\">Page not functioning correctly</option>
									<optgroup label=\"Account Help\">
										<option value=\"Account\" >Account issue</option>
										<option value=\"Abuse\" >User Abuse</option>
									<optgroup label=\"Banning Appeal\">
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
					if(!isset($_POST['type']) || $_POST['type'] == ""){
						echo "<div class=\"alert alert-danger\">Please select the ticket category.</div>";
					}
					elseif(!isset($_POST['support']) || $_POST['support'] == ""){
						echo "<div class=\"alert alert-danger\">Please select the ticket type.</div>";
					}
					elseif(!isset($_POST['title']) || strlen($_POST['title']) < 5){
						echo "<div class=\"alert alert-danger\">You did not enter a title name or the title name is too short.</div>";
					}
					elseif(!isset($_POST['details']) || strlen($_POST['details']) < 25){
						echo "<div class=\"alert alert-danger\">Please supply more information about the problem you are having. Make sure to include details.</div>";
					}
					else{
						$type = $mysqli->real_escape_string($_POST['type']);
						$support = $mysqli->real_escape_string($_POST['support']);
						$title = $mysqli->real_escape_string($_POST['title']);
						$details = $mysqli->real_escape_string($_POST['details']);
						$newticket = $mysqli->query("INSERT INTO ".$prefix."tickets (title, type, support_type, details, date, ip, name, status)
							VALUES ('".$title."', '".$type."', '".$support."', '".$details."', '".date('F d - g:i A')."', '".$_SERVER['REMOTE_ADDR']."', '".$_SESSION['pname']."', 1)");

						if($newticket){
							echo "<meta http-equiv=\"refresh\" content=\"0; url=?base=ucp&amp;page=ticket\"/>";
						}
						else{
							echo "<div class=\"alert alert-danger\">An error has occurred. Please contact your web administrator.</div>";
						}
					}
				}
			}
			else {
				echo "<h2 class=\"text-left\">Create Ticket</h2><hr/><div class=\"alert alert-danger\">Unfortunately you cannot create more tickets. Please wait for your older tickets to process.</div>";
			}
	}
	elseif(@$_GET['ticket'] == "Yes"){
		$GrabTicket = $mysqli->query("SELECT * FROM ".$prefix."tickets LEFT JOIN ".$prefix."tcomments ON ".$prefix."tickets.ticketid = ".$prefix."tcomments.ticketid WHERE ".$prefix."tickets.ticketid = '".$mysqli->real_escape_string($_GET['a'])."'");
		$viewTicket = $GrabTicket->fetch_assoc();
		$getResponse = $mysqli->query("SELECT * FROM ".$prefix."tcomments WHERE ticketid = '".$mysqli->real_escape_string($_GET['a'])."'");
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
				<b>Ticket Details:</b><hr/>
				<div class=\"breakword\">" . $viewTicket['details'] . "</div>";
				while($c = $getResponse->fetch_assoc()){
				$clean_ticket = $ticketpurifier->purify($c['content']);
				// Get webadmin status
				$queryadmin = $mysqli->query("SELECT ".$prefix."tcomments.user, ".$prefix."profile.name, ".$prefix."profile.accountid, accounts.webadmin FROM ".$prefix."tcomments INNER JOIN ".$prefix."profile ON ".$prefix."tcomments.user = ".$prefix."profile.name INNER JOIN accounts ON ".$prefix."profile.accountid = accounts.id WHERE ".$prefix."tcomments.user = '".$c['user']."'");
				$adminstatus = $queryadmin->fetch_assoc();
				if($adminstatus['webadmin'] > 0){
					echo "<hr/><div class=\"well well2 breakword\">";
				} else {
					echo "<hr/><div class=\"well breakword\">";
				}
					echo $c['user'] . " posted on " . $c['date_com'] . "<br/><br/> " . $clean_ticket . "</div>";
				}
				/*if($countTicket < 1){
					echo "There is currently no responces to this ticket yet. If you need to add more details, go ahead and add one more!";
				}*/
				if($viewTicket['status'] == 0){
					echo "<hr/><div class=\"alert alert-info\">This ticket is closed. If your solution is not here, please open another ticket.</div>";
				}
				else {
				echo "
					<hr/>
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
							VALUES "."('".$mysqli->real_escape_string($_GET['a'])."', '".$_SESSION['pname']."', '".$postComment."', '".date('F d - g:i A')."')") or die(mysql_error());

						$insertComment = $mysqli->query("UPDATE `".$prefix."tickets` SET `date` = '".date('F d - g:i A')."' WHERE `ticketid` = '".$mysqli->real_escape_string($_GET['a'])."'");

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
			while($viewTickets = $getclosedtickets->fetch_assoc()){
				echo "
					<tr>
						<td>
							" . $viewTickets['ticketid'] . "
						</td>
						<td>
							<a href = \"?base=ucp&amp;page=ticket&amp;ticket=Yes&amp;a=$viewTickets[ticketid]\">
								" . htmlspecialchars($viewTickets['title'], ENT_QUOTES, 'UTF-8') . "
							</a>
						</td>
						<td>
							" . $viewTickets['date'] . "
						</td>
						<th>
							Closed
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