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
		$gmcp = $_GET['page'];
}else{
	$gmcp = "";
}
if($_SESSION['id']){
	if($_SESSION['gm']){
		if($getcype == "gmcp"){
			if($gmcp == ""){
				echo "<legend><b>GameMaster Control Panel</b></legend>";
				echo "<script>
$('#myTab a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
</script>
<ul id=\"myTab\" class=\"nav nav-tabs\">
	<li class=\"active\"><a href=\"#blog\" data-toggle=\"tab\">Blogs</a></li>
	<li><a href=\"#ticket\" data-toggle=\"tab\">Tickets</a></li>
	<li><a href=\"#misc\">Misc</a></li>	
</ul>

<div id=\"myTabContent\" class=\"tab-content\">
<div class=\"tab-pane fade in active\" id=\"blog\">
<h4>GM Blog Management</h4>
Welcome to the GM Blog section. You can write, edit, and delete your blogs, but you can't modify anyone else's blogs. Please select an option.
<br/><br/>
<div class=\"btn-group\">
  <a href=\"?cype=gmcp&amp;page=manblog&amp;action=add\" class=\"btn\">Add</a>
  <a href=\"?cype=gmcp&amp;page=manblog&amp;action=edit\" class=\"btn\">Edit</a>
  <a href=\"?cype=gmcp&amp;page=manblog&amp;action=del\" class=\"btn\">Delete</a>
</div>
</div>
<div class=\"tab-pane fade\" id=\"ticket\">
<a href=\"?cype=gmcp&amp;page=ticket\">".unSolved("ticket")."
</div>
</div>
";
		}elseif($gmcp == "manblog"){
			include('sources/gmcp/manage-blog.php');
		}elseif($gmcp == "ticket"){
			include('sources/gmcp/ticket.php');
		}
		}else{
			header("Location: ?cype=gmcp");
		}
	}else{
		include('sources/public/accessdenied.php');
	}
}else{
	include('sources/public/login.php');
}
?>