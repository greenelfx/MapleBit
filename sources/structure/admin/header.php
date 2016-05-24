<?php
if(basename($_SERVER["PHP_SELF"]) == "header.php") {
    die("403 - Access Forbidden");
}

$admin = "";
if(isset($_GET['page'])) {
	$admin = $_GET['page'];
}

$settings = array("properties", "voteconfig", "nxpacks", "bannedmaps", "theme", "banner", "background");
$content = array("homeconfig", "mannews", "manevent", "pages");
$users = array("manageaccounts", "ticket", "banned");
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo $servername;?></title>
		<link rel="icon" href="favicon.ico" type="image/x-icon" />
		<link href="<?php echo $siteurl; ?>assets/css/<?php echo $theme; ?>.min.css" rel="stylesheet" type="text/css" id="theme"/>
		<link href="<?php echo $siteurl; ?>assets/css/addon.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $siteurl; ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
		body {
			min-height: 200px;
			padding-top: 90px;
		}
		.nav > li > a {
			color: #787878;
		}
		</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo $siteurl;?>assets/js/bootstrap.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('[data-toggle=collapse]').click(function() {
					$(this).find("i").toggleClass("fa-chevron-right fa-chevron-down");
				});
			 });
			function goBack() {
				window.history.back();
			}
		</script>
	</head>
	<body>
		<nav class="<?php echo $nav; ?> navbar-fixed-top" id="navbar">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#"><?php echo $servername; ?></a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li><a href="?base=main">Home</a></li>
					<li><a href="?base=main&amp;page=download">Download</a></li>
					<li><a href="?base=main&amp;page=rankings">Rankings</a></li>
					<li><a href="?base=main&amp;page=vote">Vote</a></li>
					<li><a href="<?php echo $forumurl; ?>">Forums</a></li>
					<?php
					$getpages = $mysqli->query("SELECT * from ".$prefix."pages WHERE visible = 1");
					while ($fetchpages = $getpages->fetch_assoc()) {
						echo "<li><a href=\"?base=main&amp;page=".$fetchpages['slug']."\">" . $fetchpages['title'] . "</a>";
					}
					?>
				</ul>

				<ul class="nav navbar-nav navbar-right" style="margin-right: 20px;">
					<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['pname'];?><b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="?base=main&amp;page=members&amp;name=<?php echo $_SESSION['pname'] ?>">Profile</a></li>
							<li><a href="?base=ucp&amp;page=charfix">Character Fix</a></li>
							<li class="divider"></li>
							<li><a href="?base=misc&amp;script=logout">Log Out</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<!-- Left column -->
					<h2 class="text-left">Panel</h2>
					<hr/>
					<ul class="nav nav-pills nav-stacked">
						<li><a href="#" data-toggle="collapse" data-target="#menu1">Site Settings <i class="fa fa-chevron-<?php echo (in_array($admin, $settings)) ? 'down' : 'right';?>" style="float:right;"></i></a></li>
						<ul class="nav nav-pills nav-stacked collapse <?php echo (in_array($admin, $settings)) ? 'in' : ''; ?>" id="menu1">
							<li <?php echo ($admin == "properties") ? 'class="active"' : ''; ?>><a href="?base=admin&amp;page=properties"><i class="fa fa-cogs"></i> Site Configuration</a></li>
							<li <?php echo ($admin == "voteconfig") ? 'class="active"' : ''; ?>><a href="?base=admin&amp;page=voteconfig"><i class="fa fa-arrow-circle-o-up"></i> Vote Configuration</a></li>
							<li <?php echo ($admin == "nxpacks") ? 'class="active"' : ''; ?>><a href="?base=admin&amp;page=nxpacks"><i class="fa fa-shopping-cart"></i> NX Packs</a></li>
							<li <?php echo ($admin == "bannedmaps") ? 'class="active"' : ''; ?>><a href="?base=admin&amp;page=bannedmaps"><i class="fa fa-ban"></i> Jailed Maps</a></li>
							<li <?php echo ($admin == "theme") ? 'class="active"' : ''; ?>><a href="?base=admin&amp;page=theme"><i class="fa fa-magic"></i> Theme</a></li>
							<li <?php echo ($admin == "banner") ? 'class="active"' : ''; ?>><a href="?base=admin&amp;page=banner"><i class="fa fa-flag"></i> Banner</a></li>
							<li <?php echo ($admin == "background") ? 'class = "active"' : ''; ?>><a href="?base=admin&amp;page=background"><i class="fa fa-object-ungroup"></i> Background</a></li>
						</ul>
						<li><a href="#" data-toggle="collapse" data-target="#menu2">Manage Content <i class="fa fa-chevron-<?php echo (in_array($admin, $content)) ? 'down' : 'right'; ?>" style="float:right;"></i></a></li>
						<ul class="nav nav-pills nav-stacked collapse <?php echo (in_array($admin, $content)) ? 'in' : ''; ?>" id="menu2">
							<li <?php echo ($admin == "homeconfig") ? 'class="active"' : ''; ?>><a href="?base=admin&amp;page=homeconfig"><i class="fa fa-home"></i> Home Content</a></li>
							<li class="dropdown <?php echo ($admin == "mannews") ? 'active' : ''; ?>"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-pencil"></i> News <span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a href="?base=admin&amp;page=mannews&amp;action=add">Add News</a></li>
									<li><a href="?base=admin&amp;page=mannews&amp;action=edit">Edit News</a></li>
									<li><a href="?base=admin&amp;page=mannews&amp;action=del">Delete News</a></li>
								</ul>
							</li>
							<li class="dropdown <?php echo ($admin == "manevent") ? 'active' : ''; ?>"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-pencil"></i> Events <span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a href="?base=admin&amp;page=manevent&amp;action=add">Add Event</a></li>
									<li><a href="?base=admin&amp;page=manevent&amp;action=edit">Edit Event</a></li>
									<li><a href="?base=admin&amp;page=manevent&amp;action=del">Delete Event</a></li>
								</ul>
							</li>
							<li><a href="?base=gmcp"><i class="fa fa-pencil"></i> GM Blogs</a></li>
							<li class="dropdown <?php echo ($admin == "pages") ? 'active' : ''; ?>"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-file-text"></i> Pages <span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a href="?base=admin&amp;page=pages&amp;action=add">Add Page</a></li>
									<li><a href="?base=admin&amp;page=pages&amp;action=edit">Edit Page</a></li>
									<li><a href="?base=admin&amp;page=pages&amp;action=del">Delete Page</a></li>
								</ul>
							</li>
						</ul>
						<li><a href="#" data-toggle="collapse" data-target="#menu3">Manage Community <i class="fa fa-chevron-<?php echo (in_array($admin, $users)) ? 'down' : 'right'; ?>" style="float:right;"></i></a></li>
						<ul class="nav nav-pills nav-stacked collapse <?php echo (in_array($admin, $users)) ? 'in' : ''; ?> " id="menu3">
							<li <?php echo ($admin == "manageaccounts") ? 'class="active"' : ''; ?>><a href="?base=admin&amp;page=manageaccounts"><i class="fa fa-user"></i> Manage Accounts</a></li>
							<li <?php echo ($admin == "ticket") ? 'class="active"' : ''; ?>><a href="?base=admin&amp;page=ticket"><i class="fa fa-ticket"></i> View Tickets</a></li>
							<li <?php echo ($admin == "banned") ? 'class="active"' : ''; ?>><a href="?base=admin&amp;page=banned"><i class="fa fa-gavel"></i> Banned Users</a></li>
						</ul>
					</ul>
					<hr/>
					<h2 class="text-left">Resources</h2>
					<hr/>
					<ul class="nav nav-pills nav-stacked">
						<li class="nav-header"></li>
						<li><a href="?base=admin"><i class="fa fa-tachometer"></i>  Admin Dashboard</a></li>
						<li><a href="https://github.com/greenelfx/MapleBit/"><i class="fa fa-github"></i> GitHub</a></li>
						<li><a href="http://forum.ragezone.com/f690/beta-maplebitcms-977439/"><i class="fa fa-comment"></i> Ragezone Thread</a></li>
					</ul>
					<hr/>
				</div>
				<!-- /col-3 -->
				<div class="col-md-9">