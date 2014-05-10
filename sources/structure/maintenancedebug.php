<?php
if(basename($_SERVER["PHP_SELF"]) == "header.php"){
    die("403 - Access Forbidden");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $sitetitle; ?></title>
<link rel="icon" href="favicon.ico" type="image/x-icon" />
<link href="<?php echo $siteurl; ?>assets/css/<?php echo $theme; ?>.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $siteurl; ?>assets/css/addon.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $siteurl; ?>assets/css/<?php echo $themetype; ?>.css" rel="stylesheet" type="text/css" />
<style>
body{
	background: #<?php echo $bgcolor; ?> url(<?php echo $background; ?>) <?php echo $bgrepeat . " " . $bgcenter; ?>;
	<?php
	if($bgfixed == 1) {
	echo "background-attachment: fixed;";
	}
	if($bgcover == 1) {
	echo "
	background-size: cover;";
	}
	?>
	
}
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $siteurl; ?>assets/js/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo $siteurl; ?>assets/js/login.js"></script>
<script>
	function roll(img_name1, img_src1) {document[img_name1].src = img_src1;}
	function goBack() {window.history.back()}
</script>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="<?php echo $siteurl; ?>assets/js/ie/html5shiv.js"></script>
  <script src="<?php echo $siteurl; ?>assets/js/ie/respond.min.js"></script>
<![endif]-->
</head>
<body>
<div class="container">
<?php
if($prop['debug'] == 1){echo "<hr/><div class=\"alert alert-info\">Site is currently in maintenance mode! Only administrators can use the website.</div><hr/>";}
if($banner != ""){echo "<img src=\"".$banner."\" alt=\"banner\" class=\"img-responsive\" style=\"margin: 0 auto;margin-top:20px;\">";} 
?>
<div class="well">
<div class="row">
	<div class="col-md-3">
		<?php include("sources/structure/sidebar.php"); ?>
	</div>
<div class="col-md-9">
<h2 class="text-left">Maintenance Mode</h2>