<?php
echo "
<h3 class=\"text-center\">Server Info</h3>";
	$accounts = $mysqli->query("SELECT * FROM accounts");
		$saccounts = $accounts->num_rows;
	$characters = $mysqli->query("SELECT * FROM characters");
		$scharacters = $characters->num_rows;
	$online = $mysqli->query("SELECT * FROM accounts where loggedin = 2");
		$sonline = $online->num_rows;
				echo "	
	Players Online: <b>".$sonline."</b><br/>
	Accounts: <b>".$saccounts."</b><br/>
	Characters: <b>".$scharacters."</b><br/>";

echo "<hr/>
Version <a href='?cype=main&amp;page=download'><b>".$version."</b></a><br/>
Experience Rate: <b>".$exprate."</b><br/>
Meso Rate: <b>".$mesorate."</b><br/>
Drop Rate: <b>".$droprate."</b><br/>
";
?>