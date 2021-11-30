<?php
	require_once("fnc_party.php");
	require_once("../../../config.php");
	
	$first_name = $last_name = $student_code = null;
	$notice = null;
	
	if(isset($_POST["party_submit"])){
		if(empty($_POST["first_name_input"])){
			$notice = "Eesnimi sisestamata!";
		} else {
			$first_name = $_POST["first_name_input"];
		}
		if(empty($_POST["surname_input"])){
			$notice .= " Perekonnanimi sisestamata!";
		} else {
			$last_name = $_POST["surname_input"];
		}
		if(isset($_POST["student_code_input"])){
			if(empty($_POST["student_code_input"])){
				$notice .= " Üliõpilaskood sisestamata!";
			} else if(strlen($_POST["student_code_input"]) != 6) {
				$notice .= " Sisestasid vale koodi!";
			}
			$student_code = $_POST["student_code_input"];
		}
		
		if(empty($notice)){
			$notice = register_to_party($first_name, $last_name, $student_code);
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
	<h1>Peole registreerimine</h1>
	<p>Siin saad end peole registreerida, toimumiskoht ja -aeg on veel selgitamisel!</p>
	<p>Hetkel on registreerunud: <?php echo read_attending_people();?></p>
	<hr>
	
	<h3>Registreeri ennast peole:</h3>
		
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label for="first_name_input">Eesnimi:</label><br>
	  <input name="first_name_input" id="first_name_input" type="text" value="<?php echo $first_name;?>">
	  <br>
      <label for="surname_input">Perekonnanimi:</label>
	  <br>
	  <input name="surname_input" id="surname_input" type="text" value="<?php echo $last_name;?>">
	  <br>
	  <label for="student_code">Üliõpilaskood:</label>
	  <br>
	  <input name="student_code_input" id="student_code_input" type="text" value="<?php echo $student_code;?>">
	  <br>
	  <input type="submit" name="party_submit" value="Registreeri peole!">
	 </form>
	 <span><?php echo $notice;?></span>
	 <br>
	 <hr>
	<a href="cancel_registration.php">Tühista registreerumine</a>
	<br>
	<a href="party_admin.php">Ainult korraldajale info</a>
</body>
</html>
