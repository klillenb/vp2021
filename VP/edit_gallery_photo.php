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
	require_once("fnc_gallery.php");
	require_once("../../../config.php");
	require_once("fnc_general.php");
	
	$update_photo_notice = null;
	
	if(isset($_GET["photo"]) and !empty($_GET["photo"])){
		//loeme pildi ja teeme vormi kuhu loeme pildi andmed
		$_SESSION["photo"] = $_GET["photo"];
		$validate_user = validate_user_photo($_SESSION["photo"]);
		//var_dump($validate_user);
		if(!empty($validate_user)){
			$alt_text = $validate_user[0];
			$privacy = $validate_user[1];
		} else {
			$update_photo_notice = "Valitud pildi andmeid ei saa muuta";
		}
	}/* else {
		//tagasi eelmisena vaadatud lehele
		header("Location: gallery_home.php");
	}*/
	
	if(isset($_POST["photo_submit"])){
		if(!empty($_POST["alt_input"]) and !empty($_POST["privacy_input"])){
			$alt_text = test_input(filter_var($_POST["alt_input"]), FILTER_SANITIZE_STRING);
			$update_photo_notice = update_photo_data($alt_text, $_POST["privacy_input"]);
			$privacy = $_POST["privacy_input"];
		} else {
			$update_photo_notice = "Pildiandmete uuendamine ebaõnnestus!";
		}
	}
	
	if(isset($_POST["photo_delete"])){
		$update_photo_notice = delete_photo($_SESSION["photo"]);
	}
	
	require("page_header.php");
?>

	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
		<p>See veebileht tehti <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudi</a> veebiprogrammeerimise tunnis ja <em>ei sisalda tõsiseltvõetavat sisu</em>!</p>
		<p><small>Loodetavasti saan serveriga ühendust</small></p>
	</h1>
	<hr>
	<ul>
		<li><a href="home.php">Avalehele</a></li>
		<li><a href="?logout=1">Logi välja</a></li>
	</ul>
	<hr>
	<h2>Foto andmete muutmine</h2>
	<?php //echo read_public_photo_thumbs($page_limit, $page); ?>
	<?php echo show_photo(); ?>
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="alt_input">Alternatiivtekst (alt): </label>
		<input type="text" name="alt_input" id="alt_input" placeholder="Alternatiivtekst" value="<?php echo $alt_text; ?>">
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_1" value="1" <?php if($privacy == 1){echo " checked"; }?>>
		<label for="privacy_input_1">Privaatne (ainult mina näen)</label>
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_2" value="2" <?php if($privacy == 2){echo " checked"; }?>>
		<label for="privacy_input_2">Sisseloginud kasutajatele</label>
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_3" value="3" <?php if($privacy == 3){echo " checked"; }?>>
		<label for="privacy_input_3">Avalik (kõik näevad)</label>
		<br>
		<input type="submit" name="photo_submit" value="Uuenda pildi andmeid">
	</form>
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<input type="submit" name="photo_delete" value="Kustuta foto">
	</form>
	<?php echo $update_photo_notice; ?>
</body>
</html>
