<?php
	//alustame sessiooni
	require_once("use_session.php");
	
	require_once("../../../config.php");
	require_once("fnc_general.php");
	require_once("fnc_user.php");

	$description = read_user_description();
	$notice = null;
	$profile_store = null;
	//$user_profile = load_profile();
	
	require("page_header.php");

	
	if(isset($_POST["profile_submit"])){
		$description = test_input($_POST["description_input"]);
		
		$profile_store = store_new_profile($description, $_POST["bg_color_input"], $_POST["text_color_input"]);
		$notice = "Salvestamine õnnestus!";
		$notice = "Salvestamine ebaõnnestus!";
		$_SESSION["bg_color"] = $_POST["bg_color_input"];
		$_SESSION["text_color"] = $_POST["text_color_input"];
	}
?>

	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
		<p>See veebileht tehti <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudi</a> veebiprogrammeerimise tunnis ja <em>ei sisalda tõsiseltvõetavat sisu</em>!</p>
		<p><small>Loodetavasti saan serveriga ühendust</small></p>
	<hr>
	<ul>
		<li><a href="home.php">Avalehele</a></li>
		<li><a href="?logout=1">Logi välja</a></li>
	</ul>
	</hr>
	<h2>Kasutajaprofiil</h2>
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="description_input">Minu lühikirjeldus</label>
		<br>
		<textarea name="description_input" id="description_input" rows="10" cols="80" placeholder="Minu lühikirjeldus ..."><?php echo $description; ?></textarea>
		<br>
		<label for="bg_color_input">taustavärv</label>
		<br>
		<input type="color" name="bg_color_input" id="bg_color_input" value="<?php echo $_SESSION["bg_color"]; ?>">
		<br>
		<label for="text_color_input">tekstivärv</label>
		<br>
		<input type="color" name="text_color_input" id="text_color_input" value="<?php echo $_SESSION["text_color"]; ?>"> 
		<br>
		<input type="submit" name="profile_submit" value="Salvesta">
	</form>
	<span><?php echo $notice; ?></span>
</body>
</html>