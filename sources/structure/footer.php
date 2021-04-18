<?php
if (basename($_SERVER['PHP_SELF']) == 'footer.php') {
    exit('403 - Access Forbidden');
}
?>
</div>
<br />
</div>
</div>
<footer>
	<div class="container mt-4">
		<p class="text-center">Proudly powered by MapleBit | <a href="http://forum.ragezone.com/members/1333360872.html">greenelf(x) &raquo;</a></p><br />
	</div>
</footer>
</div>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script type="text/javascript" src="<?php echo $siteurl; ?>assets/js/login.js"></script>
<script type="text/javascript">
	function roll(img_name1, img_src1) {
		document.getElementById(img_name1).src = img_src1;
	}

	function goBack() {
		window.history.back()
	}
</script>
</body>

</html>