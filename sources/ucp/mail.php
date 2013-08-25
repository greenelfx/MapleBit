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

if(basename($_SERVER["PHP_SELF"]) != "mail.php" && $_SESSION['id'] && $_SESSION['pname'] != NULL) {
ignore_user_abort(true);	# Prevent User Abort
error_reporting(0);
?>
	<legend>Mailbox</legend>
		<ul class="breadcrumb">
			<li><a href="?cype=ucp&amp;page=mail&amp;s=3">Unread: <?php mailStats(3)?></a><span class="divider">/</span></li>
			<li><a href="?cype=ucp&amp;page=mail&amp;s=2">Read: <?php mailStats(2)?></a><span class="divider">/</span></li>
			<li><a href="?cype=ucp&amp;page=mail&amp;s=1">Sent: <?php mailStats(1)?></a><span class="divider">/</span></li>
			<li><a href="?cype=ucp&amp;page=mail&amp;s=5">Drafts: <?php mailStats(5)?></a><span class="divider">/</span></li>				
		</ul>
			<legend>Send Mail</legend>
				<form method="post" action="?cype=ucp&amp;page=mail&amp;send">
				<input type="text" value="<?php if($_GET['uc']){ echo $_GET['uc']; }?>"name="recipent" placeholder="Recipient" required/><br/>
				<input type="text" name="title" placeholder="PM Title" required/>
				<textarea name="content" style="width:100%;height:150px;" placeholder="Your Message" required></textarea><br/>
				<input type="submit" name="send" value="Send" class="btn btn-success"/>
				<input type="submit" name="send" value="Draft" class="btn btn-info"/>				
				<input type="reset" id="reset" value="Reset" class="btn btn-inverse"/>
				</form>
				<hr/>
				<?php
					$tid = intval($_GET['s']);
					$title = "";
					if(isset($_GET['showmail'])) {
						$title = "Viewing Mail";
					} else if($tid == 2) {
						$title = "Inbox - Read Messages";
					} else if($tid == 3) {
						$title = "Inbox - Unread";
					} else if($tid == 1) {
						$title = "Outbox - Sent Messages";
					} else if($tid == 5) {
						$title = "Drafts";
					}
				?>
			<legend><?php echo $title?></legend>
					<?php
					if(isset($_GET['showmail']) && !empty($_GET['showmail'])) {
						$error = "<legend>System Error</legend> Invalid Message ID Or You Do Not Have Valid Permission.";
						$mid = $mysqli->real_escape_string($_GET['showmail']) or die();
						$mailquery = $mysqli->query("SELECT * FROM `cype_mail` WHERE `mailid`='".$mid."'") or die();
						$mailarray = $mailquery->fetch_assoc();
						if($mailarray['from'] == $_SESSION['pname']){
							$mailuser = $mysqli->query("SELECT * FROM `cype_mail` WHERE `from` = {$_SESSION['pname']}");
						} else {
							$mailuser = $mysqli->query("SELECT * FROM `cype_mail` WHERE `to` = {$_SESSION['pname']}");
						}
						if($mailquery->num_rows > 0) {
							$mailaction = "Sent To: ";
							$mailuserd = "From ";
							$mailuser = $mailarray['from'];
							if($mailarray['status'] == 1 || $mailarray['status'] == 5 && $mailarray['from'] == $_SESSION['pname']) {
								$mailaction = "Sent To: ";
								$mailuserd = "Sender: ";
								$mailuser = $mailarray['from'];
							}
							if($mailarray['to'] == $_SESSION['pname'] || $mailarray['from'] == $_SESSION['pname']) {
								if($mailarray['status'] == 3) {
									$mysqli->query("UPDATE `cype_mail` SET status = '2' WHERE `mailid`='".$mailarray['mailid']."'") or die();
							}
							echo "
								<form method=\"post\" action=\"?cype=ucp&amp;page=mail\">
									<b>Title</b>".$mailarray['title']."<br/>
									<b>".$mailuserd.":</b> ".$mailuser."<br />
									<b>Date:</b> ".$mailarray['dateadded']."<br/>
										<pre>".stripslashes($mailarray['content'])."</pre>
									<br />
										<input type=\"submit\" name=\"modify\" value=\"Reply\" class=\"btn btn-success\"/>
										<input type=\"submit\" name=\"modify\" value=\"Delete\" class=\"btn btn-inverse\"/>
										<input type=\"hidden\" name=\"action\" value=\"".$mailarray['mailid']."\"/>
							</form>
							";
							} else {
								echo $error;
							}
						} else {
							echo $error;
						}
					} else {
					?>
					<form method="post" action="?cype=ucp&amp;page=mail">
					<?php
						$status = $mysqli->real_escape_string(intval($_GET['s']));
						$page = $mysqli->real_escape_string(intval($_GET['p']));
						
						/*
						Mailing Status'
						5 = Drafts
						4 = Saved
						3 = Unread
						2 = Read
						1 = Sent
						0 = Junk
						*/

						if($status > 5 || $status < 0 || !isset($_GET['s'])) {
							if($_GET['s'] == 0){
								$status = 0;
							} elseif($_GET['s'] == 1){
								$status = 1;
							} elseif($_GET['s'] == 2){
								$status = 2;
							} elseif($_GET['s'] == 5){
								$status = 5;
							} else{
								$status = 3;
							}
						}
						$totalMails = 10;	# Mails Per Page
						if($status == 1 || $status == 5){
							$mailCount = $mysqli->query("SELECT COUNT(*) FROM `cype_mail` WHERE `from`='".$_SESSION['pname']."' AND `status`='".$status."'") or die();
						} else {
							$mailCount = $mysqli->query("SELECT COUNT(*) FROM `cype_mail` WHERE `to`='".$_SESSION['pname']."' AND `status`='".$status."'") or die();
						}
						$getrows = $mailCount->fetch_assoc();
						$totalpages = ceil($getrows[0] / $totalMails);
						
						if($totalpages < $page) {
							$page = $totalpages;
						} else if($page < 1) {
							$page = 1;
						}

						$offset = ($page - 1) * $totalMails;

						if($totalpages == 0) {
							$offset = 0;
						}
						
						$int = $offset;
						
						if($page == 1) {
							$prev = 1;
						} else {
							$prev = $page-1;
						}
				
						if($page == $totalpages) {
							$next = $page;
						} else {
							$next = $page+1;
						}
						if($status == 1 || $status == 5){
							$query = $mysqli->query("SELECT * FROM `cype_mail` WHERE `from`='".$_SESSION['pname']."' AND `status`='".$status."' ORDER BY `mailid` DESC LIMIT ".$offset.", ".$totalMails."") or die();
						} else{
							$query = $mysqli->query("SELECT * FROM `cype_mail` WHERE `to`='".$_SESSION['pname']."' AND `status`='".$status."' ORDER BY `mailid` DESC LIMIT ".$offset.", ".$totalMails."") or die();
						}
						echo "<table class=\"table table-bordered table-striped table-hover\">
								<thead>
									<tr>
										<th>#</th>
										<th>Sender</th>
										<th>Title</th>
										<th>Date</th>
									</tr>
								</thead>
								<tbody>
									<tr>";
						while($fetch = $query->fetch_assoc()) {
							$int++;
							$dot = "";
							$title = substr($fetch['title'], 0, 20);
							if(strlen($fetch['title']) != strlen($title)) {
								$dot = "...";
							}
							$date = substr($fetch['dateadded'], -8, 8);
							echo "	<td><a href=\"?cype=ucp&amp;page=mail&amp;showmail=".$fetch['mailid']."\">".$int."</a></td>
									<td><a href=\"?cype=ucp&amp;page=mail&amp;showmail=".$fetch['mailid']."\">".$fetch['from']."</a></td>
									<td><a href=\"?cype=ucp&amp;page=mail&amp;showmail=".$fetch['mailid']."\">".$fetch['title']."</a></td>
									<td><a href=\"?cype=ucp&amp;page=mail&amp;showmail=".$fetch['mailid']."\">".$date."</a></td>";
						}
						echo "	</tr>
							</tbody>
						</table>";
						if($totalpages < 1 && tid != "") {
							echo "";
						} 
						elseif($tid == "" ) {
						?>
						<ul class="breadcrumb">
							<li><a href="?cype=ucp&amp;page=mail&amp;s=3">Unread: <?php mailStats(3)?></a><span class="divider">/</span></li>
							<li><a href="?cype=ucp&amp;page=mail&amp;s=2">Read: <?php mailStats(2)?></a><span class="divider">/</span></li>
							<li><a href="?cype=ucp&amp;page=mail&amp;s=1">Sent: <?php mailStats(1)?></a><span class="divider">/</span></li>
							<li><a href="?cype=ucp&amp;page=mail&amp;s=5">Drafts: <?php mailStats(5)?></a><span class="divider">/</span></li>											
						</ul>
					<?php
						}else {
					?>
					
							<br />
							<?php
								if($prev-1 > 0) {
									echo '<a href="?cype=ucp&amp;page=mail&amp;s='.$status.'&amp;p='.$prev.'"><img src="images/prev.gif" alt="Previous Mails" /></a>&nbsp;';
								}
								if($prev != $next) {
									echo '<a href="?cype=ucp&amp;page=mail&amp;s='.$status.'&amp;p='.$next.'"><img src="images/next.gif" alt="More Mails" /></a>';
								}
							?>
							<hr /><div align="right">
							<input type="submit" name="modify" value="Delete" class="textsubmit" /> | 
							<input type="submit" name="modify" value="Report" class="textsubmit" /> | 
							<input type="submit" name="modify" value="Junk" class="textsubmit" />
						</div><br />
					<?php } ?>
					</form>
					<?php
						if(isset($_POST['modify'])) {
							$name =  $mysqli->real_escape_string($_SESSION['pname']);
							$postid = $mysqli->real_escape_string(intval($_POST['action']));
							$getmail = $mysqli->query("SELECT * FROM `cype_mail` WHERE `mailid`='".$postid."'");
							$retrieve = $getmail->fetch_assoc();
							$postmethod = $retrieve['status'];
							switch ($postmethod) {
								case 1:
									$show = "from";
								break;
								default:
									$show = "to";
								break;
							}
							$found = $mysqli->query("SELECT * FROM `cype_mail` WHERE `".$show."`='".$name."'") or die();
							$ffound = $found->fetch_assoc();
							$message = "An Error Occured - Due To Lack Of Permission Or System Error.";
							$cfound = $found->num_rows;
							if($cfound >= 1) {
								if(empty($postid)) {
									$succ = "Please Select A Post ID You Wish To Modify.";
								} else {
									if($_POST['modify'] == "Delete") {
										$execute = $mysqli->query("DELETE FROM `cype_mail` WHERE `mailid` = '".$postid."'") or die();
										$message = "<div class=\"alert alert-success\">Message deleted.</div>";
									} else if($_POST['modify'] == "Reply") {
										$message = "<META http-equiv=\"refresh\" content=\"0;URL=?cype=ucp&page=mail&uc=".$ffound['from']."\">";
									} else if($_POST['modify'] == "Save") {
										$execute = $mysqli->query("UPDATE `cype_mail` SET `status` = '4' WHERE `mailid` = '".$postid."'") or die();
										$message = "<div class=\"alert alert-success\">Selected Message Has Been Saved!</div>";
									}
								}
							}
							echo "<br /><legend>Message Action Status</legend>";
							echo $message;
						} else if(isset($_GET['send']) && isset($_POST)) {
							$message = strip_tags($mysqli->real_escape_string($_POST['content']));
							$message = str_replace($censored, '*CYPE*', $message);	// Content Filtering
							$recipent = $mysqli->real_escape_string($_POST['recipent']);
							$title = $mysqli->real_escape_string($_POST['title']);
							$timestamp = time();
							$time = date("h:i A");
							$date = date("m/d/y");
							$fulltime = "".$time." &middot; ".$date."";
							$font = "red";
							$found = $mysqli->query("SELECT * FROM `cype_profile` WHERE `name`='".$recipent."'") or die();
							$grab = $found->fetch_assoc();
							if($_SESSION['pname'] == NULL) {
								$reply = "Please Set A Profile Name Before Sending Mails.";
							} else if($recipent == $_SESSION['pname']) {
								$reply = "Sending Mails To Yourself Is Just Sad.";
							$seconds = 60*$cypefloodint;
							} else if($cypeflood > 0 && (time() - $seconds) < $grab['dateadded']) {
								$reply = "You May Only Send A Mail Every 5 Minutes.";
							} else if(strlen($message) < 10 || strlen($message) > 400) {
								$reply = "Please Enter A Valid Content.";
							} else if($nfound = $found->num_rows < 1) {
								$reply = "Recipent Entered Is Invalid Or Is Not A Community Member, Yet.";
							} else if($title == "PM Title") {
								$reply = "Please Enter A Valid Title Of PM.";
							} else if(strlen($title) > 30 || strlen($title) < 5) {
								$reply = "Title entered is too short, or too long!";
							} else {
								$status = 3;
								if($_POST['send'] == "Draft") {
									$status = 5;
								}
								$query = $mysqli->query("INSERT INTO `cype_mail` (`to`,`from`,`status`,`title`,`content`,`ipaddress`,`timestamp`,`dateadded`) 
									VALUES ('".$recipent."','".$_SESSION['pname']."','".$status."','".$title."','".$message."','".$ipaddress."','".$timestamp."','".$fulltime."')") or die();
								if($status == 3){
									$status = 1;
									$query = $mysqli->query("INSERT INTO `cype_mail` (`to`,`from`,`status`,`title`,`content`,`ipaddress`,`timestamp`,`dateadded`)
										VALUES ('".$recipent."','".$_SESSION['pname']."','".$status."','".$title."','".$message."','".$ipaddress."','".$timestamp."','".$fulltime."')") or die();
								}
								$reply = "Message Successfully Sent To <b>'".$recipent."'</b>!";
								if($status == 5) {
									$reply = "Message For <b>'".$recipent."'</b> Successfully Drafted.";
								}
								$font = "blue";
							}
							echo "<br /><fieldset><legend>Private Message Status</legend><font color=\"".$font."\">";
							echo $reply;
							echo "</font></fieldset>";
						}
					}
					?>
				</fieldset>
			</td>
		</tr>
	</table>
<?php
} else {
	header('Location:?cype=ucp');
}
?>