<?php
$editable = ">";
if(isset($_SESSION['id'])){
	if(isset($_SESSION['admin'])){
?>
<script src="assets/libs/ckeditor/ckeditor.js"></script>
<script>
$(document).ready(function() {
    $("div[contenteditable='true']" ).each(function( index ) {
        var content_id = $(this).attr('id');
        CKEDITOR.inline( content_id, {
            on: {
                blur: function( event ) {
                    var data = event.editor.getData();
					$.ajax({
                        type: "POST",
						url: "?base=misc&script=home",
                        data: {
                            content : data,
                            content_id : content_id,
							admin_id : <?php echo $_SESSION['admin']; ?>,
							is_ajax: '1',
                        },
                    });
                }
            }
        });
    });
});
</script>
<?php
		$editable = " contenteditable=\"true\" id=\"home\">";
	}
}
echo "<hr/>
<div" . $editable . $gethome['homecontent'] . "</div>";
?>