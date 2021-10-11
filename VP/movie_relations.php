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
	
	require_once("../../../config.php");
	require_once("fnc_general.php");
	require_once("fnc_movie.php");

	$notice = null;
	$selected_person = null;
	$selected_movie = null;
	$selected_position = null;
	$role = null;
	$person_in_movie_error = null;
	
	$selected_person_for_photo = null;
	$photo_upload_notice = null;
	
	if(isset($_POST["person_in_movie_submit"])){
		if(isset($_POST["person_input"]) and !empty($_POST["person_input"])){
			$selected_person = filter_var($_POST["person_input"], FILTER_VALIDATE_INT);
		} else {
			$person_in_movie_error .= "Isik on valimata!";
		}
		
		if(isset($_POST["movie_input"]) and !empty($_POST["movie_input"])){
			$selected_movie = filter_var($_POST["movie_input"], FILTER_VALIDATE_INT);
		} else {
			$person_in_movie_error .= "Film on valimata!";
		}
		
		if(isset($_POST["position_input"]) and !empty($_POST["position_input"])){
			$selected_position = filter_var($_POST["position_input"], FILTER_VALIDATE_INT);
		} else {
			$person_in_movie_error .= "Isik on valimata!";
		}
		
		 if(isset($_POST["role_input"]) and !empty($_POST["role_input"])){
                $role = test_input(filter_var($_POST["role_input"], FILTER_SANITIZE_STRING));
                if(empty($role)){
                    $person_in_movie_error .= "Palun sisesta näitlejale normaalne rolli nimi!";
					
				}
			} else {
                $person_in_movie_error .= "Näitleja roll on sisestamata!";
		}
		if(empty($person_in_movie_error)){
			$person_in_movie_error = store_person_in_movie($selected_person, $selected_movie, $selected_position, $role);
        }
	}
	
	if(isset($_POST["person_photo_submit"])){
		var_dump($_POST);
	}
	
	require("page_header.php");
?>

	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
		<p>See veebileht tehti <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudi</a> veebiprogrammeerimise tunnis ja <em>ei sisalda tõsiseltvõetavat sisu</em>!</p>
		<p><small>Loodetavasti saan serveriga ühendust</small></p>
	<hr>
	<ul>
		<li><a href="home.php">Avalehele</a></li>
		<li><a href="?logout=1">Logi välja</a></li>
	</ul>
	<hr>
	<h2>Filmi info seostamine</h2>
	<h3>Film, inimene ja tema roll</h3>
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="person_input">Isik</label>
		<select name="person_input" id="person_input">
			<option value="" selected disabled>Vali isik</option>
			<?php echo read_all_person($selected_person);?>
		</select>
		<label for="movie_input">Film: </label>
		<select name="movie_input" id="movie_input">
			<option value="" selected disabled>Vali film</option>
			<?php echo read_all_movies($selected_movie);?>
		</select>
		<label for="position_input">Amet: </label>
		<select name="position_input" id="position_input">
			<option value="" selected disabled>Vali amet: </option>
			<?php echo read_all_positions($selected_position);?>
		</select>
		<label for="role_input"> Roll: </label>
        <input type="text" name="role_input" id="role_input" placeholder="Tegelase nimi" value="<?php echo $role; ?>">
		
		<input type="submit" name="person_in_movie_submit" value="Salvesta">
	</form>
	<span><?php echo $person_in_movie_error; ?></span>
	<span><?php echo $notice; ?></span>
	<hr>
	<h3>Filmitegelase foto</h3>
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label for="person_for_photo_input">Isik</label>
		<select name="person_for_photo_input" id="person_for_photo_input">
			<option value="" selected disabled>Vali isik</option>
			<?php echo read_all_person($selected_person_for_photo);?>
		</select>
		<label for="photo_input">Vali pildi fail!</label>
		<input type="file" name="photo_input" id="photo_input">
		<input type="submit" name="person_photo_submit" value="Lae pilt üles">
	</form>
	<span><?php echo $photo_upload_notice;?></span>
</body>
</html>