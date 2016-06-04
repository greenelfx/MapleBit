<?php
if(basename($_SERVER["PHP_SELF"]) == "main-rank.php") {
	die("403 - Access Forbidden");
}
if($servertype == 1) {
	$first = "reborns";
	$second = "level";
}
else {
	$first = "level";
	$second = "exp";
}

echo "
	<div class=\"col-md-6\">
	<a href='?base=main&amp;page=rankings'><h4>Rankings &raquo;</h4></a><hr/>
";
$gc = $mysqli->query("SELECT c.$first , c.$second, c.name, c.accountid, a.banned AS banned FROM characters c LEFT JOIN accounts a ON c.accountid = a.id WHERE c.gm < '$gmlevel' AND banned = 0 GROUP BY c.id DESC ORDER BY $first DESC, $second DESC LIMIT 5");
if($gc->num_rows) {
	echo "
		<table class=\"table table-condensed\">
			<thead>
				<tr>
					<th>Avatar</th>
					<th>Name</th>
					<th>".ucfirst($first)."</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td rowspan=\"6\">
	";
	$p = 0;
	while($player = $gc->fetch_assoc() and $p <=5) {
		$char = $player['accountid'];
		$name = $player['name'];
		$p++;
		if ($p == 1) {
			echo "
				<img src=\"assets/img/GD/create.php?name=".$name."\" alt='".$name."' id=\"top5\" class=\"rank_img\"/>
				</td>
			";
		}
		echo "
			<tr>
				<td>
					<a href=\"?base=main&amp;page=character&amp;n=".$name."\" onmouseover=\"roll('top5', 'assets/img/GD/Characters/".$name.".png')\" id=\"".$name."\">".$name."</a>
				</td>
				<td>".$player[$first]."</td>
			</tr>
		";
	}
	echo "
		</tbody>
		</table>
	";
}
else {
	echo "<div class=\"alert alert-info\">No characters found.</div>";
}
echo "<hr/></div>";