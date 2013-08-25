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

if(isset($_SESSION["admin"])){
	echo "
		<legend>".$servername." Website Activity Log</legend>
		<div style=\"border:t0; padding:6px; overflow:auto; height:400px;\">";
		include('log.php');
	echo "
		</div>
		<br/>
		<form name=\"input\" action=\"?cype=admin&amp;page=logs&clear=1\" method=\"post\">
			<input type=\"submit\" class=\"btn btn-danger\" value=\"Clear Log &raquo;\" />
		</form>
		";
	if(isset($_GET['clear'])){
		$clear = $_GET['clear'];
	}else{
		$clear = "";
	}
	if($clear == "1"){
		$myFile = "sources/admin/log.php";
		unlink($myFile);
		header("refresh: ?cype=admin&page=logs");
	}
}else{
	include('sources/public/accessdenied.php');
}
?>