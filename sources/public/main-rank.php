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
			if($player["job"] == "121"){
				$job = "White Knight";
				}elseif($player["job"] == "000"){
					$job = "Beginner";
				}elseif($player["job"] == "100"){
					$job = "Swordsman";
				}elseif($player["job"] == "110"){
					$job = "Fighter";
				}elseif($player["job"] == "120"){
					$job = "Page";
				}elseif($player["job"] == "130"){
					$job = "Spearman";
				}elseif($player["job"] == "111"){
					$job = "Crusader";
				}elseif($player["job"] == "121"){
					$job = "White Knight";
				}elseif($player["job"] == "131"){
					$job = "Dragon Knight";
				}elseif($player["job"] == "112"){
					$job = "Hero";
				}elseif($player["job"] == "122"){
					$job = "Paladin";
				}elseif($player["job"] == "132"){
					$job = "Dark Knight";
				}elseif($player["job"] == "200"){
					$job = "Magician";
				}elseif($player["job"] == "210"){
					$job = "F/P Wizard";
				}elseif($player["job"] == "220"){
					$job = "I/L Wizard";
				}elseif($player["job"] == "230"){
					$job = "Cleric";
				}elseif($player["job"] == "211"){
					$job = "F/P Mage";
				}elseif($player["job"] == "221"){
					$job = "I/L Mage";
				}elseif($player["job"] == "231"){
					$job = "Priest";
				}elseif($player["job"] == "212"){
					$job = "F/P Arch Mage";
				}elseif($player["job"] == "222"){
					$job = "I/L Arch Mage";
				}elseif($player["job"] == "232"){
					$job = "Bishop";
				}elseif($player["job"] == "300"){
					$job = "Bowman";
				}elseif($player["job"] == "310"){
					$job = "Hunter";
				}elseif($player["job"] == "320"){
					$job = "Crossbowman";
				}elseif($player["job"] == "311"){
					$job = "Ranger";
				}elseif($player["job"] == "321"){
					$job = "Sniper";
				}elseif($player["job"] == "312"){
					$job = "Bow Master";
				}elseif($player["job"] == "322"){
					$job = "Crossbow Master";
				}elseif($player["job"] == "400"){
					$job = "Rogue";
				}elseif($player["job"] == "410"){
					$job = "Assassin";
				}elseif($player["job"] == "420"){
					$job = "Bandit";
				}elseif($player["job"] == "411"){
					$job = "Hermit";
				}elseif($player["job"] == "421"){
					$job = "Chief Bandit";
				}elseif($player["job"] == "412"){
					$job = "Night Lord";
				}elseif($player["job"] == "422"){
					$job = "Shadower";
				}elseif($player["job"] == "500"){
					$job = "Pirate";
				}elseif($player["job"] == "510"){
					$job = "Brawler";
				}elseif($player["job"] == "520"){
					$job = "Gunslinger";
				}elseif($player["job"] == "511"){
					$job = "Marauder";
				}elseif($player["job"] == "521"){
					$job = "Outlaw";
				}elseif($player["job"] == "512"){
					$job = "Buccaneer";
				}elseif($player["job"] == "522"){
					$job = "Corsair";
				}elseif($player["job"] == "900"){
					$job = "GM";
				}elseif($player["job"] == "910"){
					$job = "SuperGM";
}
		if ($p == 1){
			echo "<img src=\"GD/?n=".$player['name']."\" alt='".$player['name']."' />";
		}
			echo "	
				<span onmouseover=\"roll('top5', 'GD/?n=".$player['name']."'); this.style.backgroundColor='';\" onmouseout=\"this.style.backgroundColor=''\"></span>
						<a href=\"#".$player['name']."\">".$player['name']."</a>

						".$player['level']."

						".$job."<br/>";
					}
            }
			echo "</div>";
?>
<script type="text/javascript">
	var image = new Image();
	image.src = "GD/?n=".$player['name'].";
</script>