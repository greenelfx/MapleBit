<?php
/**
    Copyright (C) 2009  Josh L.
        		Murad --

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
**/

if(!isset($_POST['buyNX']))
{
	echo '
		<form name="buynx" method="post" action="">
			<legend>Select A Character</legend>
	';
	$fetchChar = $mysqli->query("SELECT * FROM `characters` WHERE `accountid` = '".$_SESSION['id']."'") or die();
	while($getChar = $fetchChar->fetch_assoc())
	{
		echo '<label class="radio"><input type="radio" name="selChar" value="'.$getChar['id'].'">'.$getChar['name'].'</input></label>';
	}
	echo '
			<legend>Select a Package</legend>
	';
	$fetchPack = $mysqli->query("SELECT * FROM `cype_buynx`");
	while($getPack = $fetchPack->fetch_assoc())
	{
		echo '<label class="radio"><input type="radio" name="selPack" value="'.$getPack['meso'].'">'.number_format($getPack['nx']).' NX for '.number_format($getPack['meso']).' Mesos</input></label>';
	}
	echo '
		<br/><input type="submit" name="buyNX" value="Buy NX &raquo" class="btn btn-primary"/>
		</form>
	';
}
else
{
	$selChar = isset($_POST['selChar']) ? $_POST['selChar'] : '';
	$selPack = isset($_POST['selPack']) ? $_POST['selPack'] : '';
	
	$hasMeso = $mysqli->query("SELECT * FROM `characters` WHERE `id` = '".$selChar."'") or die();
	$getMeso = $hasMeso->fetch_assoc();
	
	$fetchNX = $mysqli->query("SELECT * FROM `cype_buynx` WHERE `meso` = '".$selPack."'") or die();
	$selNX = $fetchNX->fetch_assoc();
	
	if($selChar == NULL)
	{
		echo 'You need to select a character to pay for the NX.<br />[<a href="javascript:history.go(-1);">Go Back</a>]';
	}
	elseif($selPack == NULL)
	{
		echo 'You need to select a package that you want to buy.<br />[<a href="javascript:history.go(-1);">Go Back</a>]';
	}
	elseif($getMeso['meso'] < $selPack)
	{
		echo 'The character you chose does not have enough mesos to buy this package.<br />[<a href="javascript:history.go(-1);">Go Back</a>]';
	}
	else
	{
		$fetchCharId = $mysqli->query("SELECT * FROM `characters` WHERE `id` = '".$selChar."'") or die();
		$getCharId = $fetchCharId->fetch_assoc();
		$mysqli->query("UPDATE `characters` SET `meso` = meso - ".$selPack." WHERE `id` = ".$selChar."") or die();
		$mysqli->query("UPDATE `accounts` SET `paypalNX` = paypalNX + ".$selNX['nx']." WHERE `id` = ".$getCharId['accountid']."") or die();
		echo 'You have purchased <b>'.number_format($selNX['nx']).' NX</b> for <b>'.number_format($selPack).'. Mesos</b>. The mesos have been taken from <b>'.$getCharId['name'].'</b>.<br /><br />Thank you for your purchase,<br />Cype.<br />[<a href="?cype=ucp&page=buynx">Buy NX</a>]';
	}
}
?>