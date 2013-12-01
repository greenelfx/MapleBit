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

if(isset($_GET["do"])){
	$do = $_GET["do"];
}else {
	$do = "";
}

if($_SESSION['admin']){
	if($do == "submit"){
		$sservername = $mysqli->real_escape_string(stripslashes($_POST['servername']));
		$ssitetitle = $mysqli->real_escape_string(stripslashes($_POST['sitetitle']));
		$sclient = $mysqli->real_escape_string(stripslashes($_POST['client']));
		$sforumurl = $mysqli->real_escape_string(stripslashes($_POST['forumurl']));
		$ssiteurl = $mysqli->real_escape_string(stripslashes($_POST['siteurl']));
		$sexp = $mysqli->real_escape_string(stripslashes($_POST['exprate']));
		$smeso = $mysqli->real_escape_string(stripslashes($_POST['mesorate']));
		$sdrop = $mysqli->real_escape_string(stripslashes($_POST['droprate']));
		$smbanner = $mysqli->real_escape_string(stripslashes($_POST['mbanner']));
		$smblink = $mysqli->real_escape_string(stripslashes($_POST['mblink']));
		$sscroller = $mysqli->real_escape_string(stripslashes($_POST['scroller']));
		$scypedir = $mysqli->real_escape_string(stripslashes($_POST['cypedir']));
		$semail = $mysqli->real_escape_string(stripslashes($_POST['email']));
		$spcap = $mysqli->real_escape_string(stripslashes($_POST['pcap']));
		$floodp = $mysqli->real_escape_string(stripslashes($_POST['floodp']));
		$floodi = $mysqli->real_escape_string(stripslashes($_POST['floodi']));
		$accMax = $mysqli->real_escape_string(stripslashes($_POST['maxaccounts']));
		$sgmlevel = $mysqli->real_escape_string(stripslashes($_POST['gmlevel']));
		$sversion = $_POST['version'];
	
		$stop = "false";
		if(empty($sservername)){
			echo '<div class="alert alert-error">Your server doesn&apos;t have a name?</div>';
			$stop = "true";
			header("refresh: 1; url=?cype=admin&page=properties");
		}
		if($stop == "false"){
			if(empty($ssitetitle)){
				echo '<div class="alert alert-error">Your site doesn&apos;t have a name?</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=properties");
			}
		}
		if($stop == "false"){
			if(empty($sclient)){
				echo '<div class="alert alert-error">You need a client link.</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=properties");
			}
		}
		if($stop == "false"){
			if(empty($sforumurl)){
				echo '<div class="alert alert-error">You need to enter a forum URL. If you don&apos; have one, just put a &apos;#&apos; in the text box.</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=properties");
			}
		}
		if($stop == "false"){
			if(!is_numeric($floodp)) {
				echo '<div class="alert alert-error">Hacking Attempt Detected!</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=properties");
			} else if(!is_numeric($floodi)) {
				echo '<div class="alert alert-error">Invalid flood interval entered.</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=properties");
			}
		}
		if($stop == "false") {
			if(!is_numeric($accMax)) {
				echo '<div class="alert alert-error">Please enter a valid amount of accounts per IP.</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=properties");	
			}
		}
		if($stop == "false"){
			if(empty($ssiteurl)){
				echo '<div class="alert alert-error">You need to enter a site URL. If you are unsure, just put a &apos;/&apos; in the text box.</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=properties");
			}
		}
		if($stop == "false"){
			if(empty($sexp)){
				echo '<div class="alert alert-error">Enter an exp rate. Don&apos;t put an x in the text box!</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=properties");
			}
		}
		if($stop == "false"){
			if(empty($smeso)){
				echo '<div class="alert alert-error">Enter a meso rate. Don&apos;t put an x in the text box!</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=properties");
			}
		}
		if($stop == "false"){
			if(empty($sdrop)){
				echo '<div class="alert alert-error">Enter an drop rate. Don&apos;t put an x in the text box!</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=properties");
			}
		}
		if($stop == "false"){
			if(empty($smbanner)){
				echo '<div class="alert alert-error">Enter the link to your middle banner. If you are unsure, leave the text box as is.</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=properties");
			}
		}
		if($stop == "false"){
			if(empty($smblink)){
				echo '<div class="alert alert-error">Enter a link for the middle banner. If you are unsure, put &apos;#&apos; in the text box.</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=properties");
			}
		}
		if($stop == "false"){
			if(empty($sscroller)){
				echo '<div class="alert alert-error">Enter your desired scrolling message.</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=properties");
			}
		}
		
		if($stop == "false"){
			if(empty($scypedir)){
				echo '<div class="alert alert-error">Enter the Cype Directory. Default is /cype/</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=properties");
			}
		}
		if($stop == "false"){
			if(empty($semail)){
				echo '<div class="alert alert-error">Enter your email.</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=properties");
			}
		}
		
		if($stop == "false"){
			if(empty($spcap)){
				echo '<div class="alert alert-error">Enter a player cap.</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=properties");
			}
		}
		
		if($stop == "false"){
			if(empty($sgmlevel)){
				echo '<div class="alert alert-error">Enter a level for GMs.</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=properties");
			}
		}
		if($stop == "false"){
			$mquery = "UPDATE cype_properties SET name='$sservername', title='$ssitetitle', client='$sclient', version='$sversion', forumurl='$sforumurl', siteurl='$ssiteurl', exprate='$sexp', mesorate='$smeso', droprate='$sdrop', mbanner='$smbanner', mblink='$smblink', scroller='$sscroller', flood='$floodp', floodint='$floodi', cypedir='$scypedir', email='$semail', pcap='$spcap', maxaccounts='$accMax', gmlevel='$sgmlevel'";
			$exec = $mysqli->query($mquery);
			echo "<legend>Success</legend><div class=\"alert alert-success\">Configuration Updated</div>";
			header("refresh: 1; url=?cype=admin&page=properties");
		}
	}
	elseif($do == ""){
		include('assets/config/properties.php');
		if($version == "55"){
			$dversion = "
				<option value=\"55\" selected=\"selected\">v55</option>
				<option value=\"62\">v62</option>
				<option value=\"75\">v75</option>
				<option value=\"83\">v83</option>
				<option value=\"90\">v90</option>
				<option value=\"111\">v111</option>
				<option value=\"117\">v117</option>
				";
		}
		elseif($version == "62"){
			$dversion = "
				<option value=\"55\">v55</option>
				<option value=\"62\" selected=\"selected\">v62</option>
				<option value=\"75\">v75</option>
				<option value=\"83\">v83</option>
				<option value=\"90\">v90</option>
				<option value=\"111\">v111</option>
				<option value=\"117\">v117</option>
				";
		}
		elseif($version == "75"){
			$dversion = "
				<option value=\"55\">v55</option>
				<option value=\"62\">v62</option>
				<option value=\"75\" selected=\"selected\">v75</option>
				<option value=\"83\">v83</option>
				<option value=\"90\">v90</option>
				<option value=\"111\">v111</option>
				<option value=\"117\">v117</option>
				";
		}
		elseif($version == "83"){
			$dversion = "
				<option value=\"55\">v55</option>
				<option value=\"62\">v62</option>
				<option value=\"75\">v75</option>
				<option value=\"83\" selected=\"selected\">v83</option>
				<option value=\"90\">v90</option>
				<option value=\"111\">v111</option>
				<option value=\"117\">v117</option>
				";
		}
		elseif($version == "90"){
			$dversion = "
				<option value=\"55\">v55</option>
				<option value=\"62\">v62</option>
				<option value=\"75\">v75</option>
				<option value=\"83\">v83</option>
				<option value=\"90\" selected=\"selected\">v90</option>
				<option value=\"111\">v111</option>
				<option value=\"117\">v117</option>
				";
		}
		elseif($version == "111"){
			$dversion = "
				<option value=\"55\">v55</option>
				<option value=\"62\">v62</option>
				<option value=\"75\">v75</option>
				<option value=\"83\">v83</option>
				<option value=\"90\">v90</option>
				<option value=\"111\" selected=\"selected\">v111</option>
				<option value=\"117\">v117</option>
				";
		}
		elseif($version == "117"){
			$dversion = "
				<option value=\"55\">v55</option>
				<option value=\"62\">v62</option>
				<option value=\"75\">v75</option>
				<option value=\"83\">v83</option>
				<option value=\"90\">v90</option>
				<option value=\"111\">v111</option>
				<option value=\"117\" selected=\"selected\">v117</option>
				";
		}
		else{
			$dversion = "
				<option value=\"55\">v55</option>
				<option value=\"62\">v62</option>
				<option value=\"75\">v75</option>
				<option value=\"83\">v83</option>
				<option value=\"90\">v90</option>
				<option value=\"111\">v111</option>
				<option value=\"117\">v117</option>
				";
		}
		
		$flooddefault = "
			<option value=\"0\">Off</option>
			<option value=\"1\" selected>On</option>";

		if($cypeflood == 0) {
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
              <li class=\"active\"><a href=\"#mainconfig\" data-toggle=\"tab\">Main Configuration</a></li>
              <li><a href=\"#links\" data-toggle=\"tab\">Links</a></li>
              <li><a href=\"#info\" data-toggle=\"tab\">Game Info</a></li>
            </ul>
			
		<form method='post' action='?cype=admin&amp;page=properties&amp;do=submit'>
            <div id=\"myTabContent\" class=\"tab-content\">
<div class=\"tab-pane fade in active\" id=\"mainconfig\">
<br/>
	<div class=\"form-group\">
		<label for=\"serverName\">Server Name</label>
		<input name=\"servername\" type=\"text\" maxlength=\"100\" class='form-control' id=\"serverName\" value=\"".$servername."\" required/>
	</div>
	<div class=\"form-group\">
		<label for=\"maxAccounts\">Max Accounts</label> <small>(Per IP Address. 0 = Unlimited)</small>
		<input name=\"maxaccounts\" type=\"text\" maxlength=\"100\" class='form-control' id=\"maxAccounts\" value=\"".$servername."\" required/>
	</div>
	<div class=\"form-group\">
		<label for=\"gmLevel\">Level for GM Panel Access:</label>
		<input name=\"gmlevel\" type=\"text\" maxlength=\"100\" class='form-control' id=\"gmLevel\" value=\"".$gmlevel."\" required/>
	</div>		
</div>

<div class=\"tab-pane fade\" id=\"links\">
	<br/>
	<div class=\"form-group\">
		<label for=\"forumURL\">Forum URL:</label>
		<input name=\"forumurl\" type=\"text\" maxlength=\"100\" class='form-control' id=\"forumURL\" value=\"".$forumurl."\" required/>
	</div>	
	<div class=\"form-group\">
		<label for=\"clientLink\">Client Link:</label>
		<input name=\"client\" type=\"text\" maxlength=\"100\" class='form-control' id=\"clientLink\" value=\"".$client."\" required/>
	</div>
</div>

<div class=\"tab-pane fade\" id=\"info\">
	<br/>
	<div class=\"form-group\">
	<label for=\"serverVersion\">Server Version:</label>
		<select name=\"version\" class=\"form-control\" id=\"serverVersion\">
			".$dversion."
		</select>
	</div>
	<div class=\"form-group\">
		<label for=\"expRate\">Experience Rate:</label>
		<input name=\"exprate\" type=\"text\" maxlength=\"10\" class='form-control' id=\"expRate\" value=\"".$exprate."\" required/>
	</div>	
	<div class=\"form-group\">
		<label for=\"mesoRate\">Meso Rate:</label>
		<input name=\"mesorate\" type=\"text\" maxlength=\"10\" class='form-control' id=\"mesoRate\" value=\"".$mesorate."\" required/>
	</div>	
	<div class=\"form-group\">
		<label for=\"dropRate\">Drop Rate:</label>
		<input name=\"doprate\" type=\"text\" maxlength=\"10\" class='form-control' id=\"dropRate\" value=\"".$droprate."\" required/>
	</div>
	<div class=\"form-group\">
		<label for=\"playerCap\">Player Cap:</label>
		<input name=\"pcap\" type=\"text\" maxlength=\"20\" class='form-control' id=\"playerCap\" value=\"".$pcap."\" required/>
	</div>
	<div class=\"form-group\">
	<label for=\"floodPrevention\">Flood Prevention:</label>
		<select name=\"floodp\" class=\"form-control\" id=\"floodPrevention\">
				".$flooddefault."
		</select>
	</div>
	<div class=\"form-group\">
		<label for=\"postingInterval\">Posting Interval:</label> <small>Amount of time in seconds between comments</small>
		<input name=\"floodi\" type=\"text\" maxlength=\"10\" class='form-control' id=\"postingInterval\" value=\"".$cypefloodint."\" required/>
	</div>
</div>
</div>
<input type='submit' name='submit' value='Update &raquo;' class=\"btn btn-primary btn-large\"/>
</form>";
	}
}else{
	include('sources/public/accessdenied.php');
}
?>