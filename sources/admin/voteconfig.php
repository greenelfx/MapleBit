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
		$svlink = $mysqli->real_escape_string(stripslashes($_POST['votelink']));
		$snx = $mysqli->real_escape_string(stripslashes($_POST['nx']));
		$scolnx = $mysqli->real_escape_string(stripslashes($_POST['nxcolumn']));
		$svp = $mysqli->real_escape_string(stripslashes($_POST['vp']));
		$scolvp = $mysqli->real_escape_string(stripslashes($_POST['vpcolumn']));
		$svtime = $mysqli->real_escape_string(stripslashes($_POST['wait'])) * 3600;
	
		$stop = "false";
		if(empty($svlink)){
			echo '<div class="alert alert-danger">Please enter your vote link!</div>';
			$stop = "true";
			header("refresh: 1; url=?cype=admin&page=voteconfig");
		}
		if($stop == "false"){
			if(empty($snx)){
				echo '<div class="alert alert-danger">Please enter the amount of NX per vote!</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=voteconfig");
			}
		}
		if($stop == "false"){
			if(empty($scolnx)){
				echo '<div class="alert alert-danger">Please enter the NX column!</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=voteconfig");
			}
		}
		if($stop == "false"){
			if(empty($svp)){
				echo '<div class="alert alert-danger">Please enter the amount of VP per vote!</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=voteconfig");
			}
		}
		if($stop == "false"){
			if(empty($scolvp)){
				echo '<div class="alert alert-danger">Please enter the VP column!</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=voteconfig");
			}
		}
		if($stop == "false"){
			if(empty($svtime)){
				echo '<div class="alert alert-danger">Please enter the amount of time players must wait before voting again!</div>';
				$stop = "true";
				header("refresh: 1; url=?cype=admin&page=voteconfig");
			}
		}
		if($stop == "false"){
			$vquery = "UPDATE cype_properties SET vlink='$svlink', gnx='$snx', gvp='$svp', colnx='$scolnx', colvp='$scolvp', vtime='$svtime'";
			$exec = $mysqli->query($vquery);
			echo "<legend>Success</legend><div class=\"alert alert-success\">Vote Configuration Updated</div>";
			header("refresh: 1; url=?cype=admin&page=voteconfig");
		}
	}
	elseif($do == ""){
		echo "
<legend>Vote Configuration</legend>
<form method='post' action='?cype=admin&amp;page=voteconfig&amp;do=submit'>
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
</form>
<br/>";
	}
}else{
	include('sources/public/accessdenied.php');
}
?>