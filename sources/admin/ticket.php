<?php
if(basename($_SERVER["PHP_SELF"]) == "ticket.php") {
    die("403 - Access Forbidden");
}
?>
<script src="assets/libs/cksimple/ckeditor.js"></script>
<?php
	require_once 'assets/libs/HTMLPurifier.standalone.php';
	$ticketconfig = HTMLPurifier_Config::createDefault();
	$ticketconfig->set('HTML.Allowed', 'p, b, u, s, ol, li, ul, i, em, strong, blockquote, small, hr');
	$ticketpurifier = new HTMLPurifier($ticketconfig);
if(isset($_SESSION['admin'])) {
	if(!isset($_GET['ticket']) || isset($_GET['ticket']) == "") {
	$gettickets = $mysqli->query("SELECT * FROM ".$prefix."tickets WHERE status = 1 ORDER BY `ticketid` ASC")or die(mysql_error());
	$countgettickets = $gettickets->num_rows;
	if($countgettickets > 0 ) {
		echo "
		<h2 class=\"text-left\">Ticket Management</h2><hr/>
			<table class=\"table table-striped table-bordered table-hover\">
			<thead>
				<tr>
					<th>Ticket Number</th>
					<th>Ticket Name</th>
					<th>Last Reply</th>
					<th>Status</th>
				</tr>
			</thead>";
				while($tickets = $gettickets->fetch_assoc()) {
				if($tickets['status'] == 1) { $status = "Open";}
					echo "
						<tr>
							<td>
								" . $tickets['ticketid'] . "
							</td>
							<td>
								<a href =\"?base=admin&amp;page=ticket&amp;a=$tickets[ticketid]&amp;ticket=Yes\">
									" . htmlspecialchars($tickets['title'], ENT_QUOTES, 'UTF-8') . "
								</a>
							</td>
							<td>
								" . $tickets['date'] . "
							</td>
							<td>";
							if($tickets['status'] == 1) {echo "<span class=\"label label-success\">Open</span>";} elseif($tickets['status'] == 0) {echo "<span class=\"label label-default\">Closed</span>"; } else {echo "<span class=\"label label-warning\">Unknown</span>";}
							echo "</td>
						</tr>
					";
				}
			echo "</table>";
		} else {
			echo "<h2 class=\"text-left\">Ticket Management</h2><hr/><div class=\"alert alert-success\">No open tickets.</div>";
		}
	}
	elseif($_GET['ticket'] == "Yes") {
		$GrabTicket = $mysqli->query("SELECT * FROM ".$prefix."tickets LEFT JOIN ".$prefix."tcomments ON ".$prefix."tickets.ticketid = ".$prefix."tcomments.ticketid WHERE ".$prefix."tickets.ticketid = '".mysql_real_escape_string($_GET['a'])."'");
		$viewTicket = $GrabTicket->fetch_assoc();
		$getResponse = $mysqli->query("SELECT * FROM ".$prefix."tcomments WHERE ticketid = '".$mysqli->real_escape_string($_GET['a'])."'");
		$countTicket = $getResponse->num_rows;
		$content = stripslashes($viewTicket['details']);
		echo "
			<h2 class=\"text-left\">Viewing Ticket</h2><hr/>
				<b>Created By:</b> $viewTicket[name]<br/>
				<b>Date:</b> $viewTicket[date]<br/>
				<b>Ticket Details:</b><hr/>
				<div class=\"breakword\">" . $content . "</div>";
				while($c = $getResponse->fetch_assoc()) {
				$clean_ticket = $ticketpurifier->purify($c['content']);
				// Get webadmin status
				$queryadmin = $mysqli->query("SELECT ".$prefix."tcomments.user, ".$prefix."profile.name, ".$prefix."profile.accountid, accounts.webadmin FROM ".$prefix."tcomments INNER JOIN ".$prefix."profile ON ".$prefix."tcomments.user = ".$prefix."profile.name INNER JOIN accounts ON ".$prefix."profile.accountid = accounts.id WHERE ".$prefix."tcomments.user = '".$c['user']."'");
				$adminstatus = $queryadmin->fetch_assoc();
				if($adminstatus['webadmin'] > 0) {
					echo "<hr/><div class=\"well well2 breakword\">";
				} else {
					echo "<hr/><div class=\"well breakword\">";
				}
				echo "<b>" . $c['user'] . "</b> posted on " . $c['date_com'] . "<br/><br/> " . $clean_ticket . "</div>";
				}
				if($countTicket < 1) {
					echo "<hr/><div class=\"alert alert-info\">Please make a response to this ticket.</div>";
				}
				echo "
					<hr/>
					Make a comment to this ticket:<br/>
					<form method=\"post\" action\"\">
						<textarea name=\"comment\" style=\"height:150px;\" class=\"form-control\" id=\"ticketDetails\"/></textarea><hr/>
							<input type=\"submit\" name=\"subcomment\" value=\"Submit Response\" class=\"btn btn-primary\"/>
							<input type=\"submit\" name=\"close\" value=\"Close Ticket\" class=\"btn btn-default\"/>
					</form>
				";
				if(isset($_POST['subcomment'])) {
					$postComment = $mysqli->real_escape_string($_POST['comment']);

					if(strlen($postComment) < 10) {
						echo "Please provide more information.";
					}
					else{
						$insertComment = $mysqli->query("INSERT INTO ".$prefix."tcomments (ticketid, user, content, date_com)
							VALUES "."('".$_GET['a']."', '".$_SESSION['pname']."', '".$postComment."', '".date('F d - g:i A')."')") or die(mysql_error());
						$insertComment = $mysqli->query("UPDATE ".$prefix."tickets SET date = '".date('F d - g:i A')."' WHERE ticketid = '".$mysqli->real_escape_string($_GET['a'])."'") or die(mysql_error());
						if($insertComment) {
							echo "<meta http-equiv=\"refresh\" content=\"0; url=\"/>";
						}
						else{
							echo "There was an error processing your update. Please notify the admin.";
						}
					}
				}
				if(isset($_POST['close'])) {
					$closeTicket = $mysqli->query("UPDATE ".$prefix."tickets SET status = 0 WHERE ticketid = '".$mysqli->real_escape_string($_GET['a'])."'");
					if($closeTicket) {
						echo "<br/><div class=\"alert alert-success\">This ticket was successfully closed! You will be redirected in five seconds.</div>";
						redirect_wait5("?base=admin&amp;page=ticket");
					}
				}
	}
} else {
	redirect("?base");
}
?>
<script>
<?php
	if(isset($_SESSION['id'])) {
?>
CKEDITOR.replace( 'ticketDetails', {
    allowedContent: 'b i u li ol ul blockquote anchor hr small'
});
$(function() {
for ( var i in CKEDITOR.instances ) {
   var currentInstance = i;
   break;
}
var oEditor = CKEDITOR.instances[currentInstance];
});
<?php
	}
?>
</script>