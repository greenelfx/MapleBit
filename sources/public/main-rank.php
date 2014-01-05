<?php
if($servertype == 1) { 
	$first = "reborns";
	$second = "level";
} else {
	$first = "level";
	$second = "exp";
}

echo "
<div class=\"col-md-6\">
<a href='?base=main&amp;page=rankings'><h4>Rankings &raquo;</h4></a><hr/>";
	$gc = $mysqli->query("SELECT c.$first , c.$second, c.name, c.accountid, a.banned AS banned FROM characters c LEFT JOIN accounts a ON c.accountid = a.id WHERE c.gm < '$gmlevel' AND banned = 0 GROUP BY c.id DESC ORDER BY $first DESC, $second DESC LIMIT 5");
	$backcolor="";
	$rootfolder = "";
	require_once("assets/img/GD/coordinates.php");
	require_once("assets/img/GD/cache_character.php");
	echo "
<table class=\"table table-condensed\">
	<thead>
		<th>Avatar</th>
		<th>Name</th>
		<th>".ucfirst($first)."</th>
	</thead>
	<tbody>
		<tr>
			<td rowspan=\"6\">";
	$p = 0;
	while($player = $gc->fetch_assoc() and $p <=5){
		$char = $player['accountid'];
		$name = $player['name'];
		createChar($name, $rootfolder);
		$cachechar = $mysqli->query("SELECT hash, name FROM ".$prefix."gdcache WHERE name='".$name."'")->fetch_assoc();
		$p++;
			if ($p == 1){
				echo "
				<img src=\"assets/img/GD/Characters/".$cachechar['hash'].".png\" alt='".$cachechar['name']."' name=\"top5\"/>
			</td>";
			}
				echo "
				<tr>
					<td>
						<a href=\"?base=main&page=character&n=".$player['name']."\" onmouseover=\"roll('top5', 'assets/img/GD/Characters/".$cachechar['hash'].".png')\">".$player['name']."</a>
					</td>
					<td>".$player[$first]."</td>
				</tr>";
	}
?>

</table>
</div>