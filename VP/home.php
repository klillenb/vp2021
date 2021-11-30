<?php
	//alustame sessiooni
	//session_start();
	require_once("use_session.php");
	require_once("../../../config.php");
	require_once("fnc_news.php");
	//kas on sisselogitud
	if(!isset($_SESSION["user_id"])){
		header("Location: page2.php");
	}
	//väljalogimine
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page2.php");
	}
	//filmide nimekiri
	if(isset($_GET["list_films"])){
		header("Location: list_films.php");
	}
	
	if(isset($_GET["list_movies"])){
		header("Location: list_movies.php");
	}
	//filmide lisamine
	if(isset($_GET["add_films"])){
		header("Location: add_films.php");
	}
	
	//testime klassi
	/*require_once("classes/Test.class.php");
	$my_test_object = new Test(33);
	echo "Avalik muutuja " .$my_test_object->non_secret_value;
	//echo "Privaatne muutuja " .$my_test_object->secret_value;
	//$my_test_object->multiply;
	$my_test_object->reveal();
	unset($my_test_object);*/
	
	//küpsiste ehk cookie'de näide
	setcookie("vpvisitor", $_SESSION["first_name"] ." " .$_SESSION["last_name"], time() + (86400 * 3), "/~kertlil/vp2021", "greeny.cs.tlu.ee", isset($_SERVER["HTTPS"]), true);
	$last_visitor = null;
	if(isset($_COOKIE["vpvisitor"])){
		$last_visitor = "<p>Viimati külastas lehte: " .$_COOKIE["vpvisitor"] ."</p> \n";
	} else {
		$last_visitor = "<p>Küpsiseid ei leitud, viimane külastaja pole teada.</p> \n";
	}
	//var_dump($_COOKIE);
	
	//küpsise kustutamiseks määratakse varasem (enne praegust hetke) aegumine
	//time() - 3600;
	
	require("page_header.php");
?>

	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
		<p>See veebileht tehti <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudi</a> veebiprogrammeerimise tunnis ja <em>ei sisalda tõsiseltvõetavat sisu</em>!</p>
		<p><small>Loodetavasti saan serveriga ühendust</small></p>
	</h1>
	<hr>
	<?php echo $last_visitor; ?>
	<hr>
	<ul>
		<li><a href="?list_films=1">Filmide nimekiri (versioon 0)</a></li>
		<li><a href="?list_movies=1">Filmide nimekiri (versioon 1)</a></li>
		<li><a href="?add_films=1">Filmide lisamine</a></li>
		<li><a href="movie_relations.php">Filmi info seostamine</a></li>
		<li><a href="gallery_photo_upload.php">Fotode üleslaadimine</a></li>
		<li><a href="gallery_public.php">Sisselogitud kasutajate jaoks fotode galerii</a></li>
		<li><a href="gallery_home.php">Minu oma fotogalerii</a></li>
		<li><a href="user_profile.php">Kasutajaprofiil</a></li>
		<li><a href="add_news.php">Uudise lisamine</a></li>
		<li><a href="?logout=1">Logi välja</a></li>
	</ul>
	<br>
	<h2>Uudised</h2>
	<?php echo latest_news(5);?>
	
</body>
</html>
