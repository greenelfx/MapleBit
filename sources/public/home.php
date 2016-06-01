<?php
if(basename($_SERVER["PHP_SELF"]) == "home.php") {
	die("403 - Access Forbidden");
}
$editable = ">";
if(isset($_SESSION['id']) && isset($_SESSION['admin'])) {
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="assets/libs/ckeditor/ckeditor.js"></script>
<script>
$(document).ready(function() {
	CKEDITOR.disableAutoInline = true;
	var content_id = $("#home").attr('id');
	CKEDITOR.inline(content_id, {
		on: {
			blur: function(event) {
				var data = event.editor.getData();
				$.ajax({
					type: "POST",
					url: "?base=misc&script=home",
					data: {
						content : data,
						is_ajax: '1',
					},
				});
			}
		}
	});
});
</script>
<?php
	$editable = " id=\"home\" contenteditable=\"true\">";
}
echo "<hr/><div" . $editable . $gethome['homecontent'] . "</div>";