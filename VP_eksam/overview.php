<?php

	require_once("../../../config.php");
	require_once("fnc_general.php");
	
	$notice = null;
	
	if(isset($_POST["car_submit"])){
		if(isset($_POST["car_input"]) and !empty($_POST["car_input"])){
			$selected_car = filter_var($_POST["car_input"], FILTER_VALIDATE_INT);
		} else {
			$notice = "Auto nr valimata!";
		}
		
		if(empty($notice)){
			$notice = show_transported_grains($selected_car);
		}
	}
	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<title>Veebiprogrammeerimine</title>
	<meta charset="utf-8">
</head>
	<h1>Ülevaade viljaveost</h1>
		<p>Siin näed kõiki koormaid ja veedetud vilja kogumassi</p>
		<p>Kokku on transporditud <?php echo transported_sum();?> kg vilja</p>
	<hr>

	<h2>Viljavedu</h2>
	<?php echo show_grain_transport();?>
	<hr>
	<h3>Filtreeritud ülevaade</h3>
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="car_input">Auto nr: </label>
		<select name="car_input" id="car_input">
			<option value="" selected disabled>Vali auto nr</option>
			<?php echo read_all_cars($selected_car);?>
		</select>
		<input type="submit" name="car_submit" value="Vali">
	</form>
	<?php echo $notice;?>
	<hr>
	<a href="page.php">Tagasi viljaveo sisestuse lehele</a>

</body>
</html>