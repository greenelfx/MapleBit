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

if(isset($_SESSION['id'])){
	if(isset($_SESSION['admin'])){
		$query = $mysqli->query("SELECT * FROM cype_pages");
		if(isset($_GET['p'])){
			$p = $_GET['p'];
		}else{
			$p = "";
		}
		
		if(isset($_GET['id'])){
			$id = $_GET['id'];
		}else{
			$id = "";
		}
		
		if($p == ""){
			if($nquery = $query->num_rows > 0){
				while($a = $query->fetch_assoc()){
					echo "
					<legend>Page Management</legend>
						<b>#".$a['id']."</b>&nbsp;&nbsp;
						<b><a href='?cype=".$a['name']."'>".$a['dir'].$a['name'].".php</a></b>&nbsp;
						<div class=\"btn-group\" style=\"float:right;\">
							<a href='?cype=admin&page=pages&p=".md5('edit')."&id=".base64_encode($a['id'])."' class=\"btn\">Edit</a>&nbsp;
							<a href='?cype=admin&page=pages&p=".md5('delete')."&id=".base64_encode($a['id'])."' class=\"btn btn-inverse\">Delete</a>
						</div><br/><br/>
					";
				}
			}else{
				echo "<legend>Add Page</legend><div class=\"alert alert-error\">You don't have any custom pages.</div>";
			}
			echo "
				<a href='?cype=admin&page=pages&p=".md5('add')."' class=\"btn btn-primary\">Add Page &raquo;</a>
			";
		}elseif($p == md5('edit')){
			if(!isset($_POST['edit'])){
				$equery = $mysqli->query("SELECT * FROM cype_pages WHERE id=".base64_decode($id)."");
				$f = $equery->fetch_assoc();
				if($f['header'] == 1){
					$hcb = '<label class="checkbox"><input type="checkbox" name="header" checked="checked">Header</input></label>';
				}else{
					$hcb = '<label class="checkbox"><input type="checkbox" name="header">Header</input></label>';
				}
				if($f['footer'] == 1){
					$fcb = '<label class="checkbox"><input type="checkbox" name="footer" checked="checked">Footer</input></label>';
				}else{
					$fcb = '<label class="checkbox"><input type="checkbox" name="footer">Footer</input></label>';
				}
				$gdir = $f['dir'];
				switch($gdir){
				case 'sources/public/':
					$dir = '<label class="radio"><input type="radio" name="dir" value="0" checked="checked">sources/public/</input></label><label class="radio"><input type="radio" name="dir" value="1">sources/ucp/</input></label><label class="radio"><input type="radio" name="dir" value="2">sources/misc/</input></label><label class="radio"><input type="radio" name="dir" value="3">sources/gmcp/</input></label><label class="radio"><input type="radio" name="dir" value="4">sources/admin/</input></label>';
					break;
				case 'sources/ucp/':
					$dir = '<label class="radio"><input type="radio" name="dir" value="0">sources/public/</input></label><label class="radio"><input type="radio" name="dir" value="1" checked="checked">sources/ucp/</input></label><label class="radio"><input type="radio" name="dir" value="2">sources/misc/</input></label><label class="radio"><input type="radio" name="dir" value="3">sources/gmcp/</input></label><label class="radio"><input type="radio" name="dir" value="4">sources/admin/</input></label>';
					break;
				case 'sources/misc/':
					$dir = '<label class="radio"><input type="radio" name="dir" value="0">sources/public/</input></label><label class="radio"><input type="radio" name="dir" value="1">sources/ucp/</input></label><label class="radio"><input type="radio" name="dir" value="2" checked="checked">sources/misc/</input></label><label class="radio"><input type="radio" name="dir" value="3">sources/gmcp/</input></label><label class="radio"><input type="radio" name="dir" value="4">sources/admin/</input></label>';
					break;
				case 'sources/gmcp/':
					$dir = '<label class="radio"><input type="radio" name="dir" value="0">sources/public/</input></label><label class="radio"><input type="radio" name="dir" value="1">sources/ucp/</input></label><label class="radio"><input type="radio" name="dir" value="2">sources/misc/</input></label><label class="radio"><input type="radio" name="dir" value="3" checked="checked">sources/gmcp/</input></label><label class="radio"><input type="radio" name="dir" value="4">sources/admin/</input></label>';
					break;
				case 'sources/admin/':
					$dir = '<label class="radio"><input type="radio" name="dir" value="0">sources/public/</input></label><label class="radio"><input type="radio" name="dir" value="1">sources/ucp/</input></label><label class="radio"><input type="radio" name="dir" value="2">sources/misc/</input></label><label class="radio"><input type="radio" name="dir" value="3">sources/gmcp/</input></label><label class="radio"><input type="radio" name="dir" value="4" checked="checked">sources/admin/</input></label>';
					break;
				default:
					$dir = '<label class="radio"><input type="radio" name="dir" value="0" checked="checked">sources/public/</input></label><label class="radio"><input type="radio" name="dir" value="1">sources/ucp/</input></label><label class="radio"><input type="radio" name="dir" value="2">sources/misc/</input></label><label class="radio"><input type="radio" name="dir" value="3">sources/gmcp/</input></label><label class="radio"><input type="radio" name="dir" value="4">sources/admin/</input></label>';
					break;
					
				}
				echo '
					<legend>Editing '.$f['name'].'</legend>
						<form action="" method="post">
							<b>Name:</b><br/>
							<input type="text" name="name" value="'.$f['name'].'"/><br/><br/>
							<b>Directory:</b><br/>
							'.$dir.'<br/>
							'.$hcb.'
							'.$fcb.'
							<br/><input type="submit" name="edit" value="Edit Page &raquo;" class="btn btn-primary"/>
						</form>
				';
			}else{
				$name = $_POST['name'];
				$gndir = $_POST['dir'];
				switch($gndir){
				case 0:
					$ndir = "sources/public/";
					break;
				case 1:
					$ndir = "sources/ucp/";
					break;
				case 2:
					$ndir = "sources/misc/";
					break;
				case 3:
					$ndir = "sources/gmcp/";
					break;
				case 4:
					$ndir = "sources/admin/";
					break;
				case 5:
					$ndir = $_POST['custom'];
					break;
				}
				if(isset($_POST['header'])){
					$header = "1";
				}else{
					$header = "0";
				}
				if(isset($_POST['footer'])){
					$footer = "1";
				}else{
					$footer = "0";
				}
				
				if(empty($name)){
					echo "You can't have an empty name.<br /><br /><a href='javascript:history.go(-1);'>Try Again</a>";
				}else{
					$mysqli->query("UPDATE `cype_pages` SET `name`='".$name."', `dir`='".$ndir."', `header`='".$header."', `footer`='".$footer."' WHERE `id`='".base64_decode($id)."'") or die(mysql_error());
					echo "<legend>Edit Complete</legend><a href='?cype=admin&page=pages'>Back to <b>Site Pages</b></a>";
				}
			}
		}elseif($p == md5('delete')){
			if(!isset($_POST['delete'])){
				echo '
				<legend>Confirm Deletion</legend>
					<form action="" method="post">
					<label class="checkbox">
						<input type="checkbox" name="confirm">Continue</input><br />
					</label>
						<input type="submit" name="delete" value="Delete Page &raquo;" class="btn btn-inverse"/>
					</form>
				';
			}else{
				if(isset($_POST['confirm'])){
					$confirm = 1;
				}else{
					$confirm = 0;
				}
				
				if($confirm == 1){
					$mysqli->query("DELETE FROM cype_pages WHERE id=".base64_decode($id)."") or die(mysql_error());
					echo "<div class=\"alert alert-success\">Page deleted. <a href='?cype=admin&page=pages'>Back to <b>Site Pages</b></a></div>";
				}else{
					echo "<div class=\"alert\">You did not confirm the delete. <a href='javascript:history.go(-1);'>Try Again</a></div>";
				}
			}
		}elseif($p == md5('add')){
		if(!isset($_POST['add'])){
			echo '
			<legend>Add Page</legend>
			<form action="" method="post" name="addform">
			<b>Name:</b></br>
			<input type="text" name="name" placeholder="Page Name" required/><br /><br/>
			<b>Directory:</b>
			<label class="radio">
				<input type="radio" name="dir" value="0" checked>sources/public/</input><br/>
			</label>
			<label class="radio">
				<input type="radio" name="dir" value="1">sources/ucp/</input><br/>
			</label>
			<label class="radio">
				<input type="radio" name="dir" value="2">sources/misc/</input><br/>
			</label>
			<label class="radio">
				<input type="radio" name="dir" value="3">sources/gmcp/</input><br />
			</label>
			<label class="radio">
				<input type="radio" name="dir" value="4">sources/admin/</input><br />
			</label>
			<br/>
			<label class="checkbox">
				<input type="checkbox" name="header">Header</input>
			</label>
			<label class="checkbox">
				<input type="checkbox" name="footer">Footer</input>
			</label>
			<br/>
				<input type="submit" name="add" value="Add Page &raquo;" class="btn btn-primary"/>
			</form>
				';
			}else{
				$name = $_POST['name'];
				$gdir = $_POST['dir'];
				switch($gdir){
				case 0:
					$dir = "sources/public/";
					break;
				case 1:
					$dir = "sources/ucp/";
					break;
				case 2:
					$dir = "sources/misc/";
					break;
				case 3:
					$dir = "sources/gmcp/";
					break;
				case 4:
					$dir = "sources/admin/";
					break;
				}
				if(isset($_POST['header'])){
					$header = "1";
				}else{
					$header = "0";
				}
				if(isset($_POST['footer'])){
					$footer = "1";
				}else{
					$footer = "0";
				}
				
				if(empty($name)){
					echo "<div class=\"alert alert-error\">You need to have a page name.<br /><br /><a href='javascript:history.go(-1);'>Try Again</a></div>";
				}elseif(empty($dir)){
					echo "<div class=\"alert alert-error\">You need to have a page directory.<br /><br /><a href='javascript:history.go(-1);'>Try Again</a></div>";
				}else{
					$mysqli->query("INSERT INTO `cype_pages` (`name`, `dir`, `header`, `footer`) VALUES ('$name', '$dir', '$header', '$footer')") or die(mysql_error());
					echo "<div class=\"alert alert-success\">Page added. <a href='?cype=admin&page=pages'>Back to <b>Site Pages</b></a></div>";
				}
			}
		}
	}
}

?>