<?php
if (basename($_SERVER['PHP_SELF']) == 'header.php') {
    exit('403 - Access Forbidden');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $servername; ?></title>
	<link rel="icon" href="favicon.ico" type="image/x-icon" />
	<link href="<?php echo $siteurl; ?>assets/css/<?php echo $theme; ?>.min.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $siteurl; ?>assets/css/addon.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
		body {
			<?php
            if (!empty($background)) {
                echo 'background-image: url('.$background.');';
            }
            if (!empty($bgcolor)) {
                echo 'background-color: #'.$bgcolor.';';
            }
            if (!empty($bgrepeat)) {
                echo 'background-repeat: '.$bgrepeat.';';
            }
            if (!empty($bgcenter)) {
                echo 'background-position: center;';
            }
            if (!empty($bgfixed)) {
                echo 'background-attachment: fixed;';
            }
            if (!empty($bgcover)) {
                echo 'background-size: cover;';
            }
            ?>
		}
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
	<div class="container">
		<?php
        if ($banner != '') {
            echo '<img src="'.$banner.'" alt="banner" class="img-responsive" style="margin: 0 auto;margin-top:20px;">';
        }
        ?>
		<nav class="<?php echo $nav; ?>">
			<a class="navbar-brand" href="#"><?php echo $servername; ?></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarContent">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item"><a class="nav-link" href="?base=main">Home</a></li>
					<?php
                    if (!isset($_SESSION['id'])) {
                        echo '<li><a class="nav-link" href="?base=main&amp;page=register">Register</a></li>';
                    }
                    ?>
					<li class="nav-item"><a class="nav-link" href="?base=main&amp;page=download">Download</a></li>
					<li class="nav-item"><a class="nav-link" href="?base=main&amp;page=rankings">Rankings</a></li>
					<li class="nav-item"><a class="nav-link" href="?base=main&amp;page=vote">Vote</a></li>
					<li class="nav-item"><a class="nav-link" href="<?php echo $forumurl; ?>">Forums</a></li>
					<?php
                    if (!empty($slugarray)) {
                        foreach ($slugarray as $page) {
                            if ($page[2]) {
                                echo '<li class="nav-item"><a class="nav-link" href="?base=main&amp;page='.$page[0].'">'.$page[1].'</a></li>';
                            }
                        }
                    }
                    ?>
				</ul>
				<?php
                if (isset($_SESSION['id'])) {
                    ?>
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
                                } ?>
								<a class="dropdown-item" href="?base=ucp&amp;page=charfix">Character Fix</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="?base=misc&amp;script=logout">Log Out</a>
							</div>
						</li>
					</ul>
				<?php
                }
                ?>
			</div>
		</nav>

		<div class="card card-body">
			<div class="row">
				<div class="col-md-3">
					<?php include 'sources/structure/sidebar.php'; ?>
				</div>
				<div class="col-md-9">