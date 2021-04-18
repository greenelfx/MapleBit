<?php
if (basename($_SERVER['PHP_SELF']) == 'header.php') {
    exit('403 - Access Forbidden');
}

$admin = '';
if (isset($_GET['page'])) {
    $admin = $_GET['page'];
}

$settings = ['properties', 'voteconfig', 'nxpacks', 'bannedmaps', 'theme', 'banner', 'background'];
$content = ['homeconfig', 'mannews', 'manevent', 'pages'];
$users = ['manageaccounts', 'banned'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $servername; ?></title>
	<link rel="icon" href="favicon.ico" type="image/x-icon" />
	<link href="<?php echo $siteurl; ?>assets/css/<?php echo $theme; ?>.min.css" rel="stylesheet" type="text/css" id="theme" />
	<link href="<?php echo $siteurl; ?>assets/css/addon.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $siteurl; ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
		body {
			padding-top: 0px;
			min-height: 200px;
		}

		.nav>li>a {
			color: #787878;
		}
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
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
	<nav class="<?php echo $nav; ?> navbar-fixed-top mb-4" id="navbar">
		<a class="navbar-brand" href="#"><?php echo $servername; ?></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item"><a class="nav-link" href="?base=main">Home</a></li>
				<li class="nav-item"><a class="nav-link" href="?base=main&amp;page=download">Download</a></li>
				<li class="nav-item"><a class="nav-link" href="?base=main&amp;page=rankings">Rankings</a></li>
				<li class="nav-item"><a class="nav-link" href="?base=main&amp;page=vote">Vote</a></li>
				<li class="nav-item"><a class="nav-link" href="<?php echo $forumurl; ?>">Forums</a></li>
				<?php
                $getpages = $mysqli->query('SELECT * from '.$prefix.'pages WHERE visible = 1');
                while ($fetchpages = $getpages->fetch_assoc()) {
                    echo '<li class="nav-item"><a class="nav-link" href="?base=main&amp;page='.$fetchpages['slug'].'">'.$fetchpages['title'].'</a>';
                }
                ?>
			</ul>

			<ul class="navbar-nav ml-auto">
				<li class="nav-item dropdown">
					<a href="#" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<img src="<?php echo get_gravatar($_SESSION['email'], 40); ?>" alt="gravatar" class="img-fluid rounded-circle" style="float:left;margin-top: -10px;padding-right: 5px;"><?php echo $_SESSION['name']; ?><b class="caret"></b>
					</a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
						<?php
                        if ($_SESSION['pname'] == 'checkpname') {
                            echo '<a class="dropdown-item" href="?base=ucp&amp;page=profname">Set Profile Name</a>';
                        } else {
                            echo '<a class="dropdown-item" href="?base=main&amp;page=members&amp;name='.$_SESSION['pname'].'">Profile</a>';
                        }
                        ?>
						<a class="dropdown-item" href="?base=ucp&amp;page=charfix">Character Fix</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="?base=misc&amp;script=logout">Log Out</a>
					</div>
				</li>
			</ul>
		</div>
	</nav>
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<!-- Left column -->
				<h2 class="text-left">Panel</h2>
				<hr />
				<ul class="nav flex-column flex-nowrap">
					<li class="nav-item">
						<a href="#" class="nav-link <?php echo (in_array($admin, $settings)) ? '' : 'collapsed'; ?>" data-toggle="collapse" data-target="#settings-menu">
							<i class="fa fa-chevron-<?php echo (in_array($admin, $settings)) ? 'down' : 'right'; ?>"></i>&nbsp;Site Settings
						</a>
						<div class="collapse <?php echo (in_array($admin, $settings)) ? 'show' : ''; ?>" id="settings-menu" aria-expanded="false">
							<ul class="flex-column pl-2 nav">
								<li class="nav-item"><a class="nav-link py-0" href="?base=admin&amp;page=properties"><i class="fa fa-cogs"></i> Site Configuration</a></li>
								<li class="nav-item"><a class="nav-link py-0" href="?base=admin&amp;page=voteconfig"><i class="fa fa-arrow-circle-o-up"></i> Vote Configuration</a></li>
								<li class="nav-item"><a class="nav-link py-0" href="?base=admin&amp;page=nxpacks"><i class="fa fa-shopping-cart"></i> NX Packs</a></li>
								<li class="nav-item"><a class="nav-link py-0" href="?base=admin&amp;page=bannedmaps"><i class="fa fa-ban"></i> Jailed Maps</a></li>
								<li class="nav-item"><a class="nav-link py-0" href="?base=admin&amp;page=theme"><i class="fa fa-magic"></i> Theme</a></li>
								<li class="nav-item"><a class="nav-link py-0" href="?base=admin&amp;page=banner"><i class="fa fa-flag"></i> Banner</a></li>
								<li class="nav-item"><a class="nav-link py-0" href="?base=admin&amp;page=background"><i class="fa fa-object-ungroup"></i> Background</a></li>
							</ul>
						</div>
					</li>
					<li class="nav-item">
						<a href="#" class="nav-link <?php echo (in_array($admin, $content)) ? '' : 'collapsed'; ?>" data-toggle="collapse" data-target="#content-menu">
							<i class="fa fa-chevron-<?php echo (in_array($admin, $content)) ? 'down' : 'right'; ?>"></i>&nbsp;Manage Content
						</a>
						<div class="collapse <?php echo (in_array($admin, $content)) ? 'show' : ''; ?>" id="content-menu" aria-expanded="false">
							<ul class="flex-column pl-2 nav">
								<li class="nav-item">
									<a class="nav-link py-0" href="?base=admin&amp;page=homeconfig">
										<i class="fa fa-home"></i> Home Content
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link py-0" href="?base=gmcp"><i class="fa fa-pencil"></i> GM Blogs</a>
								</li>
								<li class="nav-item">
									<a class="nav-link collapsed py-1" data-toggle="collapse" href="#news-menu" data-target="#news-menu">
										<i class="fa fa-chevron-right"></i>&nbsp;News
									</a>
									<div class="collapse" id="news-menu" aria-expanded="false">
										<ul class="flex-column nav pl-4">
											<li class="nav-item"><a class="nav-link p-1" href="?base=admin&amp;page=mannews&amp;action=add">Add News</a></li>
											<li class="nav-item"><a class="nav-link p-1" href="?base=admin&amp;page=mannews&amp;action=edit">Edit News</a></li>
											<li class="nav-item"><a class="nav-link p-1" href="?base=admin&amp;page=mannews&amp;action=del">Delete News</a></li>
										</ul>
									</div>
								</li>
								<li class="nav-item">
									<a class="nav-link collapsed py-1" data-toggle="collapse" href="#events-menu" data-target="#events-menu">
										<i class="fa fa-chevron-right"></i>&nbsp;Events
									</a>
									<div class="collapse" id="events-menu" aria-expanded="false">
										<ul class="flex-column nav pl-4">
											<li class="nav-item"><a class="nav-link p-1" href="?base=admin&amp;page=manevent&amp;action=add">Add Event</a></li>
											<li class="nav-item"><a class="nav-link p-1" href="?base=admin&amp;page=manevent&amp;action=edit">Edit Event</a></li>
											<li class="nav-item"><a class="nav-link p-1" href="?base=admin&amp;page=manevent&amp;action=del">Delete Event</a></li>
										</ul>
									</div>
								</li>
								<li class="nav-item">
									<a class="nav-link collapsed py-1" data-toggle="collapse" href="#pages-menu" data-target="#pages-menu">
										<i class="fa fa-chevron-right"></i>&nbsp;Pages
									</a>
									<div class="collapse" id="pages-menu" aria-expanded="false">
										<ul class="flex-column nav pl-4">
											<li class="nav-item"><a class="nav-link p-1" href="?base=admin&amp;page=pages&amp;action=add">Add Page</a></li>
											<li class="nav-item"><a class="nav-link p-1" href="?base=admin&amp;page=pages&amp;action=edit">Edit Page</a></li>
											<li class="nav-item"><a class="nav-link p-1" href="?base=admin&amp;page=pages&amp;action=del">Delete Page</a></li>
										</ul>
									</div>
								</li>
							</ul>
						</div>
					</li>
					<li class="nav-item">
					<li class="nav-item">
						<a href="#" class="nav-link <?php echo (in_array($admin, $users)) ? '' : 'collapsed'; ?>" data-toggle="collapse" data-target="#users-menu">
							<i class="fa fa-chevron-<?php echo (in_array($admin, $users)) ? 'down' : 'right'; ?>"></i>&nbsp;Manage Community
						</a>
						<div class="collapse <?php echo (in_array($admin, $users)) ? 'show' : ''; ?>" id="users-menu" aria-expanded="false">
							<ul class="flex-column pl-2 nav">
								<li class="nav-item"><a class="nav-link py-0" href="?base=admin&amp;page=manageaccounts"><i class="fa fa-cogs"></i> Manage Accounts</a></li>
								<li class="nav-item"><a class="nav-link py-0" href="?base=admin&amp;page=banned"><i class="fa fa-gavel"></i> Banned Users</a></li>
							</ul>
						</div>
					</li>
				</ul>
				<hr />
				<h2 class="text-left">Resources</h2>
				<hr />
				<ul class="nav flex-column">
					<li class="nav-item"><a class="nav-link" href="?base=admin"><i class="fa fa-tachometer"></i> Admin Dashboard</a></li>
					<li class="nav-item"><a class="nav-link" href="https://github.com/greenelfx/MapleBit/"><i class="fa fa-github"></i> GitHub</a></li>
					<li class="nav-item"><a class="nav-link" href="http://forum.ragezone.com/f690/beta-maplebitcms-977439/"><i class="fa fa-comment"></i> Ragezone Thread</a></li>
				</ul>
				<hr />
			</div>
			<!-- /col-3 -->
			<div class="col-md-9">