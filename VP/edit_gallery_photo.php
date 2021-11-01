<?php
	//alustame sessiooni
	session_start();
	//kas on sisselogitud
	if(!isset($_SESSION["user_id"])){
		header("Location: page2.php");
	}
	//väljalogimine
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page2.php");
	}
	require_once("fnc_gallery.php");
	require_once("../../../config.php");
	
	if(isset($_GET["photo"]) and !empty($_GET["photo"])){
		//loeme pildi ja teeme vormi kuhu loeme pildi andmed
	} else {
		//tagasi eelmisena vaadatud lehele
		header("Location: home.php");
	}
	
	//SET deleted = NOW()
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
	<h2>Foto andmete muutmine</h2>
	<?php //echo read_public_photo_thumbs($page_limit, $page); ?>
</body>
</html>
