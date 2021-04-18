<?php
if (basename($_SERVER['PHP_SELF']) == 'theme.php') {
    exit('403 - Access Forbidden');
}

echo '<h2 class="text-left">Configure Theme</h2><hr/>';
$themes = ['bootstrap', 'cerulean', 'cosmo', 'cyborg', 'darkly', 'flatly', 'journal', 'lumen', 'materia', 'sandstone', 'slate', 'simplex', 'spacelab', 'superhero', 'united', 'yeti', 'pulse', 'solar', 'minty'];
if (!isset($_POST['apply'])) {
    echo '
		Please click one of the options below to preview the theme. Once you are happy with your theme, click the "Apply Theme" button below.
		<hr/>
		<form name="applytheme" method="post">
	';
    foreach ($themes as $t) {
        $checked = ($theme == $t ? 'checked' : '');
        $display_text = ucfirst($t).'&nbsp;<a href="http://bootswatch.com/'.$t.'/" target="_blank"><i class="fa fa-external-link"></i></a>';
        echo '
			<div class="form-check">
				<input class="form-check-input" type="radio" name="theme" value="'.$t.'" id="'.$t.'" '.$checked.'>
				<label class="form-check-label" for="'.$t.'">'.$display_text.'</label>
			</div>
		';
    }
    echo '<hr/>';
    if ($nav == 'navbar navbar-expand-lg navbar-light bg-light') {
        echo "
			<div class=\"form-check\">
				<input class=\"form-check-input\" type=\"radio\" name=\"nav\" value=\"0\" id='normal' checked>
				<label class=\"form-check-label\" for=\"normal\">Normal Navigation Bar (light)</label>
			</div>
			<div class=\"form-check\">
				<input class=\"form-check-input\" type=\"radio\" name=\"nav\" value=\"1\" id='inverse'>
				<label class=\"form-check-label\" for=\"inverse\">Inverse Navigation Bar (dark)</label>
			</div>
		";
    } else {
        echo "
			<div class=\"form-check\">
				<input class=\"form-check-input\" type=\"radio\" name=\"nav\" value=\"0\" id='normal'>
				<label class=\"form-check-label\" for=\"normal\">Normal Navigation Bar (light)</label>
			</div>
			<div class=\"form-check\">
				<input class=\"form-check-input\" type=\"radio\" name=\"nav\" value=\"1\" id='inverse' checked>
				<label class=\"form-check-label\" for=\"inverse\">Inverse Navigation Bar (dark)</label>
			</div>
		";
    }
    echo '
		<hr/>
		<input type="submit" name="apply" value="Apply Theme &raquo;" class="btn btn-primary"/>
		</form>
	';
} else {
    if ((isset($_POST['theme']) && in_array($_POST['theme'], $themes)) && isset($_POST['nav'])) {
        $theme = $mysqli->real_escape_string($_POST['theme']);
        $nav = $mysqli->real_escape_string($_POST['nav']);
        $update = $mysqli->query('UPDATE '.$prefix."properties SET theme = '$theme', nav = '$nav'");
        echo "<script>$(\"#theme\").attr(\"href\", \"$siteurl/assets/css/\" + \"".$theme.'" + ".min.css");</script>';
        echo '<div class="alert alert-success">Successfully updated theme.</div>';
        redirect_wait5('?base=admin&page=theme');
    } else {
        echo '<div class="alert alert-danger">Please select your theme and navigation bar type!</div>';
    }
}
?>
<script>
	$("input[name='theme']").change(function() {
		$("#theme").attr("href", "<?php echo $siteurl; ?>assets/css/" + $(this).val() + ".min.css");
	});
	$("input[name='nav']").change(function() {
		if ($(this).val() == 0) {
			$("#navbar").removeClass("navbar-dark bg-dark").addClass("navbar-light bg-light");
		} else {
			$("#navbar").removeClass("navbar-light bg-light").addClass("navbar-dark bg-dark");
		}
	});
</script>