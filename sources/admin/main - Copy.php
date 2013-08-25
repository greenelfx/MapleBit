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
}else{
	$admin = "";
}
if($_SESSION['id']){
	if($_SESSION['admin']){
		if($getcype == "admin"){
			if($admin == ""){
				echo "
			<legend>Cype Administration</legend>
<script>
$('#myTab a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
</script>
<ul id=\"myTab\" class=\"nav nav-tabs\">
	<li class=\"active\"><a href=\"#newsman\" data-toggle=\"tab\">News</a></li>
	<li><a href=\"#accountman\" data-toggle=\"tab\">Accounts</a></li>
	<li><a href=\"#ticket\" data-toggle=\"tab\">Tickets</a></li>
	<li><a href=\"#siteman\" data-toggle=\"tab\">Main Management</a></li>
	<li><a href=\"?cype=admin&page=theme\">Themes</a></li>	
</ul>

<div id=\"myTabContent\" class=\"tab-content\">
<div class=\"tab-pane fade in active\" id=\"newsman\">
<br/>
 	<ul class=\"unstyled\">
	<li><a href=\"?cype=admin&page=mannews&amp;action=add\"><b>Add News &raquo;</b></a><br/>
	<ul>
		<li><a href=\"?cype=admin&page=mannews&amp;action=edit\">Edit News</a><br/></li>
		<li><a href=\"?cype=admin&page=mannews&amp;action=del\">Delete News</a><br/></li>
	</ul>
	</li>
	<br/>
	<li><a href=\"?cype=admin&page=manevent&amp;action=add\"><b>Add Event &raquo;</b></a><br/>
	<ul>
		<li><a href=\"?cype=admin&page=manevent&amp;action=edit\">Edit Event</a><br/></li>
		<li><a href=\"?cype=admin&page=manevent&amp;action=del\">Delete Event</a><br/></li>
	</ul>
	</li>
</div>
<div class=\"tab-pane fade\" id=\"accountman\">
<br/>
	<a href=\"?cype=admin&amp;page=logs\">IP Logs</a><br/>
	<a href=\"?cype=admin&amp;page=banned\">Banned Users</a><br/>
	<a href=\"?cype=admin&amp;page=muteuser\">Mute User</a><br/>
	<a href=\"?cype=admin&amp;page=unmuteuser\">Unmute User</a><br/>
</div>
<div class=\"tab-pane fade\" id=\"ticket\">
<br/>
	".unSolved("ticket")."<br/>
</div>
<div class=\"tab-pane fade\" id=\"siteman\">
<br/>
	<a href=\"?cype=admin&page=properties\">Properties Editor</a><br/>
	<a href=\"?cype=admin&page=dbedit\">Database Configuration</a><br/>
	<a href=\"?cype=admin&page=gmlog\">GameMaster Command Log</a><br/>
	<a href=\"?cype=admin&page=pages\">Site Pages</a><br/>
	<a href=\"?cype=admin&page=nxpacks\">NX Packages</a><br/>
	<a href=\"?cype=admin&page=theme\">Theme</a><br/>
</div></div>";
			}elseif($admin == "dbedit"){
				include('sources/admin/dbedit.php');
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
		}else{
			header("Location: ?cype=admin");
		}
	}else{
		include('sources/public/accessdenied.php');
	}
}else{
	include('sources/public/login.php');
}

?>