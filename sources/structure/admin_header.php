<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $sitetitle.$pb; ?></title>
<link rel="icon" href="favicon.ico" type="image/x-icon" />
<link href="assets/css/<?php echo $theme; ?>.min.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body {
  min-height: 200px;
  padding-top: 90px;
}
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.js"></script>
</head>

<body>
<?php getNav();?>
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="#"><?php echo $servername; ?></a>
	</div>	
	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav">
              <li><a href="?cype=main">Home</a></li>
			<?php
				if(isset($_SESSION['id'])){
					echo "";
				}
				else{echo "<li><a href=\"?cype=main&amp;page=register\">Register</a></li>";}
			?>
              <li><a href="?cype=main&amp;page=download">Download</a></li>
			  <li><a href="?cype=main&amp;page=ranking">Rankings</a></li>
			  <li><a href="?cype=main&amp;page=vote">Vote</a></li>
            </ul>
		<?php	
			if(isset($_SESSION['id'])){
			$name = $_SESSION['name'];
		?>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $name; ?><b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="?cype=main&amp;page=members&amp;name=<?php echo $_SESSION['pname'] ?>">Profile</a></li>
						<li><a href="?cype=ucp&page=mail&s=3"><?php mailStats(3)?> Unread Mail</a></li>
						<li><a href="?cype=ucp&amp;page=charfix">Character Fix</a></li>
						<li class="divider"></li>
						<li><a href="?cype=misc&amp;script=logout">Log Out</a></li>
					</ul>
				</li>
			</ul>
		<?php } ?>
	</div><!-- /.navbar-collapse -->
</nav>

<div class="container">
  <div class="row">