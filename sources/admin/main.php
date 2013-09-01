<?php 
/*
    Copyright (C) 2009  Murad <Murawd>
						Josh L. <Josho192837>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if(isset($_GET['page'])){
	$admin = $_GET['page'];
	include("sources/structure/header.php");
}else{
	$admin = "";
}
if($_SESSION['id']){
	if($_SESSION['admin']){
		if($getcype == "admin"){
			if($admin == ""){
			include("sources/structure/aheader.php");
	
?>
<div class="col-md-8">
	<div class="jumbotron">
			<h1>Welcome Back!</h1>
			<p>Hey there, <?php echo $name; ?>! You can use the links below to manage your website configuration, users, reports, and more!</p>
	</div>
</div>
<div class="col-md-4">
	<div class="well">
		Welcome Alpha Testers!<br/>Thanks for trying out CypeReboot! Please report any bugs and quirks to greenelf!
	</div>
</div>
</div>
<div class="row">
  <div class="col-md-3">
	<div class="well">
		<li><a href="?cype=admin&page=mannews&amp;action=add"><b>Add News &raquo;</b></a></li>
		<li><a href="?cype=admin&page=mannews&amp;action=edit">Edit News</a></li>
		<li><a href="?cype=admin&page=mannews&amp;action=del">Delete News</a></li>
	</div>
  </div>
  <div class="col-md-3 ">
	<div class="well">
		<li><a href="?cype=admin&amp;page=manevent&amp;action=add"><b>Add Event &raquo;</b></a></li>
        <li><a href="?cype=admin&amp;page=manevent&amp;action=edit">Edit Event</a></li>
        <li><a href="?cype=admin&amp;page=manevent&amp;action=del">Delete Event</a></li>
	</div>
  </div>
  <div class="col-md-3">
  	<div class="well">
	<a href="?cype=admin&amp;page=muteuser">Mute User</a><br/>
	<a href="?cype=admin&amp;page=unmuteuser">Unmute User</a><br/>
	</div>
  </div>
  <div class="col-md-3">
  	<div class="well">
	<a href="?cype=admin&amp;page=voteconfig">Edit Vote Configuration</a>
	</div>
  </div>
</div>
<div class="row">
  <div class="col-md-3">
	<div class="well">
	<a href="?cype=admin&amp;page=properties">Edit Site Configuration</a><br/>
	Changes your rates, server name, and more!
	</div>
  </div>
  <div class="col-md-3 ">
	<div class="well">
	<a href="?cype=admin&amp;page=theme">Edit Theme</a><br/>
	Want to spice your site up? Change your theme here!
	</div>
  </div>
  <div class="col-md-3">
  	<div class="well">
	<a href="?cype=main&amp;page=guildlist">View Guilds<br/>
	<a href="?cype=admin&amp;page=banned">View Banned Members</a>
	</div>
  </div>
  <div class="col-md-3">
  	<div class="well">
	<a href="?cype=admin&amp;page=gmlog">View GM Log</a>
	</div>
  </div>
</div>
<?php
			}elseif($admin == "voteconfig"){
				include('sources/admin/voteconfig.php');
			}elseif($admin == "gmlog"){
				include('sources/admin/gmlog.php');
			}elseif($admin == "logs"){
				include('sources/admin/logs.php');
			}elseif($admin == "manevent"){
				include('sources/admin/manage-event.php');
			}elseif($admin == "mannews"){
				include('sources/admin/manage-news.php');
			}elseif($admin == "manuser"){
				include('sources/admin/manage-user.php');
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
			}elseif($admin == "pages"){
				include('sources/admin/pages.php');
			}elseif($admin == "ticket"){
				include('sources/admin/ticket.php');
			}
			else{header("Location: ?cype=admin");}
			if($admin!=""){
			include("sources/structure/footer.php");
			}
		}else{
			header("Location: ?cype=admin");
		}
	}else{
		include('sources/public/accessdenied.php');
	}
}else{
	include('sources/public/accessdenied.php');
}

?>