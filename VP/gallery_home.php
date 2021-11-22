<?php
	//alustame sessiooni
	require_once("use_session.php");
	
	require_once("fnc_gallery.php");
	require_once("../../../config.php");
	
	$page = 1;
	$page_limit = 5;
	$photo_count = count_public_photos(2);
	//hoolitseme, et saaks liikuda vaid legaalsetele lehekülgedele, mis on olemas
	if(!isset($_GET["page"]) or $_GET["page"] < 1){
		$page = 1;
	} elseif(round($_GET["page"] - 1) * $page_limit >= $photo_count) {
		$page = ceil($photo_count / $page_limit);
	} else {
		$page = $_GET["page"];
	}
	
	$to_head = '<link rel="stylesheet" type="text/css" href="style/gallery.css">' ."\n";
	
	require("page_header.php");
?>

	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
		<p>See veebileht tehti <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudi</a> veebiprogrammeerimise tunnis ja <em>ei sisalda tõsiseltvõetavat sisu</em>!</p>
		<p><small>Loodetavasti saan serveriga ühendust</small></p>
	</h1>
	<hr>
	<ul>
		<li><a href="home.php">Avalehele</a></li>
		<li><a href="?logout=1">Logi välja</a></li>
	</ul>
	<hr>
	<h2>Minu oma fotod</h2>
	<p>
		<?php
			if($page > 1){
				echo '<span><a href="?page=' .($page - 1) .'">Eelmine leht</a></span> |' ."\n";
			} else {
				echo "<span>Eelmine leht</span> | \n";
			}
			if($page * $page_limit < $photo_count){
				echo '<span><a href="?page=' .($page + 1) .'">Järgmine leht</a></span>' ."\n";
			} else {
				echo "<span>Järgmine leht</span> \n";
			}
		?>
	</p>
	<?php echo read_own_photo_thumbs($page_limit, $page);?>
	
</body>
</html>
