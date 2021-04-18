<?php

if (basename($_SERVER['PHP_SELF']) == 'main.php') {
    exit('403 - Access Forbidden');
}

if (isset($_GET['page'])) {
    $gmcp = $_GET['page'];
}

if (isset($_SESSION['id']) && (isset($_SESSION['gm']) || isset($_SESSION['admin']))) {
    if (empty($gmcp)) {
        echo "
			<h2 class=\"text-left\">GameMaster Panel</h2><hr/>
			<ul class=\"nav nav-tabs\" id=\"gmTabs\" role=\"tablist\">
				<li class=\"nav-item\"><a href=\"#blog\" data-toggle=\"tab\" class=\"nav-link active\">Manage Blogs</a></li>
				<li class=\"nav-item\"><a href=\"?base=gmcp&amp;page=banned\" class=\"nav-link\">Banned Users</a></li>
			</ul>
			<div id=\"myTabContent\" class=\"tab-content\">
				<div class=\"tab-pane fade show active\" id=\"blog\">
				<br/>
				Welcome to the GM Blog section. You can write, edit, and delete <b>your</b> blogs, but you can't modify anyone else's blogs. Please select an option below.
				<hr/>
				<a href=\"?base=gmcp&amp;page=manblog&amp;action=add\" class=\"btn btn-primary\">Add</a>
				<a href=\"?base=gmcp&amp;page=manblog&amp;action=edit\" class=\"btn btn-info\">Edit</a>
				<a href=\"?base=gmcp&amp;page=manblog&amp;action=del\" class=\"btn btn-danger\">Delete</a>
				<hr/>
				</div>
			</div>
		";
    } elseif ($gmcp === 'manblog') {
        include 'sources/gmcp/manage-blog.php';
    } elseif ($gmcp === 'banned') {
        include 'sources/gmcp/banned.php';
    }
} else {
    redirect('?base=main');
}
