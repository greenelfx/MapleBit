<?php
if(basename($_SERVER["PHP_SELF"]) == "members.php"){
    die("403 - Access Forbidden");
}

if(isset($_GET['name'])) {
	$name = $mysqli->real_escape_string($_GET['name']);
	$check = $mysqli->query("SELECT COUNT(*) FROM `accounts` WHERE `id`='".getInfo('accid', $name, 'profilename')."'")->fetch_row();
	if($check[0] != 1){ // If the requested user doesn't exist, use the current user
		if($_SESSION){
			$name = $_SESSION['name'];
		} else { // Else redirect to the homepage
			echo "<meta http-equiv=refresh content=\"0; url=?base=main\">";
			exit();
		}
	}

	$ga = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".getInfo('accid', $name, 'profilename')."'") or die();
	$a = $ga->fetch_assoc();
	if($a['loggedin'] == "0") {
		$status = "<span class=\"label label-danger\">Offline</span>";
	} else{
		$status = "<span class=\"label label-success\">Online</span>";
	}
	$gp = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `name`='".$name."'") or die();
	$p = $gp->fetch_assoc();
	$mc = $p['mainchar'];
	$gmc = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$mc."'") or die();
	$m = $gmc->fetch_assoc();

	require_once 'assets/libs/HTMLPurifier.standalone.php';
	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.Allowed', 'p, b, u, s, ol, li, ul, i, em, strong');
	$purifier = new HTMLPurifier($config);
	$clean_html = $purifier->purify($p['text']);
	if(empty($p['realname'])){
		$p['realname'] = "";
	} else {
		$p['realname'] = "(" . $p['realname'] . ")";
	}
	echo "<h2 class=\"text-left\"><img src=\"".get_gravatar($a['email'])."\" class=\"img-circle\">&nbsp;".$name." ".htmlspecialchars($p['realname'], ENT_QUOTES, 'UTF-8')."</h2>
	<hr/>
	".$status." in game and ".onlineCheck(getInfo('accid', $name, 'profilename'))." on the site
	<hr/>";
	if(!$m['name'] == "") {
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
		echo "<b>Favorite Job: </b>".htmlspecialchars($p['favjob'], ENT_QUOTES, 'UTF-8')."<br/><br/>";
	}
	if(!empty($p['text'])) {
		echo "
			<b>About Me:</b>
			<div class=\"breakword\">" . $clean_html."</div>";
	}
	if(isset($_SESSION['pname']) && $_GET['name'] == $_SESSION['pname']) {
		echo "<hr/><a href=\"?base=ucp&page=profedit\">Edit Profile &raquo;</a><hr/>";
	}
} elseif(isset($_GET['action']) && $_GET['action'] == "search") {
	if($_POST['search']) {
		$name = $mysqli->real_escape_string($_POST['name']);
		$gs = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `name` LIKE '%".$name."%' ORDER BY `name` ASC") or die();
		echo "
		<legend>
			<b>Search result:</b>
		</legend>";
		while($s = $gs->fetch_assoc()){
			echo "<a href=\"?base=main&amp;page=members&amp;name=".$s['name']."\">".$s['name']."</a><br />";
		}
	}
} else {
	echo "
		<h2 class=\"text-left\">Members List</h2><hr/>";
	echo "
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
	echo "
		<table class=\"table table-bordered\">
	<thead>
		<tr>
			<th style=\"width:100px\">Site Status</th>
			<th>Name</th>
		</tr>
	</thead>
	<tbody>";
	while($p = $gp->fetch_assoc()){
		echo "
			<tr>
				<td>".onlineCheck($p['accountid'])."</td>
				<td>
					<a href=\"?base=main&amp;page=members&amp;name=".$p['name']."\">".$p['name']."</a>
				</td>
			</tr>";
	}
	echo "
		</tbody>
	</table>
	";
}
?>