<?php

	require_once("../../../config.php");
	require_once("fnc_general.php");
	
	$car = null;
	$error = null;
	$notice = null;
	$update = null;
	
	if(isset($_POST["entering_car_grain_submit"])){
		
		if(isset($_POST["car_input"]) and !empty($_POST["car_input"])){
			$car = filter_var($_POST["car_input"], FILTER_SANITIZE_STRING);
		} else {
			$error = "Auto registrinumber sisestamata!";
		}
		
		if(isset($_POST["grain_input"]) and !empty($_POST["grain_input"])){
			$grain_type = filter_var($_POST["grain_input"], FILTER_SANITIZE_STRING);
		} else {
			$error .= " Viljatüüp sisestamata!";
		}
		
		if(isset($_POST["entering_weight_input"]) and !empty($_POST["entering_weight_input"])){
			$entering_weight = filter_var($_POST["entering_weight_input"], FILTER_SANITIZE_STRING);
		} else {
			$error .= " Sisenemismass sisestamata!";
		}
		
		if(isset($_POST["exit_weight_input"]) and !empty($_POST["exit_weight_input"])){
			$exit_weight = filter_var($_POST["exit_weight_input"], FILTER_SANITIZE_STRING);
		} else {
			$exit_weight = null;
		}
		
		if(empty($error)){
			$notice = entering_grain_transport($car, $grain_type, $entering_weight, $exit_weight);
		} else {
			$notice = $error;
		}
	}
	
	if(isset($_POST["car_submit"])){
		if(isset($_POST["car_input"]) and !empty($_POST["car_input"])){
			$selected_car = filter_var($_POST["car_input"], FILTER_VALIDATE_INT);
		} else {
			$update = "Auto nr valimata!";
		}
		
		if(isset($_POST["update_exit_weight_input"]) and !empty($_POST["update_exit_weight_input"])){
			$update_exit_weight = filter_var($_POST["update_exit_weight_input"], FILTER_SANITIZE_STRING);
		} else {
			$update = "Väljumismass sisestamata!";
		}
		
		if(empty($update)){
			$update = change_exit_weight($selected_car, $update_exit_weight);
		}
	}

	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<title>Veebiprogrammeerimine</title>
	<meta charset="utf-8">
</head>
	<h1>Viljavedu</h1>
		<p>Sisesta auto nr, koorma nimetus, sisenemismass ja väljumismass</p>
	<hr>

	<h2>Viljavedu</h2>
	<h3>Auto:</h3>
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="car_input">Auto registrinumber:</label>
		<input type="text" name="car_input" id="car_input" placeholder="Auto registrinumber" value="">
		<br>
		<label for="grain_input">Mis vili: </label>
		<input type="text" name="grain_input" id="grain_input" placeholder="Vilja tüüp" value="">
		<br>
		<label for="entering_weight_input"> Sisenemismass kilogrammides: </label>
        <input type="text" name="entering_weight_input" id="entering_weight_input" placeholder="Sisenemismass" value="">
		<br>
		<label for="exit_weight_input"> Väljumismass kilogrammides: </label>
        <input type="text" name="exit_weight_input" id="exit_weight_input" placeholder="Väljumismass" value="">
		<br>
		<input type="submit" name="entering_car_grain_submit" value="Salvesta">
	</form>
	<?php echo $notice;?>
	
	<h3>Väljumismassita autode info uuendamine:</h3>
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="car_input">Auto nr: </label>
		<select name="car_input" id="car_input">
			<option value="" selected disabled>Vali auto nr</option>
			<?php echo cars_without_exit_weight($selected_car);?>
		</select>
		<input type="text" name="update_exit_weight_input" id="update_exit_weight_input" placeholder="Väljumismass" value="">
		<input type="submit" name="car_submit" value="Salvesta">
	</form>
	<?php echo $update;?>
	<hr>
	<a href="overview.php">Ülevaade</a>

</body>
</html>