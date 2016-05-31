<?php 
if(basename($_SERVER["PHP_SELF"]) == "bannedmaps.php") {
	die("403 - Access Forbidden");
}

echo "<h2 class=\"text-left\">Edit Banned Maps</h2><hr/>";
if(!isset($_POST['submit'])) {
	$seekmaps = $mysqli->query("SELECT jailmaps FROM ".$prefix."properties");
	$getmaps = $seekmaps->fetch_assoc();
	$mapid = explode("," , $getmaps['jailmaps']);
	$totalids = count($mapid);
	echo "
		Prevent users from warping out of jail from the User Control Panel.<br/><br/>
		<form role=\"form\" method=\"post\">
			<div class=\"form-group\">
				<div class=\"input_fields_wrap\">
	";
	for($i = 0; $i < $totalids; $i++) {
		$n = $i+1;
		echo "<div class=\"row\" style=\"margin-bottom:15px;\"><div class=\"col-md-10\"><input autocomplete=\"off\" class=\"form-control\" type=\"text\" placeholder=\"Jail Map ID\" type=\"text\" name=\"input_map[]\"  value=\"".$mapid[$i]."\"></div><div class=\"col-md-2\"><a href=\"#\" class=\"remove_field btn btn-danger\">Remove</a><br/></div></div>";
	}
	echo "	
		</div></div></div>
		<span class=\"input-group-btn\">
			<button id=\"b1\" class=\"btn btn-info add_field_button\" type=\"button\">Add another Map</button>
		</span>
		<hr/>
		<button class=\"btn btn-primary\" name=\"submit\">Submit &raquo;</button>
	";
} else {
	$input_map = $_POST['input_map'];
	$maps = array();
	$i = 1;
	foreach($input_map as $n) {
		$fieldval = $mysqli->real_escape_string(preg_replace("/[^A-Za-z0-9 ]/", '', $n));
		if(!is_numeric($fieldval)) {
			echo "<div class=\"alert alert-danger\">Please make sure to only use numbers! Check map entry #".$i.".</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
			exit();
		}
		else {
			$maps[] = $fieldval;
		}
		$i++;
	}

	$maplist = implode(",", $maps);
	$mysqli->query("UPDATE ".$prefix."properties SET jailmaps = '$maplist'");
	echo "<div class=\"alert alert-success\">Successfully updated jail maps</div>";
	redirect_wait5("?base=admin&page=bannedmaps");
}
?>
<script>
$(document).ready(function() {
    var max_fields	= 10;
    var wrapper	= $(".input_fields_wrap");
    var add_button	= $(".add_field_button");
    var x = 1;
    $(add_button).click(function(e) {
        e.preventDefault();
        if(x < max_fields) {
            x++;
            $(wrapper).append('<div class=\"row\" style=\"margin-bottom:15px;\"><div class="col-md-10"><input autocomplete="off" class="form-control" type="text" placeholder="Jail Map ID" type="text" name="input_map[]"/></div><div class="col-md-2"><a href="#" class="remove_field btn btn-danger">Remove</a></div></div>'); //add input box
        }
    });
    
    $(wrapper).on("click",".remove_field", function(e) {
        e.preventDefault(); $(this).parent('div').parent('div').remove(); x--;
    })
});
</script>