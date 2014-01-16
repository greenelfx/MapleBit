<?php 
if(isset($_GET['page'])){
	$admin = $_GET['page'];
	include("sources/structure/header.php");
}else{
	$admin = "";
}
if($_SESSION['id']){
	if($_SESSION['admin']){
		if($getbase == "admin"){
			if($admin == ""){
				include("sources/structure/admin_header.php");
				if (extension_loaded('openssl')) {
					$opts = array(
						'http'=>array(
						'method'=>"GET",
						'header'=>"User-Agent: maplebit"
						)
					);
					$context = stream_context_create($opts);
					#$current_commits = file_get_contents("https://api.github.com/repos/greenelf/maplebit/commits", false, $context);
					$current_tags = file_get_contents("https://api.github.com/repos/greenelf/maplebit/tags", false, $context);
					if ($current_tags !== false) {
						#$commits = json_decode($current_commits);
						$tags = json_decode($current_tags);
						#$ref_commit = "7350dcac3e5d3bb7fede63e4e5cfff3852bcc9df";
						$ref_tag = "v1.02";
						#$current_commit_minus1 = $commits[1]->sha;
						$current_tag = $tags[0]->name;
						#$commit_message = $commits[0]->commit->message;
						if ($current_tag == $ref_tag) {
							$alert_class = "success";
							$version_message = "<b>MapleBit is up to date.</b>";
						} else {
							$alert_class = "info";
							$version_message = "<a href=\"https://github.com/greenelf/MapleBit\" class=\"alert-link\">Update Available &raquo;</a>";
						}
					} else {
							$alert_class = "danger";
							$version_message = "Can't get MapleBit update status.";
					}
				} else {
					$alert_class = "danger";
					$version_message = "Enable openssl by right clicking wamp, then PHP, and then scroll down to php_openssl";
				}
?>
<div class="col-md-8">
	<div class="jumbotron">
			<h1>Welcome Back!</h1>
			<p>Hey there, <?php echo $name; ?>! You can use the links below to manage your website configuration, users, reports, and more!</p>
	</div>
</div>
<div class="col-md-4">
	<div class="well">
		Welcome to the MapleBit Administration Panel.<br/>Please report any bugs and quirks to greenelf!
	</div>
	<div class="alert alert-<?php echo $alert_class; ?>">
		<h2 style="margin: 0px;">MapleBit Status</h2><hr/>
		<?php echo $version_message; ?>
	</div>
</div>
</div>
<div class="row">
  <div class="col-md-3">
	<div class="well">
		<a href="?base=admin&page=mannews&amp;action=add"><b>Add News &raquo;</b></a><br/>
		<a href="?base=admin&page=mannews&amp;action=edit">Edit News</a><br/>
		<a href="?base=admin&page=mannews&amp;action=del">Delete News</a>
	</div>
  </div>
  <div class="col-md-3 ">
	<div class="well">
		<a href="?base=admin&amp;page=manevent&amp;action=add"><b>Add Event &raquo;</b></a><br/>
        <a href="?base=admin&amp;page=manevent&amp;action=edit">Edit Event</a><br/>
        <a href="?base=admin&amp;page=manevent&amp;action=del">Delete Event</a>
	</div>
  </div>
  <div class="col-md-3">
  	<div class="well">
		<a href="?base=admin&amp;page=pages&amp;action=add"><b>Add Page &raquo;</b></a><br/>
		<a href="?base=admin&amp;page=pages&amp;action=edit">Edit Page</a><br/>
		<a href="?base=admin&amp;page=pages&amp;action=del">Delete Page</a>
	</div>
  </div>
  <div class="col-md-3">
  	<div class="well">
	<a href="?base=admin&amp;page=voteconfig">Edit Vote Configuration</a><br/>
	<a href="?base=admin&amp;page=nxpacks">Add NX Packages</a>
	</div>
  </div>
</div>
<div class="row">
  <div class="col-md-3">
	<div class="well">
	<a href="?base=admin&amp;page=properties">Edit Site Configuration</a><br/>
	Changes your rates, server name, and more!
	</div>
  </div>
  <div class="col-md-3 ">
	<div class="well">
	<a href="?base=admin&amp;page=theme">Edit Theme</a><br/>
	<a href="?base=admin&amp;page=banner">Add Banner</a><br/>
	<a href="?base=admin&amp;page=background">Add Background</a>
	</div>
  </div>
  <div class="col-md-3">
  	<div class="well">
	<a href="?base=admin&amp;page=muteuser">Mute User</a><br/>
	<a href="?base=admin&amp;page=unmuteuser">Unmute User</a><br/>
	<a href="?base=admin&amp;page=ticket">Manage Tickets</a>
	</div>
  </div>
  <div class="col-md-3">
  	<div class="well">
	<a href="?base=main&amp;page=guildlist">View Guilds<br/>
	<a href="?base=admin&amp;page=banned">View Banned Members</a><br/>
	<a href="?base=admin&amp;page=gmlog">View GM Log</a>
	</div>
  </div>
</div>
<?php
			}elseif($admin == "voteconfig"){
				include('sources/admin/voteconfig.php');
			}elseif($admin == "gmlog"){
				include('sources/admin/gmlog.php');
			}elseif($admin == "manevent"){
				include('sources/admin/manage-event.php');
			}elseif($admin == "mannews"){
				include('sources/admin/manage-news.php');
			}elseif($admin == "muteuser"){
				include('sources/admin/mute-user.php');
			}elseif($admin == "unmuteuser"){
				include('sources/admin/unmute-user.php');
			}elseif($admin == "properties"){
				include('sources/admin/properties.php');
			}elseif($admin == "banned"){
				include('sources/admin/banned.php');
			}elseif($admin == "nxpacks"){
				include('sources/admin/nxpacks.php');
			}elseif($admin == "theme"){
				include('sources/admin/theme.php');
			}elseif($admin == "banner"){
				include('sources/admin/banner.php');
			}elseif($admin == "background"){
				include('sources/admin/background.php');
			}elseif($admin == "ticket"){
				include('sources/admin/ticket.php');
			}elseif($admin == "pages"){
				include('sources/admin/pages.php');
			}elseif($admin == "update"){
				include('sources/admin/update.php');
			}
			else{header("Location: ?base=admin");}
			if($admin!=""){
			include("sources/structure/footer.php");
			}
		}else{
			redirect("?base=main");
		}
	}else{
		redirect("?base=main");
	}
}else{
	redirect("?base=main");
}

?>