<?php
if(basename($_SERVER["PHP_SELF"]) == "main.php") {
	die("403 - Access Forbidden");
}

$main = "";
if(isset($_GET['page'])) {
	$main = $_GET['page'];
}

if($getbase === "main") {
	if(empty($main)) {
		$queryhome = $mysqli->query("SELECT homecontent FROM ".$prefix."properties");
		$gethome = $queryhome->fetch_assoc();
		echo "<div class=\"row\">";
		include ("sources/public/main-news.php");
		include ("sources/public/main-events.php");
		echo "</div>";
		echo "<br/><div class=\"row\">";
		include ("sources/public/main-rank.php");
		include ("sources/public/main-gm.php");
		echo "</div><br/>";
		include ("sources/public/home.php");
	}
	elseif($main === "download") {
		include('sources/public/download.php');
	}
	elseif($main === "events") {
		include('sources/public/events.php');
	}
	elseif($main === "guildlist") {
		include('sources/public/guildlist.php');
	}
	elseif($main === "gmblog") {
		include('sources/public/gmblog.php');
	}
	elseif($main === "members") {
		include('sources/public/members.php');
	}
	elseif($main === "news") {
		include('sources/public/news.php');
	}
	elseif($main === "events") {
		include('sources/public/events.php');
	}
	elseif($main === "rankings") {
		include('sources/public/rankings.php');
	}
	elseif($main === "register") {
		include('sources/public/register.php');
	}
	elseif($main === "vote") {
		include('sources/public/vote.php');
	}
	elseif($main === "character") {
			include('sources/public/character.php');
	}
	elseif(in_array($main, $slugs)) {
		include('sources/public/pages.php');
	}
	else {
		redirect("?base=main");
	}
} else {
	redirect("?base=main");
}