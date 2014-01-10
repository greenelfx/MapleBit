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
	
?>
<div class="col-md-8">
	<div class="jumbotron">
			<h1>Welcome Back!</h1>
			<p>Hey there, <?php echo $name; ?>! You can use the links below to manage your website configuration, users, reports, and more!</p>
	</div>
</div>
<div class="col-md-4">
	<div class="well">
		Welcome to the MapleBit Administration Panel.<br/>Thanks for trying out MapleBit! Please report any bugs and quirks to greenelf!
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