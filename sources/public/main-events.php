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
<a href='?cype=main&amp;page=events'><h4>Events &raquo;</h4></a><hr/>";
	$i = 0;
	$ge = $mysqli->query("SELECT * FROM `cype_events` ORDER BY `id` DESC LIMIT 4") or die(mysql_error());
	while($e = $ge->fetch_assoc()){
		$gc = $mysqli->query("SELECT * FROM `cype_ecomments` WHERE `eid`='".$e['id']."' ORDER BY `id` ASC") or die(mysql_error());
		$cc = $gc->num_rows;
		$title = $e['title'];
		$maxlength = 33;
		echo "
			<img src=\"assets/img/news/".$e['type'].".gif\" class=\"absmiddle\" alt='".$e['type']."' />
			[".$e['date']."]
			<a href=\"?cype=main&amp;page=events&amp;id=".$e['id']."\">";
		if(strlen($title) > $maxlength){
			echo stripslashes(shortTitle($title));
		}else{
			echo stripslashes($title);
		}
		echo "
			</a>
			<span class=\"commentbubble\">
			<b>".$cc."</b> <img src=\"assets/img/news/comment.png\" alt='Comment' /></span><br/>";
		$i++;
}
		if($i == 0) {
			echo "No events to display right now!";
		}
echo "</div>";
?>