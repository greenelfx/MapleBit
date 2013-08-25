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

if($_SESSION['admin']){
	echo "
		<legend>
			<b>Database Information Editor</b>
		</legend>
	";
	if(isset($_POST['Submit'])){
		$open = fopen("config/database.php","w+");
		$text = $_POST['update'];
		fwrite($open, $text);
		fclose($open);
		echo "File updated.<br />"; 
	}else{
		$file = file('assets/config/database.php');
		echo "
		<form action=\"".isset($PHP_SELF)."\" method=\"post\">
		";
		echo "
			<textarea name=\"update\" style=\"width:98%;height:600px;\">
		";
		foreach($file as $text) {
			echo $text;
		} 
		echo "
			</textarea>
		";
		echo "
		<div class=\"alert alert-error\"><b>Be Careful!</b> Once you click Update, your changes will be made.</div>
			<center>
				<input name=\"Submit\" type=\"submit\" value=\"Update\" class=\"btn btn-inverse btn-large\"/>\n
			</center>
		</form>
		";
	}
}else{
	include("sources/public/accessdenied.php");
}
?>