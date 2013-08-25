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
$bar = countOnline();
echo "
<h3>Server Info</h3>";
	$accounts = $mysqli->query("SELECT * FROM accounts");
		$saccounts = $accounts->num_rows;
	$characters = $mysqli->query("SELECT * FROM characters");
		$scharacters = $characters->num_rows;
	$online = $mysqli->query("SELECT * FROM accounts where loggedin = 2");
		$sonline = $online->num_rows;
				echo "	
	Players Online: <b>".$sonline."</b><br/>
	Accounts: <b>".$saccounts."</b><br/>
	Characters: <b>".$scharacters."</b><br/><br/>
<div class=\"progress progress-striped progress-success active\">
  <div class=\"bar\" style=\"width:".$bar."%\"></div>
</div>";

echo "<hr/>
Version <a href='?cype=main&amp;page=download'><b>".$version."</b></a><br/>
Experience Rate: <b>".$exprate."</b><br/>
Meso Rate: <b>".$mesorate."</b><br/>
Drop Rate: <b>".$droprate."</b><br/>
";
?>