<?php
	
	$author_name = "Kert Lillenberk";
	require_once("../../../config.php");
	//echo $server_host;
	require_once("fnc_film.php");
	
	$film_store_notice = null;
	
	$title_input_error = null;
	$inserted_title = null;
	$year_input_error = null;
	$inserted_year = null;
	$duration_input_error = null;
	$inserted_duration = 60;
	$genre_input_error = null;
	$inserted_genre = null;
	$director_input_error = null;
	$inserted_director = null;
	$studio_input_error = null;
	$inserted_studio = null;
	
	//kas püütakse salvestada
	if(isset($_POST["film_submit"])){
		
		//kontrollin et andmeid ikka sisestati
		if(!empty($_POST["title_input"]) and !empty($_POST["year_input"]) and !empty($_POST["genre_input"]) and !empty($_POST["director_input"]) and !empty($_POST["studio_input"])){
			
			$film_store_notice = store_film(test_input($_POST["title_input"]), test_input($_POST["year_input"]), test_input($_POST["duration_input"]), test_input($_POST["genre_input"]), test_input($_POST["director_input"]), test_input($_POST["studio_input"]));
		}else{
			$film_store_notice = "Osa andmeid puudu!";
			if (empty($_POST["title_input"])){
				$title_input_error = "Sisesta filmi pealkiri!";
			} else{
				$inserted_title = $_POST["title_input"];
			}
			if (empty($_POST["year_input"])){
				$year_input_error = "Sisesta filmi väljastusaasta!";
			} else {
				$inserted_year = $_POST["year_input"];
			}
			if ($_POST["duration_input"] <= 0){
				$duration_input_error = "Sisesta korrektne kestus!";
			} else {
				$inserted_duration = $_POST["duration_input"];
			}
			if (empty($_POST["genre_input"])){
				$genre_input_error = "Sisesta filmi žanr!";
			} else {
				$inserted_genre = $_POST["genre_input"];
			}
			if (empty($_POST["director_input"])){
				$director_input_error = "Sisesta filmi lavastaja!";
			} else {
				$inserted_director = $_POST["director_input"];
			}
			if (empty($_POST["studio_input"])){
				$studio_input_error = "Sisesta filmi tootja!";
			} else {
				$inserted_studio = $_POST["studio_input"];
			}
		}
	}
	
	require("page_header.php");
	
?>

<body>
	<h1><?php echo $author_name;?>, veebiprogrammeerimine</h1>
		<p>See veebileht tehti <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudi</a> veebiprogrammeerimise tunnis ja <em>ei sisalda tõsiseltvõetavat sisu</em>!</p>
		<p><small>Loodetavasti saan serveriga ühendust</small></p>
	</h1>
	<hr>
	<h2>Eesti filmide lisamine andmebaasi</h2>
	<form method = "POST">
		<label for="title_input">Filmi pealkiri</label>
		<input type="text" name="title_input" id="title_input" placeholder="Filmi pealkiri" value="<?php echo $inserted_title ?>" ><?php echo $title_input_error?>
		<br>
		<label for="year_input">Valmimisaasta</label>
		<input type="number" name="year_input" id="year_input" value="<?php echo $inserted_year ?>"><?php echo $year_input_error?>
		<br>
		<label for="duration_input">Kestus</label>
		<input type="number" name="duration_input" id="duration_input" min="1" value="<?php echo $inserted_duration ?>" max="600"><?php echo $duration_input_error?>
		<br>
		<label for="genre_input">Filmi žanr</label>
		<input type="text" name="genre_input" id="genre_input" placeholder="Filmi žanr" value="<?php echo $inserted_genre ?>"><?php echo $genre_input_error?>
		<br>
		<label for="director_input">Filmi lavastaja</label>
		<input type="text" name="director_input" id="director_input" placeholder="Filmi lavastaja" value="<?php echo $inserted_director ?>"><?php echo $director_input_error?>
		<br>
		<label for="studio_input">Filmi tootja</label>
		<input type="text" name="studio_input" id="studio_input" placeholder="Filmi tootja" value="<?php echo $inserted_studio ?>"><?php echo $studio_input_error?>
		<br>
		<input type="submit" name="film_submit" value="Salvesta">
	</form>
	<span><?php echo $film_store_notice; ?></span>
	<ul>
		<li><a href="home.php">Avalehele</a></li>
		<li><a href="?logout=1">Logi välja</a></li>
	</ul>
</body>
</html>
