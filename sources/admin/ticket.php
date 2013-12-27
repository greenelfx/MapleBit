<?php 
if(isset($_SESSION['admin'])){
	if(!isset($_GET['ticket']) || isset($_GET['ticket']) == ""){
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
				while($tickets = $gettickets->fetch_assoc()){
				if($tickets['status'] == 1){ $status = "Open";}
					echo "
						<tr>
							<th>
								" . $tickets['ticketid'] . "
							</th>
							<td>
								<a href =\"?base=admin&amp;page=ticket&amp;a=$tickets[ticketid]&amp;ticket=Yes\">
									" . $tickets['title'] . "
								</a>
							</td>
							<td>
								" . $tickets['date'] . "
							</td>
							<th>
								" .$status. "
							</th>
						</tr>
					";
				}
			echo "</table>";
		} else {
			echo "<h2 class=\"text-left\">Ticket Management</h2><hr/><div class=\"alert alert-success\">No open tickets.</div>";
		}
	}
	elseif($_GET['ticket'] == "Yes"){
		$GrabTicket = $mysqli->query("SELECT * FROM ".$prefix."tickets LEFT JOIN ".$prefix."tcomments ON ".$prefix."tickets.ticketid = ".$prefix."tcomments.ticketid WHERE ".$prefix."tickets.ticketid = '".mysql_real_escape_string($_GET['a'])."'");
		$viewTicket = $GrabTicket->fetch_assoc();
		$getResponse = $mysqli->query("SELECT * FROM ".$prefix."tcomments WHERE ticketid = '".sql_sanitize($_GET['a'])."'");
		$countTicket = $getResponse->num_rows;
		$content = stripslashes($viewTicket['details']);
		echo "
			<h2 class=\"text-left\">Viewing Ticket</h2><hr/>
				Created By: $viewTicket[name]<br/>
				Date: $viewTicket[date]
				<hr/>
				Ticket Content:<br/> 
				$content <br/><br/>
				<hr/>
				Responses:
				";
				while($c = $getResponse->fetch_assoc()){
					echo "<pre>";
					echo $c['user'] . " posted on " . $c['date_com'] . "<br/><br/> " . $mysqli->real_escape_string($c['content']) . "</pre><hr/>";
				}
				if($countTicket < 1){
					echo "<hr/>Please make a response to this ticket.<hr/>";
				}
				echo "
					Make a comment to this ticket:<br/>
					<form method=\"post\" action\"\">
						<textarea name=\"comment\" style=\"height:150px;\" class=\"form-control\"/></textarea><hr/>
							<input type=\"submit\" name=\"subcomment\" value=\"Submit Response\" class=\"btn btn-primary\"/>
							<input type=\"submit\" name=\"close\" value=\"Close Ticket\" class=\"btn btn-default\"/>
					</form>
				";
				if(isset($_POST['subcomment'])){
					$postComment = sanitize_space($_POST['comment']);
						
					if(strlen($postComment) < 25){
						echo "Please provide more information.";
					}
					else{
						$insertComment = $mysqli->query("INSERT INTO ".$prefix."tcomments (ticketid, user, content, date_com)
							VALUES "."('".$_GET['a']."', '".$_SESSION['pname']."', '".$postComment."', '".date('F d - g:i A')."')") or die(mysql_error());
						$insertComment = $mysqli->query("UPDATE ".$prefix."tickets SET date = '".date('F d - g:i A')."' WHERE ticketid = '".sql_sanitize($_GET['a'])."'") or die(mysql_error());
						if($insertComment){
							echo "<meta http-equiv=\"refresh\" content=\"0; url=\"/>";
						}
						else{
							echo "There was an error processing your update. Please notify the admin.";
						}
					}
				}
				if(isset($_POST['close'])){
					$closeTicket = $mysqli->query("UPDATE ".$prefix."tickets SET status = 0 WHERE ticketid = '".sql_sanitize($_GET['a'])."'");
					if($closeTicket){
						echo "<div class=\"alert alert-success\">This ticket was successfully closed! You will be redirected in five seconds.</div>";
						redirect_wait5("?base=admin&amp;page=ticket");
					}
				}
	}
} else {
	redirect("?base");
}
?>