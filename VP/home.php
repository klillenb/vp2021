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
	require("page_header.php");
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<title><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</title>
	<meta charset="utf-8">
</head>
<body>
	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
		<p>See veebileht tehti <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudi</a> veebiprogrammeerimise tunnis ja <em>ei sisalda tõsiseltvõetavat sisu</em>!</p>
		<p><small>Loodetavasti saan serveriga ühendust</small></p>
	</h1>
	<hr>
	<ul>
		<li><a href="?list_films=1">Filmide nimekiri</a></li>
		<li><a href="?list_movies=1">Filmide nimekiri (parem versioon)</a></li>
		<li><a href="?add_films=1">Filmide lisamine</a></li>
		<li><a href="movie_relations.php">Filmi info seostamine</a></li>
		<li><a href="user_profile.php">Kasutajaprofiil</a></li>
		<li><a href="?logout=1">Logi välja</a></li>
	</ul>
	
</body>
</html>
