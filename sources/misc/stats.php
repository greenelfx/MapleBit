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

$char = $_GET['char'];
include("../../config/database.php");
if(strlen($char) > 12 || 4 > strlen($char))
	die("Hacking Attempt Found!");
$result = mysql_query("SELECT * FROM characters WHERE name='".$char."'")
or die(mysql_error());
$row = mysql_fetch_array($result);
if(!$row)
die("Character not found");

$level = $row['level'];
$exp = $row['exp'];
$str = $row['str'];
$dex = $row['dex'];
$luk = $row['luk'];
$int = $row['int'];
$maxhp = $row['maxhp'];
$maxmp = $row['maxmp'];
$meso = $row['meso'];

echo " <td><b>Level:</b> ".$level." <br /><br /><b>STR:</b> ".$str." <br /><b>DEX:</b> ".$dex."<br /><b>LUK:</b> ".$luk."<br /><b>INT:</b> ".$int." <br /><br /><b>HP:</b> ".$maxhp."<br /><b>MP:</b> ".$maxmp." <br /><br /><b>Meso:</b> ".$meso." 
</td>  ";
?>