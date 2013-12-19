<?php 
if(isset($_GET['name'])){$name = sql_sanitize($_GET['name']);}
$check = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".getInfo('accid', $name, 'profilename')."'") or die();
$real = $check->num_rows;;
if($real < 1){
	if($_SESSION){
		$name = $_SESSION['name'];
	} else{
		$name = "";
		echo "<meta http-equiv=refresh content=\"0; url=?cype=main\">";
		exit();
	}
}

if(@$_GET['name']){
	// Display profile
	if(@$_GET['p']==""){
		$ga = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".getInfo('accid', $name, 'profilename')."'") or die();
		$a = $ga->fetch_assoc();
		if($a['loggedin'] == "0"){
			$status = "<img src='assets/img/offline.png' alt='Offline' />";
		}else{
			$status = "<img src='assets/img/online.png' alt='Offline' />";
		}
		$gp = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `name`='".$name."'") or die();
		$p = $gp->fetch_assoc();
		$mc = $p['mainchar'];
		$gmc = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$mc."'") or die();
		$m = $gmc->fetch_assoc();
		if($m['name'] == "") {
			$m['name'] = "Not set";
			$stats = "";
		}
		else {$stats = "View Stats";}
		if(empty($p['realname'])){$p['realname'] = "Not Set";}
		if(empty($p['country'])){$p['country'] = "Not Set";}
		if(empty($p['motto'])){$p['motto'] = "Not Set";}
		if(empty($p['age'])){$p['age'] = "Not Set";}
		if(empty($p['favjob'])){$p['favjob'] = "Not Set";}
		if(empty($p['text'])){$p['text'] = "Not Set";}
		echo "
			<legend>".$name."'s Profile (".$p['realname'].")</legend>
			Game :".$status."<br/>
			Site :".onlineCheck(getInfo('accid', $name, 'profilename'))."<br/><br/>
			<b>Main Character:</b> ".$m['name']. "&nbsp;" .$stats ."<br/><br/>
			<b>Motto:</b> ".$p['motto']."<br/><br/>
			<b>Age:</b> ".$p['age']."<br/><br/>
			<b>Country: </b>".$p['country']."<br/><br/>
			<b>Favorite Job: </b>".$p['favjob']."<br/><hr/>
			";
		echo "	
			<b>About Me:</b>
			".nl2br(stripslashes($p['text']))."<br/>
			<hr/>
			<a href=\"?cype=ucp&amp;page=mail&amp;uc=$name\">Send me Mail!</a>
			";
}
}elseif(@$_GET['action']=="search"){
	if($_POST['search']){
		$name = $mysqli->real_escape_string($_POST['name']);
		$gs = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `name` LIKE '%".$name."%' ORDER BY `name` ASC") or die();
		echo "
		<legend>
			<b>Search result:</b>
		</legend>";
		while($s = $gs->fetch_assoc()){
			echo "
			<a href=\"?cype=main&amp;page=members&amp;name=".$s['name']."\">".$s['name']."</a><br />";
		}
	}
}else{
	echo "
		<legend>
			<b>Members List</b>
		</legend>";
	echo "
		Here's the full list of the members of the <b>".$servername."</b> community. 
		You can select one to visit their profile or you can search for an user.<hr />
		<div class=\"row\">
		<div class=\"col-md-6 col-md-offset-6\">
			<form method=\"post\" action=\"?cype=main&amp;page=members&amp;action=search\" role=\"form\">
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
			<th style=\"width:50px\">Status</th>
			<th>Name</th>
		</tr>
	</thead>
	<tbody>";
	while($p = $gp->fetch_assoc()){
		echo "
			<tr>
				<td>".onlineCheck($p['accountid'])."</td>
				<td>
					<a href=\"?cype=main&amp;page=members&amp;name=".$p['name']."\">".$p['name']."</a>
				</td>
			</tr>";
	}
	echo "
	</tbody>
</table>
	";
}
?>