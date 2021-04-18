<?php

if (basename($_SERVER['PHP_SELF']) == 'properties.php') {
    exit('403 - Access Forbidden');
}

if (isset($_POST['submit'])) {
    $sservername = $mysqli->real_escape_string(stripslashes($_POST['servername']));
    $sclient = $mysqli->real_escape_string(stripslashes($_POST['client']));
    $sserver = $mysqli->real_escape_string(stripslashes($_POST['server']));
    $sforumurl = $mysqli->real_escape_string(stripslashes($_POST['forumurl']));
    $ssiteurl = $mysqli->real_escape_string(stripslashes($_POST['siteurl']));
    $sexp = $mysqli->real_escape_string(stripslashes($_POST['exprate']));
    $smeso = $mysqli->real_escape_string(stripslashes($_POST['mesorate']));
    $sdrop = $mysqli->real_escape_string(stripslashes($_POST['droprate']));
    $floodp = $mysqli->real_escape_string(stripslashes($_POST['floodp']));
    $floodi = $mysqli->real_escape_string(stripslashes($_POST['floodi']));
    $sgmlevel = $mysqli->real_escape_string(stripslashes($_POST['gmlevel']));
    $sversion = $mysqli->real_escape_string(stripslashes($_POST['version']));
    $sservertype = $mysqli->real_escape_string(stripslashes($_POST['servertype']));
    $srecaptchapublic = $mysqli->real_escape_string(stripslashes($_POST['recaptcha-public'])); //can be null
    $srecaptchaprivate = $mysqli->real_escape_string(stripslashes($_POST['recaptcha-private'])); //can be null
    $shashalgorithm = $mysqli->real_escape_string(stripslashes($_POST['hash-algorithm']));
    $continue = true;

    if (empty($sservername)) {
        echo '<div class="alert alert-danger">Your server doesn&apos;t have a name?</div>';
        $continue = false;
    } elseif (empty($sclient)) {
        echo '<div class="alert alert-danger">You need a client link.</div>';
        $continue = false;
    } elseif (empty($sserver)) {
        echo '<div class="alert alert-danger">You need a setup link.</div>';
        $continue = false;
    } elseif (empty($sforumurl)) {
        echo '<div class="alert alert-danger">You need to enter a forum URL. If you don&apos; have one, just put a &apos;#&apos; in the text box.</div>';
        $continue = false;
    } elseif ($floodp != 1 && $floodp != 0) {
        $floodp = 1;
    } elseif (!is_numeric($floodi)) {
        echo '<div class="alert alert-danger">Invalid flood interval entered.</div>';
        $continue = false;
    } elseif (empty($ssiteurl)) {
        echo '<div class="alert alert-danger">You need to enter a site URL. If you are unsure, just put a &apos;/&apos; in the text box.</div>';
        $continue = false;
    } elseif (empty($sexp)) {
        echo '<div class="alert alert-danger">Enter an exp rate.</div>';
        $continue = false;
    } elseif (empty($smeso)) {
        echo '<div class="alert alert-danger">Enter a meso rate.</div>';
        $continue = false;
    } elseif (empty($sdrop)) {
        echo '<div class="alert alert-danger">Enter an drop rate.</div>';
        $continue = false;
    } elseif (empty($sgmlevel)) {
        echo '<div class="alert alert-danger">Enter a level for GMs.</div>';
        $continue = false;
    } elseif (!is_numeric($sversion) || ($sversion < 0 || $sversion > 200)) {
        echo '<div class="alert alert-danger">Enter a valid server version.</div>';
        $continue = false;
    }

    if (!$continue) {
        echo '<hr/><button onclick="goBack()" class="btn btn-primary">&laquo; Go Back</button>';
    } else {
        $mquery = 'UPDATE '.$prefix."properties SET name='$sservername', type = '$sservertype', client='$sclient', server = '$sserver', forumurl='$sforumurl', siteurl = '$ssiteurl', exprate='$sexp', mesorate='$smeso', droprate='$sdrop', version='$sversion', flood='$floodp', floodint='$floodi', gmlevel='$sgmlevel', recaptcha_public='$srecaptchapublic', recaptcha_private='$srecaptchaprivate', hash_algorithm='$shashalgorithm'";
        $exec = $mysqli->query($mquery);
        echo '<h2 class="text-left">Success</h2><hr/><div class="alert alert-success">Configuration Updated</div>';
        redirect_wait5('?base=admin&page=properties');
    }
} else {
    if ($servertype == 1) {
        $rebirths = 'selected';
        $levels = '';
    } else {
        $levels = 'selected';
        $rebirths = '';
    }
    $flooddefault = '
		<option value="0">Off</option>
		<option value="1" selected>On</option>';
    if ($baseflood == 0) {
        $flooddefault = '
		<option value="0" selected>Off</option>
		<option value="1">On</option>';
    }
    $sadefault = '
		<option value="0" selected>Yes</option>
		<option value="1">No</option>';
    echo "
		<script>
			$('#myTab a').click(function (e) {
  				e.preventDefault();
  				$(this).tab('show');
			})
		</script>
		<h2 class=\"text-left\">Site Configuration</h2><hr/>
		<ul class=\"nav nav-tabs\" id=\"configTabs\" role=\"tablist\">
			<li class=\"nav-item\"><a class=\"nav-link active\" href=\"#mainconfig\" data-toggle=\"tab\">Site</a></li>
			<li class=\"nav-item\"><a class=\"nav-link\" href=\"#links\" data-toggle=\"tab\">Links</a></li>
			<li class=\"nav-item\"><a class=\"nav-link\" href=\"#info\" data-toggle=\"tab\">Game Info</a></li>
			<li class=\"nav-item\"><a class=\"nav-link\" href=\"#comment\" data-toggle=\"tab\">Comments</a></li>
			<li class=\"nav-item\"><a class=\"nav-link\" href=\"#recaptcha\" data-toggle=\"tab\">reCAPTCHA v3</a></li>
		</ul>
		<form method='post' action='?base=admin&amp;page=properties'>
		<div id=\"myTabContent\" class=\"tab-content\">
		<div class=\"tab-pane fade show active\" id=\"mainconfig\">
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
				<label for=\"siteURL\">Site Path <span class=\"badge badge-danger\">IMPORTANT. NEEDS TRAILING SLASH</span></label>
				<input name=\"siteurl\" type=\"text\" maxlength=\"100\" class='form-control' id=\"siteURL\" value=\"".$siteurl."\" required/>
				<small id=\"siteUrlHelpBlock\" class=\"form-text text-muted\">/ indicates the root directory. /base/ indicates that base has been installed in a folder called base. You <b>must</b> use a trailing slash</small>			
			</div>
			<div class=\"form-group\">
				<label for=\"hash-algorithm\">Password Hashing Algorithm</label>
				<select name=\"hash-algorithm\" class='form-control'>
					<option value=\"bcrypt\" ".($hash_algorithm === 'bcrypt' ? 'selected' : '').'>bcrypt</option>
					<option value="sha1" '.($hash_algorithm === 'sha1' ? 'selected' : '').'>SHA1</option>
					<option value="sha512" '.($hash_algorithm === 'sha512' ? 'selected' : '').">SHA512</option>
				</select>
				<small id=\"hashingAlgorithmHelpBlock\" class=\"form-text text-muted\">The algorithm to be used to hash passwords for login and registration</small>			
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
				<input name=\"version\" type=\"text\" maxlength=\"6\" class='form-control' id=\"serverVersion\" value=\"".$version.'" required/>
			</div>
			<div class="form-group">
				<label for="serverType">Server Type</label>
					<select name="servertype" class="form-control">
						<option value="1" '.$rebirths.'>Rebirth</option>
						<option value="0" '.$levels.">Level</option>
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
				<input name=\"droprate\" type=\"text\" maxlength=\"10\" class='form-control' id=\"dropRate\" value=\"".$droprate.'" required/>
			</div>
		</div>
		<div class="tab-pane fade" id="comment">
			<br/>
			<div class="form-group">
			<label for="floodPrevention">Comment Flood Prevention</label> <small>Forces users to wait an interval inbetween comments.</small>
				<select name="floodp" class="form-control" id="floodPrevention">
					'.$flooddefault."
				</select>
			</div>
			<div class=\"form-group\">
				<label for=\"postingInterval\">Posting Interval</label> <small>Amount of time in <b>minutes</b> between comments</small>
				<input name=\"floodi\" type=\"text\" maxlength=\"10\" class='form-control' id=\"postingInterval\" value=\"".$basefloodint."\" required/>
			</div>
		</div>
		<div class=\"tab-pane fade\" id=\"recaptcha\">
			<br/>
				<div class=\"form-group\">
					<label for=\"recaptcha-public\">Site Key</label>
					<input name=\"recaptcha-public\" type=\"text\" maxlength=\"100\" class='form-control' id=\"recaptcha-public\" value=\"".$recaptcha_public."\" required/>
				</div>	
				<div class=\"form-group\">
					<label for=\"recaptcha-private\">Secret Key</label>
					<input name=\"recaptcha-private\" type=\"text\" maxlength=\"100\" class='form-control' id=\"recaptcha-private\" value=\"".$recaptcha_private.'" required/>
				</div>
				<div class="card">
					<div class="card-header">reCAPTCHA Guide</div>
						<div class="card-body">
							<ol>
								<li>Visit <a href="https://www.google.com/recaptcha" target="_blank">this link</a> and go to the admin panel.</li>
								<li>Create a website <a href="https://i.imgur.com/3mziuti.png">(guide)</a></li>
								<li>Register the site as <b>reCaptcha v3</b>. Input your domains, this will probably be <b>'.$_SERVER['SERVER_NAME']."</b> <a href=\"https://i.imgur.com/CbxluXb.jpg\">(guide)</a>. If your domain changes, you will need to update this in the Google Admin panel.</li>
								<li>Add the \"Site key\" and the \"Secret key\" given to you by Google into this form.</li>
								<li>Press the <b>Update</b> button below.</li>
							</ol>
						</div>
			  		</div>
				</div>
				<br/>
		</div>
		<input type='submit' name='submit' value='Update &raquo;' class=\"btn btn-primary btn-large\"/>
		</form>
	";
}
