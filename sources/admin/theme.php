<?php 
if(basename($_SERVER["PHP_SELF"]) == "theme.php"){
    die("403 - Access Forbidden");
}
if($_SESSION['admin'] == 1) {
	$do = isset($_GET['do']) ? $_GET['do'] : '';
	echo "<h2 class=\"text-left\">Configure Theme</h2><hr/>";
	switch($do)
	{
		case NULL:		
			echo "Welcome to MapleBit's theming center! MapleBit uses Bootswatch, a collection of Bootstrap CSS themes, to customize the feel of the website.";
			echo "<br/>Please browse <a href=\"http://bootswatch.com\">Bootswatch.</a>";
			echo '<hr/><a href="?base=admin&amp;page=theme&amp;do=apply" class="btn btn-primary">Configure Theme &raquo;</a>';
			break;
		case 'apply':
			if(!isset($_POST['apply']))
			{
				echo '
					<form name="applytheme" method="post">
					<label class="radio"><input type="radio" name="theme" value="bootstrap" checked/>Default Bootstrap</label>
					 <label class="radio"><input type="radio" name="theme" value="cerulean"/>Cerulean <a href="http://bootswatch.com/cerulean/" target="_blank"><i class="fa fa-external-link"></i></a></label>
					 <label class="radio"><input type="radio" name="theme" value="cosmo"/>Cosmo <a href="http://bootswatch.com/cosmo/" target="_blank"><i class="fa fa-external-link"></i></a></label>
					 <label class="radio"><input type="radio" name="theme" value="cyborg"/>Cyborg <a href="http://bootswatch.com/cyborg/" target="_blank"><i class="fa fa-external-link"></i></a></label>
					 <label class="radio"><input type="radio" name="theme" value="darkly"/>Darkly <a href="http://bootswatch.com/darkly/" target="_blank"><i class="fa fa-external-link"></i></a></label>
					 <label class="radio"><input type="radio" name="theme" value="flatly"/>Flatly <a href="http://bootswatch.com/flatly/" target="_blank"><i class="fa fa-external-link"></i></a></label>
					 <label class="radio"><input type="radio" name="theme" value="journal"/>Journal <a href="http://bootswatch.com/journal/" target="_blank"><i class="fa fa-external-link"></i></a></label>
					 <label class="radio"><input type="radio" name="theme" value="lumen"/>Lumen <a href="http://bootswatch.com/lumen/" target="_blank"><i class="fa fa-external-link"></i></a></label>
					 <label class="radio"><input type="radio" name="theme" value="readable"/>Readable <a href="http://bootswatch.com/readable/" target="_blank"><i class="fa fa-external-link"></i></a></label>
					 <label class="radio"><input type="radio" name="theme" value="sandstone"/>Sandstone <a href="http://bootswatch.com/sandstone/" target="_blank"><i class="fa fa-external-link"></i></a></label>
					 <label class="radio"><input type="radio" name="theme" value="slate"/>Slate <a href="http://bootswatch.com/slate/" target="_blank"><i class="fa fa-external-link"></i></a></label>
					 <label class="radio"><input type="radio" name="theme" value="simplex"/>Simplex <a href="http://bootswatch.com/simplex/" target="_blank"><i class="fa fa-external-link"></i></a></label>
					 <label class="radio"><input type="radio" name="theme" value="spacelab"/>Spacelab <a href="http://bootswatch.com/spacelab/" target="_blank"><i class="fa fa-external-link"></i></a></label>
					 <label class="radio"><input type="radio" name="theme" value="superhero"/>Superhero <a href="http://bootswatch.com/superhero/" target="_blank"><i class="fa fa-external-link"></i></a></label>
					 <label class="radio"><input type="radio" name="theme" value="united"/>United <a href="http://bootswatch.com/united/" target="_blank"><i class="fa fa-external-link"></i></a></label>
					 <label class="radio"><input type="radio" name="theme" value="yeti"/>Yeti <a href="http://bootswatch.com/yeti/" target="_blank"><i class="fa fa-external-link"></i></a></label>
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
					echo "<div class=\"alert alert-success\">" . ucfirst($themeselect) . " applied.<br /><a href=\"?base=admin&page=theme\">Back to Themes</a></div>";
				}
				else {
					echo "<div class=\"alert alert-danger\">Please select your theme and navigation bar type!<br /><a href=\"?base=admin&page=theme&do=apply\">Back to Themes</a></div>";
				}
			}
	break;
	}
}
else {
	redirect("?base");
}
?>