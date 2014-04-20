<?php 
if(basename($_SERVER["PHP_SELF"]) == "bannedmaps.php"){
    die("403 - Access Forbidden");
}
if($_SESSION['admin']){
	echo "<h2 class=\"text-left\">Edit Banned Maps</h2>
	<hr/>";
	if(!isset($_POST['submit'])) {
	$seekmaps = $mysqli->query("SELECT jailmaps FROM ".$prefix."properties");
	$getmaps = $seekmaps->fetch_assoc();
	$mapid = explode("," , $getmaps['jailmaps']);
	$totalids = count($mapid);
	echo "
		Prevent users from warping out of jail from the User Control Panel.<br/><br/>
		<form role=\"form\" method=\"post\">
			<div class=\"form-group\">
				<input type=\"hidden\" name=\"count\" value=\"".$totalids."\" id=\"count\"/>";
				for($i = 0; $i <= $totalids-1; $i++) {
					$n = $i+1;
					echo "<input autocomplete=\"off\" class=\"form-control\" id=\"field".$n."\" name=\"field".$n."\" type=\"text\" placeholder=\"Jail Map ID\" value=\"".$mapid[$i]."\"><br/>";
				}
		echo "	
			</div>
			<span class=\"input-group-btn\">
				<button id=\"b1\" class=\"btn btn-info add\" type=\"button\">Add another Map</button>
			</span>
			<hr/>
			<button class=\"btn btn-primary\" name=\"submit\">Submit &raquo;</button>
		<script>
			$(document).ready(function(){
			var next = ".$totalids.";
			$(\".add\").click(function(e){
				e.preventDefault();
				var addto = \"#field\" + next;
				next = next + 1;
				var newIn = '<br/><input autocomplete=\"off\" class=\"form-control\" id=\"field' + next + '\" name=\"field' + next + '\" type=\"text\" placeholder=\"Jail Map ID\">';
				var newInput = $(newIn);
				$(addto).after(newInput);
				$(\"#field\" + next).attr('data-source',$(addto).attr('data-source'));
				$(\"#count\").val(next);  
			});
		});
		</script>
		";
	} else {
		$fields = $mysqli->real_escape_string(preg_replace("/[^A-Za-z0-9 ]/", '', $_POST['count'])); # Get # of fields
		for($i = 1; $i <= $fields; $i++) {
			$fieldval = $mysqli->real_escape_string(preg_replace("/[^A-Za-z0-9 ]/", '', $_POST['field'.$i.''])); # Get field value
			if(!is_numeric($fieldval)) {
				echo "<div class=\"alert alert-danger\">Please make sure to only use numbers! Check map entry #".$i.".</div>";
				$pass = false;
			} else {
				$maps[] = $fieldval;
				$pass = true;
			}
		}
		if($pass) {
			$maplist = implode(",", $maps);
			$mysqli->query("UPDATE ".$prefix."properties SET jailmaps = '$maplist'");
			echo "<div class=\"alert alert-success\">Successfully updated jail maps</div><hr/><a href=\"?base=admin\" class=\"btn btn-primary\">&laquo; Admin Panel</a>";
		} else {
			echo "<hr/><a href=\"?base=admin&page=bannedmaps\" class=\"btn btn-primary\">&laquo; Go Back</a>";
		}
	}
} else{
	redirect("?base");
}
?>