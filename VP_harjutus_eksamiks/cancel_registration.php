<?php
	require_once("fnc_party.php");
	require_once("../../../config.php");
	$notice = null;

	if(isset($_POST["cancel_submit"])){
		if(!empty($_POST["student_code_input"])){
			$student_code = filter_var($_POST["student_code_input"], FILTER_SANITIZE_STRING);
			$notice = cancel_registration($student_code);
		} else {
			$notice = "Sisesta oma üliõpilaskood!";
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
	<h1>Registreerimise tühistamine</h1>
	<p>Siin saad enda registreerimise tühistada</p>
	<h3>Registreeri ennast peole:</h3>
		
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label for="student_code_input">Üliõpilaskood:</label><br>
	  <input name="student_code_input" id="student_code_input" type="text" value="">
	  <br>
	  <input type="submit" name="cancel_submit" value="Tühista">
	</form>
	<span><?php echo $notice;?></span>
	<br>
	<hr>
	<a href="page.php">Registreeri ennast peole</a>
</body>
</html>