<?php 
if(basename($_SERVER["PHP_SELF"]) == "theme.php") {
	die("403 - Access Forbidden");
}

echo "<h2 class=\"text-left\">Configure Theme</h2><hr/>";
$themes = array('bootstrap', 'cerulean', 'cosmo', 'cyborg', 'darkly', 'flatly', 'journal', 'lumen', 'paper', 'readable', 'sandstone', 'slate', 'simplex', 'spacelab', 'superhero', 'united', 'yeti');
if(!isset($_POST['apply'])) {
	echo "
		Please click one of the options below to preview the theme. Once you are happy with your theme, click the \"Apply Theme\" button below.
		<hr/>
		<form name=\"applytheme\" method=\"post\">
	";
	foreach($themes as $t) {
		echo "<div class=\"radio\"><label class=\"radio\"><input type=\"radio\" name=\"theme\" value=\"$t\"";
		if($theme == $t) { echo " checked"; }
		echo ">". ucfirst($t) ." <a href=\"http://bootswatch.com/".$t."/\" target=\"_blank\"><i class=\"fa fa-external-link\"></i></a></label></div>";
	}
	echo "<hr/>";
	if($nav == "navbar navbar-default") {
		echo "
			<div class=\"radio\"><label class=\"radio\"><input type=\"radio\" name=\"nav\" value=\"0\" checked>Normal Navigation Bar</label></div>
			<div class=\"radio\"><label class=\"radio\"><input type=\"radio\" name=\"nav\" value=\"1\">Inverse Navigation Bar</label></div>
		";
	}
	else {
		echo "
			<div class=\"radio\"><label class=\"radio\"><input type=\"radio\" name=\"nav\" value=\"0\">Normal Navigation Bar</label></div>
			<div class=\"radio\"><label class=\"radio\"><input type=\"radio\" name=\"nav\" value=\"1\" checked>Inverse Navigation Bar</label></div>
		";	
	}
	echo '
		<hr/>
		<input type="submit" name="apply" value="Apply Theme &raquo;" class="btn btn-primary"/>
		</form>
	';
}
else {
	if((isset($_POST['theme']) && in_array($_POST['theme'], $themes)) && isset($_POST['nav'])) {
		$theme = $mysqli->real_escape_string($_POST['theme']);
		$nav = $mysqli->real_escape_string($_POST['nav']);
		$update = $mysqli->query("UPDATE ".$prefix."properties SET theme = '$theme', nav = '$nav'");
		echo "<script>$(\"#theme\").attr(\"href\", \"$siteurl/assets/css/\" + \"".$theme."\" + \".min.css\");</script>";
		echo "<div class=\"alert alert-success\">Successfully updated theme.</div>";
		redirect_wait5("?base=admin&page=theme");
	}
	else {
		echo "<div class=\"alert alert-danger\">Please select your theme and navigation bar type!</div>";
	}
}
?>
<script>
$("input[name='theme']").change(function() {
	$("#theme").attr("href", "<?php echo $siteurl; ?>assets/css/" + $(this).val() + ".min.css");
});
$("input[name='nav']").change(function() {
	if($(this).val() == 0) {
		$("#navbar").removeClass("navbar-inverse").addClass("navbar-default");
	}
	else {
		$("#navbar").removeClass("navbar-default").addClass("navbar-inverse");
	}
});
</script>