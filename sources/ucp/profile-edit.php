<?php
if(basename($_SERVER["PHP_SELF"]) == "profile-edit.php") {
	die("403 - Access Forbidden");
}
?>
<script src="assets/libs/cksimple/ckeditor.js"></script>
<?php
if($_SESSION['pname'] === "checkpname") {
	echo "<div class=\"alert alert-danger\">You must assign a profile name before you can edit your public profile.</div>";
	return;
}

require "assets/libs/gump.class.php";
GUMP::add_validator("exists", function($field, $input, $param = NULL) use ($mysqli, $prefix) {
	return $mysqli->query("SELECT COUNT(*) FROM characters WHERE $param ='".$mysqli->real_escape_string($input[$field])."'")->fetch_row()[0] == 0;
});
if(isset($_POST['edit'])) {
	$gump = new GUMP();
	$_POST = $gump->sanitize($_POST);
	$gump->validation_rules(array(
		'mainchar' => 'exists,name',
		'realname' => 'alpha_space',
		'age' => 'numeric|min_numeric,7|max_numeric,50',
		'country' => 'alpha_space',
		'motto' => 'alpha_space',
		'text' => 'max_len, 200'
	));
	$gump->filter_rules(array(
	    'mainchar' => 'trim|sanitize_string',
	    'realname' => 'trim|sanitize_string',
	    'age' => 'trim|sanitize_string',
	    'country' => 'trim|sanitize_string',
	    'motto' => 'trim|sanitize_string',
	    'favjob' => 'trim|sanitize_string',
	    'text' => 'sanitize_string',
	));
	$validated_data = $gump->run($_POST);
	if($validated_data === false) {
		echo '<div class="alert alert-danger">';
		foreach($gump->get_errors_array() as $error) {
			echo $error . '<br/>';
		}
		echo '</div>';
	} else {
 		$u = $mysqli->query("UPDATE `".$prefix."profile` SET `mainchar`='".$validated_data['mainchar']."',`realname`='".$validated_data['realname']."',`age`='".$validated_data['age']."',`country`='".$validated_data['country']."',`motto`='".$validated_data['motto']."',`favjob`='".$mysqli->real_escape_string($validated_data['favjob'])."',`text`='".$mysqli->real_escape_string($validated_data['text'])."' WHERE `accountid`='".$_SESSION['id']."'");
		echo "<div class=\"alert alert-success\">Your public profile has been updated<br />";
		echo "Click <a href=\"?base=main&amp;page=members&name=".$_SESSION['pname']."\" class=\"alert-link\">here</a> to go to your profile.</div>";
	}
}
else {
	$profile =  $mysqli->query("SELECT * FROM ".$prefix."profile WHERE accountid='".$_SESSION['id']."'")->fetch_assoc();
	$getCharacters = $mysqli->query("SELECT * FROM characters WHERE accountid='".$_SESSION['id']."'");
?>
	<h2 class="text-left">My Profile</h2><hr/>
	<form method="post" role="form">
		<b>Profile Name: </b> <?php echo $profile['name'] ?>
		<div class="form-group">
		<?php
			if($getCharacters->num_rows) {
				echo "
					<label for=\"mainChar\">Main Character:</label>
					<select name=\"mainchar\" class=\"form-control\" id=\"mainChar\">
				";
				while($c = $getCharacters->fetch_assoc()) {
					echo "<option value=\"".$c['id']."\">".$c['name']."</option>";
				}
				echo "</select>";
			} else {
				echo "<hr/><div class=\"alert alert-danger\">You don't have any characters!</div><hr/>";
			}
		?>
		</div>
		<div class="form-group">
			<label for="realName">Real Name:</label>
			<input type="text" class="form-control" name="realname" id="realName" value="<?php echo htmlspecialchars($profile['realname'], ENT_QUOTES, 'UTF-8') ?>">
		</div>
		<div class="form-group">
			<label for="myAge">Age: </label>
			<select name="age" class="form-control" id="myAge">
				<?php
				if(!isset($profile['age'])) {
					echo "<option disabled selected>Select Age</option>";
				}
				for($i = 7; $i < 50; $i++) {
					if(isset($profile['age']) && $i == $profile['age']) {
						echo "<option value=\"".$i."\" selected>".$i."</option>";
					} else {
						echo "<option value=\"".$i."\">".$i."</option>";
					}
				}
				?>
			</select>
		</div>
		<div class="form-group">
			<label for="inputCountry">Country:</label>
			<select id="inputCountry" name="country" class="form-control">
			<?php
				if(!isset($profile['country'])) {
					echo "<option disabled selected>Select Country</option>";
				}
				$countries = getCountries();
				foreach($countries as $country) {
					if(isset($profile['country']) && $country == $profile['country']) {
						echo "<option value=\"".$country."\" selected>".$country."</option>";
					} else {
						echo "<option value=\"".$country."\">".$country."</option>";
					}
				}
			?>
			</select>
		</div>
		<div class="form-group">
			<label for="inputMotto">Motto:</label>
			<input type="text" class="form-control" name="motto" id="inputMotto" value="<?php echo htmlspecialchars($profile['motto'], ENT_QUOTES, 'UTF-8') ?>">
		</div>
		<div class="form-group">
			<label for="favJob">Favorite Job:</label>
			<select name="favjob" class="form-control" id="favJob">
			<?php
				$jobs = getJobNames(true);
				foreach($jobs as $job) {
					if(isset($profile['favjob']) && $job == $profile['favjob']) {
						echo "<option value=\"".$job."\" selected>".$job."</option>";
					} else {
						echo "<option value=\"".$job."\">".$job."</option>";
					}
				}
			?>
			</select>
		</div>
		<div class="form-group">
			<label>About Me:</label>
			<textarea name="text" style="height:200px" maxlength="200" class="form-control" id="textCount"><?php echo stripslashes($profile['text']) ?></textarea>
		</div>
		<p id="counter">Characters left: 200</p>
		<div class="alert alert-info">Please keep in mind that all of this information will be public.</div>
		<input type="submit" name="edit" value="Update &raquo;" class="btn btn-primary"/>
	</form>
<?php
}
?>
<script>
	CKEDITOR.replace('textCount');
	CKEDITOR.instances.textCount.on("key", function (event) {
		var s = CKEDITOR.instances.textCount.getData().length;
		var left = 200 - s;
		$('#counter').html('Characters left: ' + left);
	});
</script>