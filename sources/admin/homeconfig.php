<?php
if(basename($_SERVER["PHP_SELF"]) == "homeconfig.php") {
	die("403 - Access Forbidden");
}
?>
<script src="assets/libs/ckeditor/ckeditor.js"></script>
<?php
$queryhome = $mysqli->query("SELECT homecontent FROM ".$prefix."properties");
$gethome = $queryhome->fetch_assoc();
if(!isset($_POST['submit'])) {
	echo "
		<h2 class=\"text-left\">Home Content</h2><hr/>
		<div class=\"alert alert-info\">
		Adding something like a XAT Chat? Press the \"Source\" button, and paste your embed code.<br/><b>Note:</b> You will not be able to use the inline editor if you embed flash.
		</div>
		<form method='post'>
			<textarea name=\"content\" style=\"height:300px;\" class=\"form-control\" id=\"content\">".$gethome['homecontent']."</textarea><br/>
			<input type='submit' name='submit' value='Submit &raquo;' class=\"btn btn-primary btn-large\"/>
		</form>
	";
}
else {
	$content = $mysqli->real_escape_string(stripslashes($_POST['content']));
	$mysqli->query("UPDATE ".$prefix."properties SET homecontent = '$content'");
	echo "<div class=\"alert alert-success\">Successfully updated home page.</div><hr/><a href=\"?base=admin&amp;page=homeconfig\" class=\"btn btn-primary\">&laquo; Go Back</a>";
}
?>
<script>
	CKEDITOR.replace( 'content' );
</script>