<?php 
if($_SESSION['admin']){
	if(!isset($_POST['submit'])) {
		echo "<h2 class=\"text-left\">Vote Configuration</h2><hr/>
		<form method='post'>
			<div class=\"form-group\">
				<label for=\"voteLink\">Vote Link</label>
				<input name=\"votelink\" type=\"text\" maxlength=\"100\" class='form-control' id=\"voteLink\" value=\"".$vlink."\" placeholder=\"http://www.gtop100.com/maplestory\" required/>
			</div>
			<hr/>
			<div class=\"form-group\">
				<label for=\"nxGiven\">NX</label> <small>(Amount of NX given per vote)</small>
				<input name=\"nx\" type=\"text\" maxlength=\"100\" class='form-control' id=\"nxGiven\" value=\"".$gnx."\" placeholder=\"10\" required/>
			</div>
			<div class=\"form-group\">
				<label for=\"nxColumn\">NX Column</label> <small>(Where is NX stored in your database? [Usually nxPrepaid])</small>
				<input name=\"nxcolumn\" type=\"text\" maxlength=\"100\" class='form-control' id=\"nxColumn\" value=\"".$colnx."\" placeholder=\"nxPrepaid\" required/>
			</div>
			<hr/>
			<div class=\"form-group\">
				<label for=\"vpGiven\">Vote Points</label> <small>(Amount of Vote Points given per vote)</small>
				<input name=\"vp\" type=\"text\" maxlength=\"100\" class='form-control' id=\"vpGiven\" value=\"".$gvp."\" placeholder=\"1\" required/>
			</div>
			<div class=\"form-group\">
				<label for=\"vpColumn\">Vote Column</label> <small>(Where are Vote Points stored in your database? [Usually votepoints])</small>
				<input name=\"vpcolumn\" type=\"text\" maxlength=\"100\" class='form-control' id=\"vpColumn\" value=\"".$colvp."\" placeholder=\"votepoints\" required/>
			</div>
			<hr/>
			<div class=\"form-group\">
				<label for=\"waitTime\">Waiting Time (In Hours)</label> <small>(How long do players have to wait before voting again? [Usually 6 hours])</small>
				<input name=\"wait\" type=\"text\" maxlength=\"10\" class='form-control' id=\"waitTime\" value=\"".($vtime/3600)."\" placeholder=\"6\" required/>
			</div>			
		<input type='submit' name='submit' value='Submit &raquo;' class=\"btn btn-primary btn-large\"/>
		</form>";
	}
	else {
		$svlink = $mysqli->real_escape_string(stripslashes($_POST['votelink']));
		$snx = $mysqli->real_escape_string(stripslashes($_POST['nx']));
		$scolnx = $mysqli->real_escape_string(stripslashes($_POST['nxcolumn']));
		$svp = $mysqli->real_escape_string(stripslashes($_POST['vp']));
		$scolvp = $mysqli->real_escape_string(stripslashes($_POST['vpcolumn']));
		$svtime = $mysqli->real_escape_string(stripslashes($_POST['wait'])) * 3600;
		if(empty($vlink)){echo "<div class=\"alert alert-danger\">Please enter a vote link.</div>";}
		elseif(empty($scolnx)){echo "<div class=\"alert alert-danger\">Please enter a NX column.</div>";}
		elseif(empty($svp)){echo "<div class=\"alert alert-danger\">Please enter a Vote Point amount.</div>";}
		elseif(empty($scolvp)){echo "<div class=\"alert alert-danger\">Please enter a Vote Point amount.</div>";}
		elseif(empty($svtime)){echo "<div class=\"alert alert-danger\">Please enter a waiting time.</div>";}
		else {
			$mysqli->query("UPDATE ".$prefix."properties SET vlink='$svlink', gnx='$snx', gvp='$svp', colnx='$scolnx', colvp='$scolvp', vtime='$svtime'");
			echo "<div class=\"alert alert-success\">Successfully updated vote configuration.</div><hr/><a href=\"?base=admin\" class=\"btn btn-primary\">&laquo; Go Back</a>";
		}
	}
} else {
	redirect ("?base");
}
?>