<?php
	session_start();
	if(!isset($_SESSION["user_id"])){
		header("Location: page2.php");
	}
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page2.php");
	}
	require_once("../../../config.php");
	//echo $server_host;
	require_once("fnc_movie.php");
	$selected_movie = null;
	$movie_error = null;
	require("page_header.php");
	
	if(isset($_POST["movie_submit"])){
		if(isset($_POST["movie_input"]) and !empty($_POST["movie_input"])){
			$selected_movie = filter_var($_POST["movie_input"], FILTER_VALIDATE_INT);
		} else {
			$movie_error = "Film on valimata!";
		}
		if(empty($movie_error)){
			$movie_error = list_movie($selected_movie);
		}
	}
?>

<body>
	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
		<p>See veebileht tehti <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudi</a> veebiprogrammeerimise tunnis ja <em>ei sisalda tõsiseltvõetavat sisu</em>!</p>
		<p><small>Loodetavasti saan serveriga ühendust</small></p>
	</h1>
	<ul>
		<li><a href="home.php">Avalehele</a></li>
		<li><a href="?logout=1">Logi välja</a></li>
	</ul>
	<hr>
	<h2>Eesti filmide informatsioon</h2>
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="movie_input">Film</label>
		<select name="movie_input" id="movie_input">
			<option value="" selected disabled>Vali film</option>
			<?php echo read_all_movies($selected_movie);?>
		</select>
		<input type="submit" name="movie_submit" value="Vali">
	</form>
	<span><?php echo $movie_error; ?></span>
</body>


</html>
