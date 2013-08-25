<?php 
/*
    Copyright (C) 2009  Josh L.
			Murad --

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

if($_SESSION['admin'] == 1)
{
	$do = isset($_GET['do']) ? $_GET['do'] : '';
	$id = isset($_GET['id']) ? $_GET['id'] : '';
	
	echo "
		<legend>Configure NX Packages</legend>
	";
	
	switch($do)
	{
		case NULL:		
			$fetchPacks = $mysqli->query("SELECT * FROM `cype_buynx`") or die('Oops, I messed up: '.mysql_error());
			while($getPacks = $fetchPacks->fetch_assoc())
			{
				echo '
					<b>Package '.$getPacks['id'].':</b> '.number_format($getPacks['nx']).' NX for '.number_format($getPacks['meso']).' Mesos <div class="btn-group" style="float:right;"><a href="?cype=admin&page=nxpacks&do=edit&id='.$getPacks['id'].'" class="btn">Edit</a><a href="?cype=admin&page=nxpacks&do=delete&id='.$getPacks['id'].'" class="btn btn-inverse">Delete</a></div><br/><br/>
				';
			}
			echo '<hr/><a href="?cype=admin&page=nxpacks&do=add" class="btn">Add Package &raquo;</a>';
			break;
		case 'edit':
			if($id != NULL)
			{
				if(!isset($_POST['edit']))
				{
					$fetchEditPacks = $mysqli->query("SELECT * FROM `cype_buynx` WHERE `id` = '".$id."'") or die();
					$getEditPacks = $fetchEditPacks->fetch_assoc();
					echo '
						<form name="editpacks" method="post" action="">
							<b>Meso:<b/><br />
							<input type="text" name="meso" value="'.$getEditPacks['meso'].'" required/><br />
							<b>NX:</b><br />
							<input type="text" name="nx" value="'.$getEditPacks['nx'].'" required/><br /><br />
							<input type="submit" name="edit" value="Edit &raquo;" class="btn btn-primary"/>
						</form>
					';
				}
				else
				{
					$meso = isset($_POST['meso']) ? $_POST['meso'] : '';
					$nx = isset($_POST['nx']) ? $_POST['nx'] : '';
					
					if(empty($meso))
					{
						echo '<div class="alert alert-error">You need to enter a  meso amount. <a href="javascript:history.go(-1);">Go Back</a></div>';
					}
					elseif(empty($nx))
					{
						echo '<div class="alert alert-error">You need to enter an NX amount. <a href="javascript:history.go(-1);">Go Back</a></div>';
					}
					elseif(!is_numeric($meso))
					{
						echo '<div class="alert alert-error">You can only enter numbers. <a href="javascript:history.go(-1);">Go Back</a></div>';
					}
					elseif(!is_numeric($nx))
					{
						echo '<div class="alert alert-error">You can only enter numbers. <a href="javascript:history.go(-1);">Go Back</a></div>';
					}
					else
					{
						$mysqli->query("UPDATE `cype_buynx` SET `meso` = '".$meso."', `nx` = '".$nx."' WHERE `id` = '".$id."'");
						echo '<div class="alert alert-success"><b>Package '.$id.'</b> edited. <a href="?cype=admin&page=nxpacks&do=">NX Packages</a></div>';
					}
				}
			}
			else
			{
				echo 'Error.';
			}
			break;
		case 'delete':
			if($id != NULL)
			{
				$mysqli->query("DELETE FROM `cype_buynx` WHERE `id` = '".$id."'") or die('Could not delete package');
				echo '<div class="alert alert-success"><b>Package '.$id.'</b> deleted. <a href="?cype=admin&page=nxpacks&do=">NX Packages</a></div>';
			}
			else
			{
				echo 'Error.';
			}
			break;
		case 'add':
			if(!isset($_POST['add']))
			{
				echo '
					<form name="addpack" method="post" action="">
						<b>Meso:</b><br />
						<input type="text" name="meso" required/><br />
						<b>NX:</b><br />
						<input type="text" name="nx" required/><br /><br />
						<input type="submit" name="add" value="Add &raquo;" class="btn btn-primary" required/>
					</form>
				';
			}
			else
			{
				$meso = isset($_POST['meso']) ? $_POST['meso'] : '';
				$nx = isset($_POST['nx']) ? $_POST['nx'] : '';
				
				if(empty($meso))
				{
					echo '<div class="alert alert-error">You need to have a value in mesos. <a href="javascript:history.go(-1);">Go Back</a></div>';
				}
				elseif(empty($nx))
				{
					echo '<div class="alert alert-error">You need to have a value in nx. <a href="javascript:history.go(-1);">Go Back</a></div>';
				}
				elseif(!is_numeric($meso))
				{
					echo '<div class="alert alert-error">You can only use numbers. <a href="javascript:history.go(-1);">Go Back</a></div>';
				}
				elseif(!is_numeric($nx))
				{
					echo '<div class="alert alert-error">You can only use numbers. <a href="javascript:history.go(-1);">Go Back</a></div>';
				}
				else
				{
					$mysqli->query("INSERT INTO `cype_buynx` (`meso`, `nx`) VALUES ('".$meso."', '".$nx."')") or die();
					echo '<div class="alert alert-success">Package added.<br />[<a href="?cype=admin&page=nxpacks&do=">NX Packages</a>]</div>';
				}
			}
			break;
	}
}
else
{
	include('sources/public/accessdenied.php');
}
?>