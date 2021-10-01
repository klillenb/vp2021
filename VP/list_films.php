<?php
	session_start();
	if(!isset($_SESSION["user_id"])){
		header("Location: page2.php");
	}
	$author_name = "Kert Lillenberk";
	require_once("../../../config.php");
	//echo $server_host;
	require_once("fnc_film.php");
	$films_html = null;
	$films_html = read_all_films();
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<title><?php echo $author_name;?>, veebiprogrammeerimine</title>
	<meta charset="utf-8">
</head>
<body>
	<h1><?php echo $author_name;?>, veebiprogrammeerimine</h1>
		<p>See veebileht tehti <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudi</a> veebiprogrammeerimise tunnis ja <em>ei sisalda tõsiseltvõetavat sisu</em>!</p>
		<p><small>Loodetavasti saan serveriga ühendust</small></p>
	</h1>
	<hr>
	<h2>Eesti filmid</h2>
	<?php echo $films_html; ?>
</body>


</html>
