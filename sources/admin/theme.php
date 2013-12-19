<?php 
if($_SESSION['admin'] == 1) {
	$do = isset($_GET['do']) ? $_GET['do'] : '';
	echo "<h2 class=\"text-left\">Configure Theme</h2><hr/>";
	switch($do)
	{
		case NULL:		
			echo "Welcome to CypeReboot's theming center! Instead of loading a whole bunch of files locally, CypeReboot uses Bootswatch, a collection of Bootstrap CSS themes, to customize the feel of the website.";
			echo "<br/>Please browse <a href=\"http://bootswatch.com\">Bootswatch.</a>";
			echo '<hr/><a href="?cype=admin&amp;page=theme&amp;do=apply" class="btn btn-primary">Configure Theme &raquo;</a>';
			break;
		case 'apply':
			if(!isset($_POST['apply']))
			{
				echo '
					<form name="applytheme" method="post">
					<label class="radio"><input type="radio" name="theme" value="bootstrap" checked/>Default Bootstrap</label>
					 <label class="radio"><input type="radio" name="theme" value="cerulean" />Cerulean</label>
					 <label class="radio"><input type="radio" name="theme" value="cosmo" />Cosmo</label>
					 <label class="radio"><input type="radio" name="theme" value="cyborg" />Cyborg</label>
					 <label class="radio"><input type="radio" name="theme" value="flatly" />Flatly</label>
					 <label class="radio"><input type="radio" name="theme" value="journal" />Journal</label>
					 <label class="radio"><input type="radio" name="theme" value="readable" />Readable</label>
					 <label class="radio"><input type="radio" name="theme" value="slate" />Slate</label>
					 <label class="radio"><input type="radio" name="theme" value="spacelab" />Spacelab</label>
					 <label class="radio"><input type="radio" name="theme" value="united" />United</label>
					 <label class="radio"><input type="radio" name="theme" value="yeti" />Yeti</label>
					 <hr/>
					 <label class="radio"><input type="radio" name="nav" value="0" checked/>Normal Navigation Bar</label>
					 <label class="radio"><input type="radio" name="nav" value="1" />Inverse Navigation Bar</label>
					 <hr/>
					<input type="submit" name="apply" value="Apply Theme &raquo;" class="btn btn-primary"/>
					</form>
				';
			}
			else {	
				$themeselect = @$_POST['theme'];
				$nav = @$_POST['nav'];
				if(isset($themeselect) && isset($nav)){
					$query2 = $mysqli->query("UPDATE ".$prefix."properties SET theme = '$themeselect', nav = '$nav'");
					echo "<div class=\"alert alert-success\">" . ucfirst($themeselect) . " applied.<br /><a href=\"?cype=admin&page=theme\">Back to Themes</a></div>";
				}
				else {
					echo "<div class=\"alert alert-danger\">Please select your theme and navigation bar type!<br /><a href=\"?cype=admin&page=theme&do=apply\">Back to Themes</a></div>";
				}
			}
	break;
	}
}
else {
	redirect("?cype");
}
?>