<?php 
if(basename($_SERVER["PHP_SELF"]) == "properties.php"){
    die("403 - Access Forbidden");
}
if(isset($_GET["do"])){
	$do = $_GET["do"];
}else {
	$do = "";
}

if($_SESSION['admin']){
	if($servertype == 1) { $rebirths = "selected"; $levels = "";} else {$levels = "selected"; $rebirths = "";}
	if($do == "submit"){
		$sservername = $mysqli->real_escape_string(stripslashes($_POST['servername']));
		$sclient = $mysqli->real_escape_string(stripslashes($_POST['client']));
		$sserver = $mysqli->real_escape_string(stripslashes($_POST['server']));
		$sforumurl = $mysqli->real_escape_string(stripslashes($_POST['forumurl']));
		$ssiteurl = $mysqli->real_escape_string(stripslashes($_POST['siteurl']));
		$sexp = $mysqli->real_escape_string(stripslashes($_POST['exprate']));
		$smeso = $mysqli->real_escape_string(stripslashes($_POST['mesorate']));
		$sdrop = $mysqli->real_escape_string(stripslashes($_POST['droprate']));
		$spcap = $mysqli->real_escape_string(stripslashes($_POST['pcap']));
		$floodp = $mysqli->real_escape_string(stripslashes($_POST['floodp']));
		$floodi = $mysqli->real_escape_string(stripslashes($_POST['floodi']));
		$sgmlevel = $mysqli->real_escape_string(stripslashes($_POST['gmlevel']));
		$sversion = $mysqli->real_escape_string(stripslashes($_POST['version']));
		$sservertype = $mysqli->real_escape_string(stripslashes($_POST['servertype']));
		
		$stop = "false";
		if(empty($sservername)){
			echo '<div class="alert alert-danger">Your server doesn&apos;t have a name?</div>';
			$stop = "true";
			redirect_wait5("?base=admin&page=properties");
		}
		if($stop == "false"){
			if(empty($sclient)){
				echo '<div class="alert alert-danger">You need a client link.</div>';
				$stop = "true";
				redirect_wait5("?base=admin&page=properties");
			}
		}
		if($stop == "false"){
			if(empty($sserver)){
				echo '<div class="alert alert-danger">You need a setup link.</div>';
				$stop = "true";
				redirect_wait5("?base=admin&page=properties");
			}
		}
		if($stop == "false"){
			if(empty($sforumurl)){
				echo '<div class="alert alert-danger">You need to enter a forum URL. If you don&apos; have one, just put a &apos;#&apos; in the text box.</div>';
				$stop = "true";
				redirect_wait5("?base=admin&page=properties");
			}
		}
		if($stop == "false"){
			if(!is_numeric($floodp)) {
				echo '<div class="alert alert-danger">Hacking Attempt Detected!</div>';
				$stop = "true";
				redirect_wait5("?base=admin&page=properties");
			} else if(!is_numeric($floodi)) {
				echo '<div class="alert alert-danger">Invalid flood interval entered.</div>';
				$stop = "true";
				redirect_wait5("?base=admin&page=properties");
			}
		}
		if($stop == "false"){
			if(empty($ssiteurl)){
				echo '<div class="alert alert-danger">You need to enter a site URL. If you are unsure, just put a &apos;/&apos; in the text box.</div>';
				$stop = "true";
				redirect_wait5("?base=admin&page=properties");
			}
		}
		if($stop == "false"){
			if(empty($sexp)){
				echo '<div class="alert alert-danger">Enter an exp rate.</div>';
				$stop = "true";
				redirect_wait5("?base=admin&page=properties");
			}
		}
		if($stop == "false"){
			if(empty($smeso)){
				echo '<div class="alert alert-danger">Enter a meso rate.</div>';
				$stop = "true";
				redirect_wait5("?base=admin&page=properties");
			}
		}
		if($stop == "false"){
			if(empty($sdrop)){
				echo '<div class="alert alert-danger">Enter an drop rate.</div>';
				$stop = "true";
				redirect_wait5("?base=admin&page=properties");
			}
		}	
		if($stop == "false"){
			if(empty($spcap)){
				echo '<div class="alert alert-danger">Enter a player cap.</div>';
				$stop = "true";
				redirect_wait5("?base=admin&page=properties");
			}
		}
		
		if($stop == "false"){
			if(empty($sgmlevel)){
				echo '<div class="alert alert-danger">Enter a level for GMs.</div>';
				$stop = "true";
				redirect_wait5("?base=admin&page=properties");
			}
		}
		if($stop == "false"){
			$mquery = "UPDATE ".$prefix."properties SET name='$sservername', type = '$sservertype', client='$sclient', server = '$sserver', forumurl='$sforumurl', siteurl = '$ssiteurl', exprate='$sexp', mesorate='$smeso', droprate='$sdrop', version='$sversion', flood='$floodp', floodint='$floodi', pcap='$spcap', gmlevel='$sgmlevel'";
			$exec = $mysqli->query($mquery);
			echo "<h2 class=\"text-left\">Success</h2><hr/><div class=\"alert alert-success\">Configuration Updated</div>";
			redirect_wait5("?base=admin&page=properties");
		}
	}
	elseif($do == ""){
		include('assets/config/properties.php');
		$flooddefault = "
			<option value=\"0\">Off</option>
			<option value=\"1\" selected>On</option>";
		if($baseflood == 0) {
			$flooddefault = "
			<option value=\"0\" selected>Off</option>
			<option value=\"1\">On</option>";
		}
		$sadefault = "
			<option value=\"0\" selected>Yes</option>
			<option value=\"1\">No</option>";
		echo "
		<script>
$('#myTab a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
</script>
<h2 class=\"text-left\">Site Configuration</h2><hr/>
<ul id=\"myTab\" class=\"nav nav-tabs\">
	<li class=\"active\"><a href=\"#mainconfig\" data-toggle=\"tab\">Site</a></li>
	<li><a href=\"#links\" data-toggle=\"tab\">Links</a></li>
	<li><a href=\"#info\" data-toggle=\"tab\">Game Info</a></li>
	<li><a href=\"#comment\" data-toggle=\"tab\">Comments</a></li>
</ul>
<form method='post' action='?base=admin&amp;page=properties&amp;do=submit'>
<div id=\"myTabContent\" class=\"tab-content\">
<div class=\"tab-pane fade in active\" id=\"mainconfig\">
<br/>
	<div class=\"form-group\">
		<label for=\"serverName\">Server Name</label>
		<input name=\"servername\" type=\"text\" maxlength=\"100\" class='form-control' id=\"serverName\" value=\"".$servername."\" required/>
	</div>
	<div class=\"form-group\">
		<label for=\"gmLevel\">GM Level for Panel Access</label>
		<input name=\"gmlevel\" type=\"text\" maxlength=\"100\" class='form-control' id=\"gmLevel\" value=\"".$gmlevel."\" required/>
	</div>
	<div class=\"form-group\">
		<label for=\"siteURL\">Site Path <span class=\"label label-danger\">IMPORTANT. NEEDS TRAILING SLASH</span></label>
		<input name=\"siteurl\" type=\"text\" maxlength=\"100\" class='form-control' id=\"siteURL\" value=\"".$siteurl."\" required/>
		<span class=\"help-block\">/ indicates the root directory. /base/ indicates that base has been installed in a folder called base. You <b>must</b> use a trailing slash</span>			
	</div>	
</div>

<div class=\"tab-pane fade\" id=\"links\">
	<br/>
	<div class=\"form-group\">
		<label for=\"forumURL\">Forum URL</label>
		<input name=\"forumurl\" type=\"text\" maxlength=\"100\" class='form-control' id=\"forumURL\" value=\"".$forumurl."\" required/>
	</div>	
	<div class=\"form-group\">
		<label for=\"clientLink\">Client Link</label>
		<input name=\"client\" type=\"text\" maxlength=\"100\" class='form-control' id=\"clientLink\" value=\"".$client."\" required/>
	</div>
	<div class=\"form-group\">
		<label for=\"setupLink\">Setup Link</label>
		<input name=\"server\" type=\"text\" maxlength=\"100\" class='form-control' id=\"setupLink\" value=\"".$server."\" required/>
	</div>
</div>

<div class=\"tab-pane fade\" id=\"info\">
	<br/>
	<div class=\"form-group\">
	<label for=\"serverVersion\">Server Version</label>
		<input name=\"version\" type=\"text\" maxlength=\"6\" class='form-control' id=\"serverVersion\" value=\"".$version."\" required/>
	</div>
	<div class=\"form-group\">
		<label for=\"serverType\">Server Type</label>
			<select name=\"servertype\" class=\"form-control\">
				<option value=\"1\" ".$rebirths.">Rebirth</option>
				<option value=\"0\" ".$levels.">Level</option>
			</select>
	</div>
	<div class=\"form-group\">
		<label for=\"expRate\">Experience Rate</label>
		<input name=\"exprate\" type=\"text\" maxlength=\"10\" class='form-control' id=\"expRate\" value=\"".$exprate."\" required/>
	</div>	
	<div class=\"form-group\">
		<label for=\"mesoRate\">Meso Rate</label>
		<input name=\"mesorate\" type=\"text\" maxlength=\"10\" class='form-control' id=\"mesoRate\" value=\"".$mesorate."\" required/>
	</div>	
	<div class=\"form-group\">
		<label for=\"dropRate\">Drop Rate</label>
		<input name=\"droprate\" type=\"text\" maxlength=\"10\" class='form-control' id=\"dropRate\" value=\"".$droprate."\" required/>
	</div>
	<div class=\"form-group\">
		<label for=\"playerCap\">Player Cap</label>
		<input name=\"pcap\" type=\"text\" maxlength=\"20\" class='form-control' id=\"playerCap\" value=\"".$pcap."\" required/>
	</div>
</div>
<div class=\"tab-pane fade\" id=\"comment\">
	<br/>
	<div class=\"form-group\">
	<label for=\"floodPrevention\">Comment Flood Prevention</label> <small>Forces users to wait an interval inbetween comments.</small>
		<select name=\"floodp\" class=\"form-control\" id=\"floodPrevention\">
				".$flooddefault."
		</select>
	</div>
	<div class=\"form-group\">
		<label for=\"postingInterval\">Posting Interval</label> <small>Amount of time in <b>minutes</b> between comments</small>
		<input name=\"floodi\" type=\"text\" maxlength=\"10\" class='form-control' id=\"postingInterval\" value=\"".$basefloodint."\" required/>
	</div>
</div>
</div>
<input type='submit' name='submit' value='Update &raquo;' class=\"btn btn-primary btn-large\"/>
</form>";
	}
}else{
	redirect("?base");
}
?>