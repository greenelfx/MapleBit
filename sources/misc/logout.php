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

if($_SESSION['id']){
	$logouttime = 100;
	$date = date();
	$timenow = time();
	$loggedtime = $timenow - $logouttime;
	$query = $mysqli->query("UPDATE `accounts` SET `sitelogged` = '".$loggedtime."' WHERE `id`='".$_SESSION['id']."'") or die(mysql_error());
	session_destroy();
	include('sources/public/main.php');
	echo "<meta http-equiv=refresh content=\"0; url=?cype=main\">";
}else{
	echo "<meta http-equiv=refresh content=\"0; url=?cype=main\">";
}
?>