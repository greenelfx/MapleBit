<?php 
if(isset($_GET['page'])){
		$gmcp = $_GET['page'];
}else{
	$gmcp = "";
}
if($_SESSION['id']){
	if($_SESSION['gm'] || $_SESSION['admin']){
		if($getbase == "gmcp"){
			if($gmcp == ""){
				echo "<h2 class=\"text-left\">GameMaster Panel</h2><hr/>";
				echo "
					<script>
					$('#myTab a').click(function (e) {
					  e.preventDefault();
					  $(this).tab('show');
					})
					</script>
					<ul id=\"myTab\" class=\"nav nav-tabs\">
						<li class=\"active\"><a href=\"#blog\" data-toggle=\"tab\">Manage Blogs</a></li>
						<li><a href=\"#ticket\" data-toggle=\"tab\">Tickets</a></li>
					</ul>

					<div id=\"myTabContent\" class=\"tab-content\">
					<div class=\"tab-pane fade in active\" id=\"blog\">
					<br/>
					Welcome to the GM Blog section. You can write, edit, and delete <b>your</b> blogs, but you can't modify anyone else's blogs. Please select an option below.
					<hr/>
					  <a href=\"?base=gmcp&amp;page=manblog&amp;action=add\" class=\"btn btn-primary\">Add</a>
					  <a href=\"?base=gmcp&amp;page=manblog&amp;action=edit\" class=\"btn btn-info\">Edit</a>
					  <a href=\"?base=gmcp&amp;page=manblog&amp;action=del\" class=\"btn btn-default\">Delete</a>
					<hr/>
					</div>
					<div class=\"tab-pane fade\" id=\"ticket\">
					<br/>
					".unSolved("ticket")."
					</div>
					</div>";
			}elseif($gmcp == "manblog"){
				include('sources/gmcp/manage-blog.php');
			}elseif($gmcp == "ticket"){
				include('sources/gmcp/ticket.php');
			}
		}else{
			header("Location: ?base=gmcp");
		}
	}else{
		redirect("?base");
	}
}else{
	redirect("?base");
}
?>