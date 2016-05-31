<?php
if(basename($_SERVER["PHP_SELF"]) == "nxpacks.php") {
	die("403 - Access Forbidden");
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

echo "<h2 class=\"text-left\">Configure NX Packages</h2><hr/>";

if($action === "edit") {
	if(!empty($id) || is_numeric($id)) {
		if(!isset($_POST['edit'])) {
			$queryPack = $mysqli->query("SELECT * FROM ".$prefix."buynx WHERE id = '".$id."'") or die();
			if($queryPack->num_rows) {
				$pack = $queryPack->fetch_assoc();
				echo "
					<form name=\"editpacks\" method=\"post\">
					<div class=\"form-group\">
						<label for=\"inputMeso\">Cost in Mesos</label>
						<input type=\"text\" name=\"meso\" class=\"form-control\" id=\"inputMeso\" value=".$pack['meso']." required/>
					</div>
					<div class=\"form-group\">
						<label for=\"inputNX\">NX</label>
						<input type=\"text\" name=\"nx\" class=\"form-control\" id=\"inputNX\" value=".$pack['nx']." required/>
					</div>
					<input type=\"submit\" name=\"edit\" value=\"Edit &raquo;\" class=\"btn btn-primary\"/>
					</form>
				";
			}
			else {
				echo "Invalid Package ID";
			}
		}
		else {
			$meso = $_POST['meso'];
			$nx = $_POST['nx'];
			if(empty($meso)) {
				echo "<div class=\"alert alert-error\">You need to enter a  meso amount.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
			}
			elseif(empty($nx)) {
				echo "<div class=\"alert alert-error\">You need to enter an NX amount.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
			}
			elseif(!is_numeric($meso) || (is_numeric($meso) && $meso < 0)) {
				echo "<div class=\"alert alert-error\">You can only enter positive numbers.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
			}
			elseif(!is_numeric($nx) || (is_numeric($nx) && $nx < 0)) {
				echo "<div class=\"alert alert-error\">You can only enter positive numbers.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
			}
			else {
				$mysqli->query("UPDATE `".$prefix."buynx` SET `meso` = '".$meso."', `nx` = '".$nx."' WHERE `id` = '".$id."'");
				echo '<div class="alert alert-success"><b>Package '.$id.'</b> edited.</div><a href="?base=admin&page=nxpacks">&laquo; NX Packages</a>';
			}
		}
	}
	else {
		echo 'Invalid Package ID';
	}
}
elseif($action === "delete") {
	$pack = $mysqli->query("SELECT COUNT(*) FROM ".$prefix."buynx WHERE id = '".$id."'");
	if(!empty($id) && $pack->fetch_row()[0]) {
		$mysqli->query("DELETE FROM ".$prefix."buynx WHERE id = '".$id."'");
		echo "<div class=\"alert alert-success\"><b>Package ".$id."</b> deleted.</div><a href=\"?base=admin&page=nxpacks\">&laquo; NX Packages</a>";
	}
	else {
		echo 'Invalid Package ID';
	}
}
elseif($action === "add") {
	if(!isset($_POST['add'])) {
		echo "
			<form name=\"addpack\" method=\"post\">
			<div class=\"form-group\">
				<label for=\"inputMeso\">Cost in Mesos</label>
				<input type=\"text\" name=\"meso\" class=\"form-control\" id=\"inputMeso\" placeholder=\"20000\" required/>
			</div>
			<div class=\"form-group\">
				<label for=\"inputNX\">NX</label>
				<input type=\"text\" name=\"nx\" class=\"form-control\" id=\"inputNX\" placeholder=\"1000\" required/>
			</div>
				<input type=\"submit\" name=\"add\" value=\"Add &raquo;\" class=\"btn btn-primary\"/>
			</form>
		";
	}
	else {
		$meso = isset($_POST['meso']) ? $_POST['meso'] : '';
		$nx = isset($_POST['nx']) ? $_POST['nx'] : '';
		if(empty($meso)) {
			echo "<div class=\"alert alert-danger\">You need to have a value in mesos.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
		}
		elseif(empty($nx)) {
			echo "<div class=\"alert alert-danger\">You need to have a value in nx.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
		}
		elseif(!is_numeric($meso) || (is_numeric($meso) && $meso < 0)) {
			echo "<div class=\"alert alert-danger\">You can only use positive numbers.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>";
		}
		elseif(!is_numeric($nx) || (is_numeric($nx) && $nx < 0)) {
			echo '<div class="alert alert-danger">You can only use positive numbers.</div><hr/><button onclick=\"goBack()\" class=\"btn btn-primary\">&laquo; Go Back</button>';
		}
		else {
			$mysqli->query("INSERT INTO ".$prefix."buynx (meso, nx) VALUES ('".$meso."', '".$nx."')") or die();
			echo '<div class="alert alert-success">Package added.<hr/><a href="?base=admin&page=nxpacks">Add More NX Packages</a></div>';
		}
	}
}
else {
	$fetchPacks = $mysqli->query("SELECT * FROM ".$prefix."buynx");
	$countfetchPacks = $fetchPacks->num_rows;
	if($countfetchPacks > 0) {
		while($getPacks = $fetchPacks->fetch_assoc()) {
			echo "<b>Package ".$getPacks['id'].":</b> ".number_format($getPacks['nx'])." NX for ".number_format($getPacks['meso'])." Mesos <div style=\"float:right;\"><a href=\"?base=admin&page=nxpacks&action=edit&id=".$getPacks['id']."\" class=\"btn btn-primary\">Edit</a>&nbsp;&nbsp;<a href=\"?base=admin&page=nxpacks&action=delete&id=".$getPacks['id']."\" class=\"btn btn-info\">Delete</a></div><br/><br/>";
		}
	}
	else {
		echo "<div class=\"alert alert-danger\">No NX Packages have been added!</div>";
	}
	echo '<hr/><a href="?base=admin&page=nxpacks&action=add" class="btn btn-primary">Add Package &raquo;</a>';	
}