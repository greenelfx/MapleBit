<?php
if(basename($_SERVER["PHP_SELF"]) == "members.php") {
	die("403 - Access Forbidden");
}

require_once 'assets/libs/HTMLPurifier.standalone.php';
$config = HTMLPurifier_Config::createDefault();
$config->set('HTML.Allowed', 'p, b, u, s, ol, li, ul, i, em, strong');
$purifier = new HTMLPurifier($config);

if(isset($_GET['name'])) {
	$name = $mysqli->real_escape_string($_GET['name']);
	$a = $mysqli->query("SELECT * FROM accounts INNER JOIN ".$prefix."profile ON accounts.id = ".$prefix."profile.accountid WHERE ".$prefix."profile.name = '".$name."'")->fetch_assoc();
	if(empty($a)) {
		// TODO: flash message
		redirect("?base=main&page=members");
	}

	if($a['loggedin'] == "0") {
		$status = "<span class=\"label label-danger\">Offline</span>";
	}
	else {
		$status = "<span class=\"label label-success\">Online</span>";
	}

	$gp = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `name`='".$name."'") or die();
	$p = $gp->fetch_assoc();
	$mc = $p['mainchar'];
	$gmc = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$mc."'") or die();
	$m = $gmc->fetch_assoc();

	$clean_html = $purifier->purify($p['text']);
	if(empty($p['realname'])) {
		$p['realname'] = "";
	}
	else {
		$p['realname'] = "(" . $p['realname'] . ")";
	}

	echo "<h2 class=\"text-left\" style=\"display:inline-block\"><img src=\"".get_gravatar($a['email'])."\" class=\"img-circle\">&nbsp;".$name . "&nbsp;" .htmlspecialchars($p['realname'], ENT_QUOTES, 'UTF-8') . "&nbsp;" .$status . "</h2><hr/>";
	if(!empty($m['name'])) {
		echo "<b>Main Character:</b> ".htmlspecialchars($m['name'], ENT_QUOTES, 'UTF-8'). "<br/>";
	}
	if(!empty($p['country'])) {
		echo "<b>Country: </b>".htmlspecialchars($p['country'], ENT_QUOTES, 'UTF-8')."<br/>";
	}
	if(!empty($p['motto'])) {
		echo "<b>Motto:</b> ".htmlspecialchars($p['motto'], ENT_QUOTES, 'UTF-8')."<br/>";
	}
	if(!empty($p['age'])) {
		echo "<b>Age:</b> ".htmlspecialchars($p['age'], ENT_QUOTES, 'UTF-8')."<br/>";
	}
	if(!empty($p['favjob'])) {
		echo "<b>Favorite Job: </b>".htmlspecialchars($p['favjob'], ENT_QUOTES, 'UTF-8')."<br/>";
	}
	if(!empty($p['text'])) {
		echo "
			<br/>
			<b>About Me:</b>
			<div class=\"breakword\">" . $clean_html."</div>
		";
	}
	if(isset($_SESSION['pname']) && $_GET['name'] == $_SESSION['pname']) {
		echo "<hr/><a href=\"?base=ucp&page=profedit\">Edit Profile &raquo;</a><hr/>";
	}
}
elseif(isset($_GET['action']) && $_GET['action'] == "search" && isset($_POST['search'])) {
	$name = $mysqli->real_escape_string($_POST['name']);
	$gs = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `name` LIKE '%".$name."%' ORDER BY `name` ASC") or die();
	echo "
		<h3 class=\"text-left\">Search Results:</h3><hr/>
		<div class=\"list-group\">
	";
	while($s = $gs->fetch_assoc()) {
		echo "<a href=\"?base=main&amp;page=members&amp;name=".$s['name']."\" class=\"list-group-item\">".$s['name']."</a>";
	}
	echo "</div>";
}
else {
	echo "
		<h2 class=\"text-left\">Members List</h2><hr/>
		Here's the full list of the members of the <b>".$servername."</b> community.
		You can select one to visit their profile or you can search for an user.<hr />
		<div class=\"row\">
		<div class=\"col-md-6 col-md-offset-6\">
			<form method=\"post\" action=\"?base=main&amp;page=members&amp;action=search\" role=\"form\">
			<div style=\"float:right;margin-bottom:0px;\">
				<div class=\"input-group\">
					<input type=\"text\" name=\"name\" placeholder=\"Profile Name\" required id=\"profileName\" class=\"form-control\"/>
						<span class=\"input-group-btn\">
							<input class=\"btn btn-primary\" name=\"search\" type=\"submit\"/>
						</span>
				</div>
			</div>
			</form>
		</div>
		</div><hr/>
	";
	$gp = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `name` != 'NULL' ORDER BY `name` ASC") or die();
	echo "<div class=\"list-group\">";
	while($p = $gp->fetch_assoc()) {
		echo "<a href=\"?base=main&amp;page=members&amp;name=".$p['name']."\" class=\"list-group-item\">".$p['name']."</a>";
	}
	echo "</div>";
}