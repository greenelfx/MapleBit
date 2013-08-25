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

// Original Author: Darkmagic
// Modified by: Cype Developments

$getjob = mysql_real_escape_string(@$_GET['job']);
if(@$getjob != NULL) {
	$cygnus = '';
	if (isset($_GET['ck'])) {
		$cygnus = " AND characters.job >= '1000'";
	}
	$show = $cygnus." AND characters.job LIKE '".$getjob."%'";
} else {
	$show = "";
}
$start = mysql_real_escape_string(@$_GET['start']);
if(@$start) {
	$start = $start;
} else {
	$start = 0;
}
$search = @$_GET['search'];
if(isset($search)) {
$search = mysql_real_escape_string(@$_POST['search']);
	$csearch = " AND characters.name LIKE '".$search."%'";
} else {
	$csearch = "";
}

$order = mysql_real_escape_string(@$_GET['order']);
if(@$order) {
	$order = $order." DESC,";
} else {
		$order = "";
		$result2 = mysql_query("SELECT level, exp, characters.name name, meso, job, fame, logo, logoColor, guilds.name guildname, logoBG, logoBGColor, COUNT(eventstats.characterid) wins FROM accounts, characters LEFT JOIN guilds ON guilds.guildid = characters.guildid LEFT JOIN eventstats ON characters.id=eventstats.characterid WHERE characters.gm < '$gmlevel' AND accountid = accounts.id AND banned = 0 ".$show." ".$csearch." GROUP BY characters.id DESC ORDER BY $order level DESC, exp DESC") or die("IT IS LINE ". __LINE__ . "<br />" . mysql_error());
		$num_players=mysql_num_rows($result2);
}
if(isset($search)){
	$row_number = 0;
	$int = 0;
	while(($row = mysql_fetch_array( $result2 )) && !$row_number){
		if(strtolower($row['name']) == strtolower($search)){
			$row_number = $int;
		}
		$int++;
	}
	if($row_number){
		$start = $row_number - ($row_number % 5);
	}
}

$result = mysql_query("SELECT level, exp, characters.name name, meso, job, fame, logo, logoColor, guilds.name guildname, logoBG, logoBGColor, COUNT(eventstats.characterid) wins FROM accounts, characters LEFT JOIN guilds ON guilds.guildid = characters.guildid LEFT JOIN eventstats ON characters.id=eventstats.characterid WHERE characters.gm < '$gmlevel' AND accountid = accounts.id AND banned = 0 ".$show."".$csearch." GROUP BY characters.id DESC ORDER BY $order level DESC, exp DESC LIMIT $start, 5") or die("IT IS LINE ". __LINE__ . "<br />" . mysql_error());
		echo "
						
							<form id='search_form' method='post' action='?cype=main&page=ranking&order=".isset($_POST['order'])."&job=".isset($_POST['job'])."&search'>
								<input type='text' name='search' id='s' onmousedown=\"if(this.value=='Character Search'){this.value='';}\" onblur=\"if(this.value==''){this.value='Character Search'}\" value='' class='swap_value' />
								<input type='hidden' name='start' value='".isset($_POST['start'])."' />
								<input type='hidden' name='order' value='".isset($_POST['order'])."' />
								<input type='hidden' name='job' value='".isset($_POST['job'])."' />
								<input type='submit' value='Search' id='go' alt='Search' title='Search' />
							</form>
						</td>
					</tr>
				</table>
			</td>
		</tr>";

	echo "
		<tr>
			<td>
				<table style='border:.3em solid #505151;' border='0' width='100%' cellspacing='0'>";
	echo "
					<tr style='height: 40px;' align ='center' valign='middle'>
						<td class='ranktitle'>
							<b>Rank</b>
						</td>
						<td class='ranktitle'>
							<b>Character</b>
						</td>
						<td class='ranktitle'>
							<b>Name</b>
						</td>
						<td class='ranktitle'>
							<b>Fame</b>
						</td>
						<td class='ranktitle'>
							<b>FoJ</b>
						</td>
						<td class='ranktitle'>
							<b>Job</b>
						</td>
						<td style='background:#727575; color: #FFF; padding:10px;'>
							<b>Level</b>
						</td>
					</tr>";
		
	$ranking=$start;
	$backcolor="ffffff";

	while($row = mysql_fetch_array( $result )) {

		if(@$backcolor2 == "ffffff")
			$backcolor2 = "f3f3f3";
			else
				$backcolor2 = "ffffff";
		if(@$row_number == $ranking && $search){
			$backcolor = "cacaca";
		}
	else{
		$backcolor=$backcolor2;
    }
	$ranking++;
	
echo "
					<tr>
						<td style='border-bottom:1px solid #e3e3e3;' align='center' bgcolor='#".$backcolor."'>
							<b>$ranking</b>
						</td>
						<td style='border-bottom:1px solid #e3e3e3;' align='center' bgcolor='#".$backcolor."'>
							<b><img src='GD/?n=".$row['name']."' alt='".$row['name']."' /></b>
						</td>
						<td style='border-bottom:1px solid #e3e3e3;' align='center' bgcolor='#".$backcolor."'>
							<a href='#".$row['name']."'>
								<b>".$row['name']."</b>
							</a>
							<br />";
if($row['guildname'])
	echo "
							<b>".$row['guildname']."</b>
							<br />
								<img src='GD/guild.php?back=".$row['logoBG']."&amp;backcolor=".$row['logoBGColor']."&amp;top=".$row['logo']."&amp;topcolor=".$row['logoColor']."' alt='".$row['guildname']."' />";
	else
		echo "
							<b>
								<s>Guildless</s>
							</b>";
		echo "		
						</td>
						<td style='border-bottom:1px solid #e3e3e3;' align='center' bgcolor='#".$backcolor."'>
							<b>".$row['fame']."</b>
						</td>
						<td style='border-bottom:1px solid #e3e3e3;' align='center' bgcolor='#".$backcolor."'>
							<b>".$row['wins']."</b>
						</td>
						<td style='border-bottom:1px solid #e3e3e3;' align='center' bgcolor='#".$backcolor."'>
							<b>";
				if($row['job'] == 0)
					echo "
							<img src='images/class/job_beginner.gif' alt='Beginner' />";
				if($row['job'] >= 100 && $row['job'] < 200)
					echo "
							<img src='images/class/job_warrior.gif' alt='Warrior' />";
				if($row['job'] >= 200 && $row['job'] < 300)
					echo "
							<img src='images/class/job_magician.gif' alt='Magician' />";
				if($row['job'] >= 300 && $row['job'] < 400)
					echo "
							<img src='images/class/job_bowman.gif' alt='Bowman' />";
				if($row['job'] >= 400 && $row['job'] < 500)
					echo "
							<img src='images/class/job_thief.gif' alt='Theif' />";
				if($row['job'] >= 500 && $row['job'] < 600)
					echo "
							<img src='images/class/job_pirate.gif' alt='Pirate' />"; 
				if($row['job'] >= 900 && $row['job'] < 1000)
					echo "
							<img src='images/class/job_gm.gif' alt='GM' />";
					echo "
							<br />";
				if ($row['job']=="000")
					echo "Beginner";
				if ($row['job']=="100")
					echo "Warrior";
				if ($row['job']=="110")
					echo "Fighter";
				if ($row['job']=="120")
					echo "Page";
				if ($row['job']=="130")
					echo "Spearman";
				if ($row['job']=="111")
					echo "Crusader";
				if ($row['job']=="121")
					echo "White Knight";
				if ($row['job']=="131")
					echo "Dragon Knight";
				if ($row['job']=="112")
					echo "Hero";
				if ($row['job']=="122")
					echo "Paladin";
				if ($row['job']=="132")
					echo "Dark Knight";
				if ($row['job']=="200")
					echo "Magician";
				if ($row['job']=="210")
					echo "Wizard";
				if ($row['job']=="220")
					echo "Wizard";
				if ($row['job']=="230")
					echo "Cleric";
				if ($row['job']=="211")
					echo "Mage";
				if ($row['job']=="221")
					echo "Mage";
				if ($row['job']=="231")
					echo "Priest";
				if ($row['job']=="212")
					echo "Arch Mage";
				if ($row['job']=="222")
					echo "Arch Mage";
				if ($row['job']=="232")
					echo "Bishop";
				if ($row['job']=="300")
					echo "Bowman";
				if ($row['job']=="310")
					echo "Hunter";
				if ($row['job']=="320")
					echo "Crossbowman";
				if ($row['job']=="311")
					echo "Ranger";
				if ($row['job']=="321")
					echo "Sniper";
				if ($row['job']=="312")
					echo "Bow Master";
				if ($row['job']=="322")
					echo "Crossbow Master";
				if ($row['job']=="400")
					echo "Thief";
				if ($row['job']=="410")
					echo "Assassin";
				if ($row['job']=="420")
					echo "Bandit";
				if ($row['job']=="411")
					echo "Hermit";
				if ($row['job']=="421")
					echo "Chief Bandit";
				if ($row['job']=="412")
					echo "Night Lord";
				if ($row['job']=="422")
					echo "Shadower";
				if ($row['job']=="500")
					echo "Pirate";
				if ($row['job']=="510")
					echo "Brawler";
				if ($row['job']=="520")
					echo "Gunslinger";
				if ($row['job']=="511")
					echo "Marauder";
				if ($row['job']=="521")
					echo "Buccaneer";
				if ($row['job']=="512")
					echo "Outlaw";
				if ($row['job']=="522")
					echo "Corsair";
				if ($row['job']=="900")
					echo "GMs";
				if ($row['job']=="910")
					echo "SuperGM";
echo "
						</b>
						</td>
						<td style='border-bottom:1px solid #e3e3e3;' align='center' bgcolor='#".$backcolor."'>
							<b>
								".$row['level']."
								<font size='-3' color='green'>
									<br />(".$row['exp'].")
								</font>
							</b>
						</td>
					</tr>";
	}
echo "
				</table><br />
				<table border='0' width='100%' cellspacing='0'>
					<tr valign='top'>
						<td>";
if($start)
	echo "
							<a href='?cype=main&amp;page=ranking&amp;start=". ($start - 5) ."&amp;order=". isset($_POST['order']) ."&amp;job=". isset($_POST['job']) ."'>
								<img src='$styledir/images/prev.gif' alt='Previous' style='border:0px;' />&nbsp;
							</a>";
	else
		echo "
							<img src='$styledir/images/prev.gif' alt='Previous' style='border:0px;' />&nbsp;";
if($num_players % 5)
	$num_players = $num_players - ($num_players % 5);
	else
		$num_players -= 5;
if($start <= $num_players - 5)
	echo "
							<a href='?cype=main&amp;page=ranking&amp;start=". ($start + 5) ."&amp;order=". isset($_POST['order']) ."&amp;job=". isset($_POST['job']) ."'>
								<img src='$styledir/images/next.gif' alt='Next' style='border:0px;' />
							</a>";
	else
		echo "
							<img src='$styledir/images/next.gif' alt='Next' style='border:0px;' />";
	echo "
						</td>
						<td class='regtext' align='center'>
							<b>
								<a href='?cype=main&amp;page=ranking&amp;order=level&amp;job=". isset($_POST['job']) ."'>
									Level
								</a>
								| 
								<a href='?cype=main&amp;page=ranking&amp;order=fame&amp;job=".isset($_POST['fame'])."'>
									Fame
								</a>
								 | 
								<a href='?cype=main&amp;page=ranking&amp;order=wins&amp;job=". isset($_POST['job']) ."'>
									FoJ Wins
								</a>
							</b>
						</td>
						<td align='right'>
							<b>Page: </b>
							<select id=\"p_op\" onchange=\"document.location.href='?cype=main&amp;page=ranking&amp;start='+document.getElementById('p_op').value+'&amp;order=". isset($_POST['order'])."&amp;job=".isset($_POST['job'])."';\">";
		for( $int = 0; $int <= floor($num_players / 5); $int++){
			if( $start == ($int * 5))
				echo "
								<option selected='selected' value='". ($int * 5) ."'>".($int + 1)."</option>\n";
				else
					echo "
								<option value='". ($int * 5) ."'>".($int + 1)."</option>\n";
		}

		echo "
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class=\"tdbox\">
				<table width='100%' cellspacing='0' border='0'>
					<tr align='center'>
						<td>
							<a href='?cype=main&amp;page=ranking&amp;order=". $_POST['order'] ."'>
								<img src='images/class/job_all.gif' alt='All' />
							</a>
						</td>
						<td>
							<a href='?cype=main&amp;page=ranking&amp;order=". $_POST['order'] ."&amp;job=0'>
								<img src='images/class/job_beginner.gif' alt='Beginner' />
							</a>
						</td>
						<td>
							<a href='?cype=main&amp;page=ranking&amp;order=". $_POST['order'] ."&amp;job=1'>
								<img src='images/class/job_warrior.gif' alt='Warrior' />
							</a>
						</td>
						<td>
							<a href='?cype=main&amp;page=ranking&amp;order=". $_POST['order'] ."&amp;job=2'>
								<img src='images/class/job_magician.gif' alt='Magician' />
							</a>
						</td>
						<td>
							<a href='?cype=main&amp;page=ranking&amp;order=". $_POST['order'] ."&amp;job=3'>
								<img src='images/class/job_bowman.gif' alt='Bowman' />
							</a>
						</td>
						<td>
							<a href='?cype=main&amp;page=ranking&amp;order=". $_POST['order'] ."&amp;job=4'>
								<img src='images/class/job_thief.gif' alt='Thief' />
							</a>
						</td>
						<td>
							<a href='?cype=main&amp;page=ranking&amp;order=". $_POST['order'] ."&amp;job=5'>
								<img src='images/class/job_pirate.gif' alt='Pirate' />
							</a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>";

?>