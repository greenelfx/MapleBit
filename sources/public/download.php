<?php
if(basename($_SERVER["PHP_SELF"]) == "download.php") {
	die("403 - Access Forbidden");
}
?>
<h2 class="text-left">Downloads</h2><hr/>
<a href="<?php echo $server; ?>"><img src="<?php echo $siteurl; ?>assets/img/DL/setup.png" alt="Setup Download" class="img-responsive" style="margin: 0 auto;"></a>
<hr/>
<a href="<?php echo $client; ?>"><img src="<?php echo $siteurl; ?>assets/img/DL/client.png" alt="Client Download" class="img-responsive" style="margin: 0 auto;"></a>