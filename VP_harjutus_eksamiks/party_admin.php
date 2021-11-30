<?php
	require_once("../../../config.php");
	require_once("fnc_party.php");
	$list_html = read_party_list();
	$selected_person = null;
	$person_error = null;
	$payment = null;
	$notice = null;
	
	if(isset($_POST["person_submit"])){
		if(isset($_POST["person_input"]) and !empty($_POST["person_input"])){
			$selected_person = filter_var($_POST["person_input"], FILTER_VALIDATE_INT);
		} else {
			$person_error = "Isik on valimata!";
		}
		if(empty($person_error)){
			$person_error = list_person($selected_person);
		}
	}
	
	if(isset($_POST["payment_submit"])){
		if(isset($_POST["payment_input"]) and !empty($_POST["payment_input"])){
			$selected_person = filter_var($_POST["person_input"], FILTER_VALIDATE_INT);
			$notice = save_payment_info($selected_person, $_POST["payment_input"]);
		}
	}
?>

<!DOCTYPE html>
<html lang="et">
<head>
	<title>Pidu</title>
	<meta charset="utf-8">
</head>
<body>
	<h1>Peo info</h1>
	<p>Tähtis info peo kohta!</p>
	<hr>
	<h3>Peole registreerunud inimeste nimekiri</h3>
	<?php echo $list_html;?>
	<hr>
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label for="person_input">Isik</label>
		<select name="person_input" id="person_input">
			<option value="" selected disabled>Vali isik</option>
			<?php echo read_all_person($selected_person);?>
		</select>
		
		<input type="submit" name="person_submit" id="person_submit" value="Vali isik"><span id="notice"></span>
		<?php echo $person_error;?>
		<input type="submit" name="payment_submit" id="payment_submit" value="Salvesta"><span id="notice"></span>
		<?php echo $notice;?>
	</form>
	
	<hr>
	<a href="page.php">Peole registreerimine</a>
</body>
</html>