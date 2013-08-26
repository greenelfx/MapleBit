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
<a href='?cype=main&amp;page=events'><h4>Rankings &raquo;</h4></a><hr/>";

	$gc = $mysqli->query("SELECT * FROM `characters` WHERE `gm`='0' ORDER BY `level` DESC,`exp`") or die(mysql_error());
		$p = 0;
	while($player = $gc->fetch_assoc() and $p <=4){
		$char = $player['accountid'];
		$ban1 = $mysqli->query("SELECT banned FROM accounts WHERE id = $char");
		$ban = $ban1->fetch_assoc();
	  	if ($ban["banned"] == 1){ 
			}else{ 
			$p++;
			$jobs = array(
		   0   =>'Beginner',
		  100 =>'Warrior',
		  110 =>'Fighter',
		  120 =>'Page',
		  130 =>'Spearman',
		  111 =>'Crusader',
		  121 =>'White Knight',
		  131 =>'Dragon Knight',
		  112 =>'Hero',
		  122 =>'Paladin',
		  132 =>'Dark Knight',
		  200 =>'Magician',
		  210 =>'Fire/Poison Wizard',
		  220 =>'Ice/Lightning Wizard',
		  230 =>'Cleric',
		  211 =>'Fire/Poison Mage',
		  221 =>'Ice/Lightning Mage',
		  231 =>'Priest',
		  212 =>'Fire/Poison Arch Mage',
		  222 =>'Ice/Lightning Arch Mage',
		  232 =>'Bishop',
		  300 =>'Bowman',
		  310 =>'Hunter',
		  320 =>'Crossbowman',
		  311 =>'Ranger',
		  321 =>'Sniper',
		  312 =>'Bow Master',
		  322 =>'Crossbow Master',
		  400 =>'Thief',
		  410 =>'Assassin',
		  420 =>'Bandit',
		  411 =>'Hermit',
		  421 =>'Chief Bandit',
		  412 =>'Nights Lord',
		  422 =>'Shadower',
		  430 =>'Blade Recruit',
		  431 =>'Blade Acolyte',
		  432 =>'Blade Specialist',
		  433 =>'Blade Lord',
		  434 =>'Blade Master',
		  500 =>'Pirate',
		  508 =>'Jett',
		  510 =>'Brawler',
		  520 =>'Gunslinger',
		  511 =>'Marauder',
		  521 =>'Outlaw',
		  512 =>'Buccaneer',
		  522 =>'Corsair',
		  530 =>'Cannoneer',
		  531 =>'Cannon Blaster',
		  532 =>'Cannon Master',
		  570 =>'Jett 2',
		  571 =>'Jett 3',
		  572 =>'Jett 4',
		  1000 =>'Noblesse',
		  1100 =>'Dawn Warrior',
		  1110 =>'Dawn Warrior 2',
		  1111 =>'Dawn Warrior 3',
		  1200 =>'Blaze Wizard',
		  1210 =>'Blaze Wizard 2',
		  1211 =>'Blaze Wizard 3',
		  1300 =>'Wind Archer',
		  1310 =>'Wind Archer 2',
		  1311 =>'Wind Archer 3',
		  1400 =>'Night Walker',
		  1410 =>'Night Walker 2',
		  1411 =>'Night Walker 3',
		  1500 =>'Thunder Breaker',
		  1510 =>'Thunder Breaker 2',
		  1511 =>'Thunder Breaker 3',
		  2000 =>'Legend',
		  2100 =>'Aran 1',
		  2110 =>'Aran 2',
		  2111 =>'Aran 3',
		  2112 =>'Aran 4',
		  2200 =>'Evan 1',
		  2210 =>'Evan 2',
		  2211 =>'Evan 3',
		  2212 =>'Evan 4',
		  2213 =>'Evan 5',
		  2214 =>'Evan 6',
		  2215 =>'Evan 7',
		  2216 =>'Evan 8',
		  2217 =>'Evan 9',
		  2218 =>'Evan 10',
		  2002 =>'Mercedes Noob',
		  2300 =>'Mercedes',
		  2310 =>'Mercedes 2',
		  2311 =>'Mercedes 3',
		  2312 =>'Mercedes 4',
		  2003 =>'Phantom Noob',
		  2400 =>'Phantom',
		  2410 =>'Phantom 2',
		  2411 =>'Phantom 3',
		  2412 =>'Phantom 4',
		  3000 =>'Citizen',
		  3001 =>'Citizen DS',
		  3100 =>'Demon Slayer',
		  3110 =>'Demon Slayer 2',
		  3111 =>'Demon Slayer 3',
		  3112 =>'Demon Slayer 4',
		  3200 =>'Battle Mage',
		  3210 =>'Battle Mage 2',
		  3211 =>'Battle Mage 3',
		  3212 =>'Battle Mage 4',
		  3300 =>'Wild Hunter',
		  3310 =>'Wild Hunter 2',
		  3311 =>'Wild Hunter 3',
		  3312 =>'Wild Hunter 4',
		  3500 =>'Mechanic',
		  3510 =>'Mechanic 2',
		  3511 =>'Mechanic 3',
		  3512 =>'Mechanic 4',
		  5000 =>'Mihile',
		  5100 =>'Mihile',
		  5110 =>'Mihile 2',
		  5111 =>'Mihile 3',
		  5112 =>'Mihile 4',
		  900 =>'Game Master',
		  910 =>'Super GM'
		);
		if ($p == 1){
			echo "<img src=\"GD/?n=".$player['name']."\" alt='".$player['name']."' />";
		}
			echo "	
				<span onmouseover=\"roll('top5', 'GD/?n=".$player['name']."'); this.style.backgroundColor='';\" onmouseout=\"this.style.backgroundColor=''\"></span>
						<a href=\"#".$player['name']."\">".$player['name']."</a>

						".$player['level']."

						".$jobs[$player['job']]."<br/>";
					}
            }
			echo "</div>";
?>
<script type="text/javascript">
	var image = new Image();
	image.src = "GD/?n=".$player['name'].";
</script>