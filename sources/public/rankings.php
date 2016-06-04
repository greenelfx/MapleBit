<?php
if(basename($_SERVER["PHP_SELF"]) == "rankings.php") {
	die("403 - Access Forbidden");
}
?>
<h2 class="text-left">Rankings</h2>
<hr/>
<?php
error_reporting(-1);
if(isset($_GET['job'])) {
	$egetjob = $mysqli->real_escape_string(@$_GET['job']);
	$getjob = preg_replace("/[^A-Za-z0-9 ]/", '', $egetjob); # Escape and Strip
}
$dir = "/";
if(isset($getjob) && $getjob != NULL) {
	if($getjob == "beginner") {
		$show = "AND (c.job = 000)";
	}
	elseif($getjob == "warrior") {
		$show = "AND (c.job = 100 OR c.job = 110 OR c.job = 111 OR c.job = 112 OR c.job = 120 OR c.job = 121 OR c.job = 122 OR c.job = 130 OR c.job = 131 OR c.job = 132)";
	}
	elseif($getjob == "magician") {
		$show = "AND (c.job = 200 OR c.job = 210 OR c.job = 211 OR c.job = 212 OR c.job = 220 OR c.job = 221 OR c.job = 222 OR c.job = 230 OR c.job = 231 OR c.job = 232)";
	}
	elseif($getjob == "bowman") {
		$show = "AND (c.job = 300 OR c.job = 310 OR c.job = 311 OR c.job = 312 OR c.job = 320 OR c.job = 321 OR c.job = 322)";
	}
	elseif($getjob == "thief") {
		$show = "AND (c.job = 400 OR c.job = 410 OR c.job = 411 OR c.job = 412 OR c.job = 420 OR c.job = 421 OR c.job = 422)";
	}
	elseif($getjob == "pirate") {
		$show = "AND (c.job = 500 OR c.job = 510 OR c.job = 511 OR c.job = 512 OR c.job = 520 OR c.job = 521 OR c.job = 522)";
	}
	elseif($getjob == "cygnus") {
		$show = "AND (c.job = 1000 OR c.job = 1100 OR c.job = 1110 OR c.job = 1111 OR c.job = 1112 OR c.job = 1200 OR c.job = 1210 OR c.job = 1211 OR c.job = 1212 OR c.job = 1300 OR c.job = 1310 OR c.job = 1311 OR c.job = 1312 OR c.job = 1400 OR c.job = 1410 OR c.job = 4111 OR c.job = 1412 OR c.job = 1500 OR c.job = 1511 OR c.job = 1512)";
	}
	elseif($getjob == "aran") {
		$show = "AND (c.job = 2000 OR c.job = 2100 OR c.job = 2110 OR c.job = 2111 OR c.job = 2112)";
	}
	elseif($getjob == "all") {
		$show = "";
	}
} else {
	$show = "";
	$getjob = "all";
}
$estart = $mysqli->real_escape_string(@$_GET['start']);
$start = intval(+preg_replace("/[^A-Za-z0-9 ]/", '', $estart)); # Escape and Strip and ensure it's a number
$esearch = $mysqli->real_escape_string(@$_GET['search']);
$search = preg_replace("/[^A-Za-z0-9 ]/", '', $esearch); # Escape and Strip
if(isset($search)) {
	$esearch = $mysqli->real_escape_string(@$_POST['search']);
	$search = preg_replace("/[^A-Za-z0-9 ]/", '', $esearch); # Escape and Strip
	$csearch = " AND c.name LIKE '".$search."%'";
} else {
	$csearch = "";
}
if(isset($search)) {
	if($servertype == 1) {
		$result2 = $mysqli->query("SELECT c.name , c.gm, c.job , c.level, c.reborns, g.guildid, g.name AS gname, g.logo AS logo, g.logoColor AS logoColor, g.logoBGColor AS logoBGColor, g.logoBG AS logoBG FROM characters c LEFT JOIN guilds g ON c.guildid = g.guildid WHERE c.gm < $gmlevel ".$show."".$csearch." GROUP BY c.id DESC ORDER BY reborns DESC, level DESC LIMIT $start, 15") or die("IT IS LINE ". __LINE__ . "<br />" . $mysqli->error);
	} else {
		$result2 = $mysqli->query("SELECT c.name , c.gm, c.job , c.level, c.exp, g.guildid, g.name AS gname, g.logo AS logo, g.logoColor AS logoColor, g.logoBGColor AS logoBGColor, g.logoBG AS logoBG FROM characters c LEFT JOIN guilds g ON c.guildid = g.guildid WHERE c.gm < $gmlevel ".$show."".$csearch." GROUP BY c.id DESC ORDER BY level DESC, exp DESC LIMIT $start, 15") or die("IT IS LINE ". __LINE__ . "<br />" . $mysqli->error);
	}
	$row_number = 0;
	$int = 0;
	while(($row = $result2->fetch_assoc()) && !$row_number) {
		if(strtolower($row['name']) == strtolower($search)) {
			$row_number = $int;
		}
		$int++;
	}
	if($row_number) {
		$start = $row_number - ($row_number % 5);
	}
}
if($servertype == 1) {
	$result = $mysqli->query("SELECT c.name , c.gm, c.job, c.level, c.reborns, g.guildid, g.name AS gname, g.logo AS logo, g.logoColor AS logoColor, g.logoBGColor AS logoBGColor, g.logoBG AS logoBG FROM characters c LEFT JOIN guilds g ON c.guildid = g.guildid WHERE (c.gm < '$gmlevel') ".$show."".$csearch." GROUP BY c.id DESC ORDER BY reborns DESC, level DESC LIMIT 15 OFFSET $start") or die("IT IS LINE ". __LINE__ . "<br />" . $mysqli->error);
} else {
	$result = $mysqli->query("SELECT c.name , c.gm, c.job, c.level, c.exp, g.guildid, g.name AS gname, g.logo AS logo, g.logoColor AS logoColor, g.logoBGColor AS logoBGColor, g.logoBG AS logoBG FROM characters c LEFT JOIN guilds g ON c.guildid = g.guildid WHERE (c.gm < '$gmlevel') ".$show."".$csearch." GROUP BY c.id DESC ORDER BY level DESC, exp DESC LIMIT 15 OFFSET $start") or die("IT IS LINE ". __LINE__ . "<br />" . $mysqli->error);
}
echo "
<div class=\"row\">
	<div class=\"col-md-6\">
		<div class=\"well well2\" style=\"margin: 0 auto; display: inline-block;margin-bottom:0px;\">
			<a href=\"?base=main&page=rankings&job=beginner\"><img src=\"".$siteurl."assets/img/rank/beginner.png\" data-toggle=\"tooltip\" title=\"Beginner\" alt=\"Beginner\"/></a>
			<a href=\"?base=main&page=rankings&job=warrior\"><img src=\"".$siteurl."assets/img/rank/warrior.png\" data-toggle=\"tooltip\" title=\"Warrior\" alt=\"Warrior\"/></a>
			<a href=\"?base=main&page=rankings&job=magician\"><img src=\"".$siteurl."assets/img/rank/magician.png\" data-toggle=\"tooltip\" title=\"Magician\" alt=\"Magician\"/></a>
			<a href=\"?base=main&page=rankings&job=bowman\"><img src=\"".$siteurl."assets/img/rank/bowman.png\" data-toggle=\"tooltip\" title=\"Bowman\" alt=\"Bowman\"/></a>
			<a href=\"?base=main&page=rankings&job=thief\"><img src=\"".$siteurl."assets/img/rank/thief.png\" data-toggle=\"tooltip\" title=\"Thief\" alt=\"Theif\"/></a>
			<a href=\"?base=main&page=rankings&job=pirate\"><img src=\"".$siteurl."assets/img/rank/pirate.png\" data-toggle=\"tooltip\" title=\"Pirate\" alt=\"Pirate\"/></a>
			<a href=\"?base=main&page=rankings&job=cygnus\"><img src=\"".$siteurl."assets/img/rank/cygnus.png\" data-toggle=\"tooltip\" title=\"Cygnus\" alt=\"Cygnus\"/></a>
			<a href=\"?base=main&page=rankings&job=aran\"><img src=\"".$siteurl."assets/img/rank/aran.png\" data-toggle=\"tooltip\" title=\"Aran\" alt=\"Aran\"/></a>
		</div>
	</div>
	<div class=\"col-md-5 col-md-offset-1\">
		<form id='search_form' method='post' action='?base=main&page=rankings'>
			<div style=\"float:right;\">
				<div class=\"well well2\" style=\"margin-bottom:0px;\">
					<div class=\"input-group\">
						<input type='text' name='search' id='s' class='form-control' placeholder='Character Name' required value='".$search."'/>
						<span class=\"input-group-btn\">
							<button class=\"btn btn-primary\" type=\"submit\"><i class=\"icon-search\"></i> Search</button>
						</span>
					</div>
				</div>
			</div>
		</form>
	</div>
</div><hr/>";
echo "
<div class=\"table-responsive\">
	<table class=\"table table-striped table-hover center-table table-bordered\">
		<thead>
			<tr>
				<th>Rank</th>
				<th class=\"hidden-sm hidden-xs\">Picture</th>
				<th>Name</th>
				<th>Job</th>";
				if($servertype == 1) {
					echo "<th>Rebirths</th>";
				}
				echo "		<th>Level</th>
			</tr>
		</thead>
		<tbody>";

			$ranking=$start;
			while($row = $result->fetch_assoc()) {
				$ranking++;
				$name = $row['name'];
				echo "
				<tr>
					<td><span class=\"badge\">$ranking</span></td>
					<td class=\"hidden-sm hidden-xs\"><img src=\"".$siteurl."assets/img/GD/create.php?name=".$name."\" alt=\"".$name."\" class=\"avatar img-responsive\" style=\"margin: 0 auto;\"></td>
					<td><a href=\"?base=main&page=character&n=".$row['name']."\">".$row['name']."</a></td>
					<td>";
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
						if ($row['job']=="1000")
							echo "Noblesse";
						if ($row['job']=="1100")
							echo "Dawn Warrior";
						if ($row['job']=="1110")
							echo "Dawn Warrior 2";
						if ($row['job']=="1111")
							echo "Dawn Warrior 3";
						if ($row['job']=="1112")
							echo "Dawn Warrior 4";
						if ($row['job']=="1200")
							echo "Flame Wizard";
						if ($row['job']=="1210")
							echo "Flame Wizard 2";
						if ($row['job']=="1211")
							echo "Flame Wizard 3";
						if ($row['job']=="1212")
							echo "Flame Wizard 4";
						if ($row['job']=="1300")
							echo "Wind Archer";
						if ($row['job']=="1310")
							echo "Wind Archer 2";
						if ($row['job']=="1311")
							echo "Wind Archer 3";
						if ($row['job']=="1312")
							echo "Wind Archer 4";
						if ($row['job']=="1400")
							echo "Night Walker";
						if ($row['job']=="1410")
							echo "Night Walker 2";
						if ($row['job']=="1411")
							echo "Night Walker 3";
						if ($row['job']=="1412")
							echo "Night Walker 4";
						if ($row['job']=="1500")
							echo "Thunder Breaker";
						if ($row['job']=="1510")
							echo "Thunder Breaker 2";
						if ($row['job']=="1511")
							echo "Thunder Breaker 3";
						if ($row['job']=="1512")
							echo "Thunder Breaker 4";
						if ($row['job']=="2000")
							echo "Legend";
						if ($row['job']=="2100")
							echo "Aran";
						if ($row['job']=="2111")
							echo "Aran 2";
						if ($row['job']=="2112")
							echo "Aran 3";

						if($servertype == 1) {
							echo "</td>
							<td>".$row['reborns']."</td>";
						}
						echo "
						<td>".$row['level']."</td>
					</tr>";
				}
				echo "
			</tbody>
		</table>
	</div>
	<ul class=\"pager\">
		";

		if($start == 0 || $start<=15) {
			echo "  <li class=\"previous\"><a href=\"?base=main&page=rankings&job=".$getjob."/\"><i class=\"icon-arrow-left\"></i> Previous</a></li>";
		}
		else{
			echo "<li class=\"previous\"><a href=\"?base=main&page=rankings&job=".$getjob."&start=". abs($start - 15) ."\"><i class=\"icon-arrow-left\"></i> Previous</a></li>";
		}
		echo"
		<li class=\"next\"><a href=\"?base=main&page=rankings&job=".$getjob."&start=". abs($start + 15) ."\">Next<i class=\"icon-arrow-right\"></i></a></li>";
		?>

	</ul>