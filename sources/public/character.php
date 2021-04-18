<?php

if (basename($_SERVER['PHP_SELF']) == 'character.php') {
    exit('403 - Access Forbidden');
}
if (isset($_GET['n'])) {
    $getchar = $mysqli->real_escape_string($_GET['n']);
    $c = $mysqli->query("SELECT * from characters WHERE name = '".$getchar."'")->fetch_assoc();
    if ($c) {
        echo '
			<h2 class="text-left">Character Info</h2><hr/>
			<div class="row">
				<div class="col-6 offset-3">
					<div class="card">
  						<div class="card-header">'.$c['name'].'</div>
						<div class="card-body">
							<div class="text-center">
								<img src="'.$siteurl.'assets/img/GD/create.php?name='.$c['name'].'" alt="'.$c['name'].'" img-fluid">
							</div>
							<hr/>
							<b>Job:</b> '.$c['job'].'<br/>
		';
        if ($servertype == 1) {
            echo '<b>Rebirths:</b> '.$c['reborns'].'<br/>';
        }
        echo '
						<b>Level:</b> '.$c['level'].'<br/>
						<b>EXP:</b> '.$c['exp'].'<br/>
					</div>
				</div>
			</div>
		</div>
		';
    } else {
        echo "<div class=\"alert alert-danger\">This character doesn't exist!</div>";
        redirect_wait5('?base=main');
    }
} else {
    echo "<div class=\"alert alert-danger\">This character doesn't exist!</div>";
    redirect_wait5('?base=main');
}
