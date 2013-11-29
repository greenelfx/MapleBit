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
<a href='?cype=main&amp;page=news'><h4>News &raquo;</h4></a><hr/>";
	$i = 0;
	$gn = $mysqli->query("SELECT * FROM ".$prefix."news ORDER BY id DESC LIMIT 4") or die();
	while($n = $gn->fetch_assoc()){
		$gc =$mysqli->query("SELECT * FROM ".$prefix."ncomments WHERE nid='".$n['id']."' ORDER BY id ASC") or die();
		$cc = $gc->num_rows;
		$title = $n['title'];
		$maxlength = 33;
		echo "
			<img src=\"assets/img/news/".$n['type'].".gif\" alt='".$n['type']."' class='text-left' />
			[".$n['date']."]
			<a href=\"?cype=main&amp;page=news&amp;id=".$n['id']."\">";
		if(strlen($title) > $maxlength){
			echo stripslashes(shortTitle($title));
		}else{
			echo stripslashes($title);
		}
		echo "
			</a>
			<span class=\"commentbubble\">
			<b>".$cc."</b>
			<img src=\"assets/img/news/comment.png\" alt=\"Comment\"/></span><br/>";
		$i++;
}

		if($i == 0) {
			echo "No news to display right now!";
		}

echo "</div>";
?>