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

if(isset($_GET['page'])){
	$main = $_GET['page'];
}else{
	$main = "";
}
	if($getcype == "main"){
		if($main == ""){
			echo "<div class=\"row\">";
			include ("sources/public/main-news.php");
			include ("sources/public/main-events.php");
			echo "</div>";
			echo "<div class=\"row\" style=\"margin-top:30px;\">";
			include ("sources/public/main-rank.php");
			include ("sources/public/main-gm.php");
			echo "</div><br/>";
		}elseif($main == "download"){
			include('sources/public/download.php');
		}elseif($main == "events"){
			include('sources/public/events.php');
		}elseif($main == "guildlist"){
			include('sources/public/guildlist.php');
		}elseif($main == "gmblog"){
			include('sources/public/gmblog.php');
		}elseif($main == "members"){
			include('sources/public/members.php');
		}elseif($main == "news"){
			include('sources/public/news.php');
		}elseif($main == "events"){
			include('sources/public/events.php');
		}elseif($main == "ranking"){
			include('sources/public/ranking.php');
		}elseif($main == "register"){
			include('sources/public/register.php');
		}elseif($main == "vote"){
			include('sources/public/vote.php');
		}
		else {
		header("Location: ?cype=main");
		}
	}else{
		header("Location: ?cype=main");
	}
?>