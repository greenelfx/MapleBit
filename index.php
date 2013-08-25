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

# Begin Session
session_start();

# Disable Notices

# Is Cype installed?
if(!file_exists('assets/config/install/installdone.txt')){
	header("Location: assets/config/install/install.php");
	exit;
} else {
	# Stable Version Number
	define("CYPE_VERSION", "1.03");

	# Import Database Driver
	require_once("assets/config/database.php");

	# Import Essential Files
	require_once("assets/config/properties.php");
	require_once("assets/config/afuncs.php");

	# Define $getcype variable
	$getcype = isset($_GET['cype']) ? $_GET['cype'] : "";

	switch($getcype){
		case NULL:
			header('Location: ?cype=main');
			break;
		case "main":
			include("sources/structure/header.php");
			include("sources/public/main.php");
			include("sources/structure/footer.php");
			break;
		case "ucp":
			include("sources/structure/header.php");
			include("sources/ucp/main.php");
			include("sources/structure/footer.php");
			break;
		case "admin":
		    //include("sources/structure/header.php");
			include("sources/admin/main.php");
			//include("sources/structure/footer.php");
			break;
		case "gmcp":
			include("sources/structure/header.php");
			include("sources/gmcp/main.php");
			include("sources/structure/footer.php");
			break;
		case "bbs":
			include("sources/structure/header.php");
			include("sources/bbs/main.php");
			include("sources/structure/footer.php");
			break;
		case "misc":
			include("sources/misc/main.php");
			break;
		case "style":
			include("sources/structure/header.php");
			include("sources/public/styles.php");
			include("sources/structure/footer.php");
			break;
		case "cypedl":
			include("sources/misc/phpdownload.php");
			break;
		default:
			include("sources/structure/header.php");
			include("sources/public/main.php");
			include("sources/structure/footer.php");
			break;
	}
}

$mysqli->close();
?>