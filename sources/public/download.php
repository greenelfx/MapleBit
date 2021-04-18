<?php
if (basename($_SERVER['PHP_SELF']) == 'download.php') {
    exit('403 - Access Forbidden');
}
?>
<h2 class="text-left">Downloads</h2>
<hr />
<div class="text-center">
	<a href="<?php echo $server; ?>"><img src="<?php echo $siteurl; ?>assets/img/DL/setup.png" alt="Setup Download" class="img-fluid"></a>
	<hr />
	<a href="<?php echo $client; ?>"><img src="<?php echo $siteurl; ?>assets/img/DL/client.png" alt="Client Download" class="img-fluid"></a>
</div>