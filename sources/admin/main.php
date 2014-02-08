<?php 
if(isset($_GET['page'])){
	$admin = $_GET['page'];
}else{
	$admin = "";
}
if($_SESSION['id']){
	if($_SESSION['admin']){
		if($getbase == "admin"){
			if($admin == ""){
				$getcomments = $mysqli->query("SELECT * FROM ".$prefix."ncomments, ".$prefix."bcomments, ".$prefix."ecomments LIMIT 10"); // not actually latest
				$gettime = $mysqli->query("SELECT githubapi FROM ".$prefix."properties")->fetch_assoc();
				$time = time();
				if($gettime['githubapi'] == "" || $gettime['githubapi'] >= $time+21600) {
					$time = $time + 21600;
					if (extension_loaded('openssl')) {
						$opts = array(
							'http'=>array(
							'method'=>"GET",
							'header'=>"User-Agent: maplebit"
							)
						);
						$context = stream_context_create($opts);
						$current_tags = file_get_contents("https://api.github.com/repos/greenelf/maplebit/tags", false, $context);
						if ($current_tags !== false) {
							$tags = json_decode($current_tags);
							$ref_tag = "v1.03";
							$current_tag = $tags[0]->name;
							if ($current_tag == $ref_tag) {
								$alert_class = "success";
								$version_message = "<b>MapleBit is up to date.</b>";
								$status = 0;
							} else {
								$alert_class = "info";
								$version_message = "<a href=\"https://github.com/greenelf/MapleBit\" class=\"alert-link\">Update Available &raquo;</a>";
								$status = 1;
							}
						} else {
							$alert_class = "danger";
							$version_message = "Can't get MapleBit update status.";
							$status = 2;
						}
					} else {
						$alert_class = "danger";
						$version_message = "Enable openssl by right clicking wamp, then PHP, and then scroll down to php_openssl";
						$status = 2;
					}
					$mysqli->query("UPDATE ".$prefix."properties SET status = '".$status."', githubapi = '". $time ."'");
				} else {
					$getstatus = $mysqli->query("SELECT status FROM ".$prefix."properties")->fetch_assoc();
					if($getstatus['status'] == 0) {
						$alert_class = "success";
						$version_message = "<b>MapleBit is up to date.</b>";
					}
					elseif($getstatus['status'] == 1){
						$alert_class = "info";
						$version_message = "<a href=\"https://github.com/greenelf/MapleBit\" class=\"alert-link\">Update Available &raquo;</a>";
					}
					elseif($getstatus['status'] == 2){
						$alert_class = "danger";
						$version_message = "Can't get MapleBit update status.";
					}
					else {
						$alert_class = "danger";
						$version_message = "Enable openssl by right clicking wamp, then PHP, and then scroll down to php_openssl";
					}
				
				}
				require_once 'assets/libs/HTMLPurifier.standalone.php';
				$commentconfig = HTMLPurifier_Config::createDefault();
				$commentconfig->set('HTML.Allowed', 'b, u, s, i'); 
				$commentpurifier = new HTMLPurifier($commentconfig);
?>

	<h2 class="text-left">Admin Home</h2>
	<hr>
	<div class="row">
		<div class="col-md-12">
			<div class="jumbotron">
					<h1>Welcome Back!</h1>
					<p>Hey there, <?php echo $name; ?>! You can use the links below to manage your website configuration, users, reports, and more!</p>
			</div>
			<hr/>
			<h2 class="text-left">Recent Comments</h2><br/>
			<ul class="list-group">
			<?php
			while($comments = $getcomments->fetch_assoc()) {
			$clean_comment = $commentpurifier->purify($comments['comment']);
			if($comments['feedback'] == 0){
				$icon = "smile"; 
			}
			elseif($comments['feedback'] == 1){
				$icon = "frown"; 
			}
			if($comments['feedback'] == 2){
				$icon = "meh"; 
			}
				echo "<li class=\"list-group-item\"><i class=\"fa fa-".$icon."-o\"></i>&nbsp;<a href=\"#\">" . $comments['author'] . ": ".substr($clean_comment, 0, 50)."</a></li>";
			}
			?>
			</ul>
			<hr/>
		</div>
		<div class="col-md-6">
			<div class="well">
				Welcome to the MapleBit Administration Panel.<br/>Please report any bugs and quirks to greenelf!
			</div>
		</div>
		<div class="col-md-6">
			<div class="alert alert-<?php echo $alert_class; ?>">
				<h2 style="margin: 0px;">MapleBit Status</h2><hr/>
				<?php echo $version_message; ?>
			</div>
		</div>
	</div>
</div>
<?php
			}elseif($admin == "voteconfig"){
				include('sources/admin/voteconfig.php');
			}elseif($admin == "gmlog"){
				include('sources/admin/gmlog.php');
			}elseif($admin == "manevent"){
				include('sources/admin/manage-event.php');
			}elseif($admin == "mannews"){
				include('sources/admin/manage-news.php');
			}elseif($admin == "muteuser"){
				include('sources/admin/mute-user.php');
			}elseif($admin == "unmuteuser"){
				include('sources/admin/unmute-user.php');
			}elseif($admin == "properties"){
				include('sources/admin/properties.php');
			}elseif($admin == "banned"){
				include('sources/admin/banned.php');
			}elseif($admin == "nxpacks"){
				include('sources/admin/nxpacks.php');
			}elseif($admin == "theme"){
				include('sources/admin/theme.php');
			}elseif($admin == "banner"){
				include('sources/admin/banner.php');
			}elseif($admin == "background"){
				include('sources/admin/background.php');
			}elseif($admin == "ticket"){
				include('sources/admin/ticket.php');
			}elseif($admin == "pages"){
				include('sources/admin/pages.php');
			}elseif($admin == "update"){
				include('sources/admin/update.php');
			}elseif($admin == "homeconfig"){
				include('sources/admin/homeconfig.php');
			}elseif($admin == "bannedmaps"){
				include('sources/admin/bannedmaps.php');
			}
			else{header("Location: ?base=admin");}
			if($admin!=""){
			echo "<hr/>";
			include("sources/structure/footer.php");
			}
		}else{
			redirect("?base=main");
		}
	}else{
		redirect("?base=main");
	}
}else{
	redirect("?base=main");
}

?>