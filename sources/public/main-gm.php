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

echo "
<div class=\"col-md-6\">
<a href='?cype=main&amp;page=gmblog'><h4>GM Blog &raquo;</h4></a><hr/>";
	$i = 0;
	$gn = $mysqli->query("SELECT * FROM `cype_gmblog` ORDER BY `id` DESC LIMIT 4") or die(mysql_error());
	while($n = $gn->fetch_assoc()){
		$title = $n['title'];
		$maxlength = 33;
		echo "
			[".$n['date']."]
			<a href=\"?cype=main&amp;page=gmblog&amp;id=".$n['id']."\">";
		if(strlen($title) > $maxlength){
			echo stripslashes(shortTitle($title));
		}else{
			echo stripslashes($title);
		}
		echo "</a><br/>";
		$i++;
}

		if($i == 0) {
			echo "Oops! No blogs to display right now!";
		}

echo "</div>";
?>