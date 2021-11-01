<?php
	//alustame sessiooni
	session_start();
	require_once("fnc_user.php");
	require_once("fnc_general.php");
	require_once("../../../config.php");
	require_once("fnc_gallery.php");
	
	
	$author_name = "Kert Lillenberk";
	$todays_evaluation = null; // $todays_evaluation="";
	$inserted_adjective = null;
	$adjective_error = null;
	
	$email = null;
	$email_error = null;
	$password_error = null;
	$notice = null;
	
	//kontrollin kas on vajutatud Submit nuppu
	if(isset($_POST["todays_adjective_input"])){
		//echo "Klikiti nuppu";
		//kas midagi kirjutati ka
		if(!empty($_POST["adjective_input"])){
			$todays_evaluation = "<p>Tänane päev on <strong>" .$_POST["adjective_input"]."</strong>.<hr></p>";
			$inserted_adjective = $_POST["adjective_input"];
		} else {
			$adjective_error = "Palun kirjuta tänase päeva kohta sobiv omadussõna!";
		}
	}
	//var_dump($_POST);
	$pic_num = null;
	$photo_dir = "photos/";
	$allowed_photo_types = ["image/jpeg", "image/png"];
	$all_files = array_slice(scandir($photo_dir), 2);
	$photo_files = [];
	foreach($all_files as $file){
		$file_info = getimagesize($photo_dir .$file);
		if(isset($file_info["mime"])){
			if(in_array($file_info["mime"], $allowed_photo_types)){
				array_push($photo_files, $file);
			}
		}
	}
	
	$limit = count($all_files);
	$pic_num = mt_rand(0, $limit - 1);
	
	if(isset($_POST["photo_select_submit"])){
		$pic_num = $_POST["photo_select"];
	}
	
	$pic_file_html = null;
	$pic_file = $photo_files[$pic_num];
	$pic_html = '<img src = "' .$photo_dir .$pic_file .'" alt = "Tallinna Ülikool">';
	
	$pic_file_html = "\n <p>".$pic_file ."</p> \n";
	
	//fotode nimekiri
	//<p>valida on järgmised fotod: <strong>foto1.jpg</strong>, <strong>foto2.jpg</strong>, <strong>foto33.jpg</strong>.</p>
	//<ul>valida on järgmised fotod: <li>foto1.jpg</li>, <li>foto2.jpg</li>, <li>foto33.jpg</li></ul>
	$list_html = "<ul> \n";
	for($i = 0; $i < $limit; $i ++){
		$list_html .= "<li>" .$photo_files[$i] ."</li>";
	}
	$list_html .= "</ul>";
	
	$photo_select_html = '<select name="photo_select">' ."\n";
	for($i = 0; $i < $limit; $i ++){
		$photo_select_html .= "\t \t \t" .'<option value="' .$i .'"';
		if($i == $pic_num){
			$photo_select_html .= " selected";
		}
		$photo_select_html .= ">" .$photo_files[$i] ."</option> \n";
	}
	$photo_select_html .= "\t \t </select> \n";
	
	//sisselogimine
	if(isset($_POST["login_submit"])){
		if(isset($_POST["email_input"]) and !empty($_POST["email_input"])){
			$email = filter_var($_POST["email_input"], FILTER_VALIDATE_EMAIL);
			if(strlen($email) < 5){
				$email_error = "Palun sisesta kasutajatunnus (e-mail)!";
			}
		} else {
			$email_error = "Palun sisesta kasutajatunnus (e-mail)!";
		}
		if(isset($_POST["password_input"]) and !empty($_POST["password_input"])){
			if(strlen($_POST["password_input"]) < 8){
				$password_error = "Sisestatud salasõna on liiga lühike!";
			}
		} else {
			$password_error = "Palun sisesta salasõna!";
		}
		if(empty($email_error) and empty($password_error)){
			$notice = sign_in($email, $_POST["password_input"]);
		} else {
			$notice = $email_error ." " .$password_error;
		}
    }

?>
<!DOCTYPE html>
<html lang="et">
<head>
	<title><?php echo $author_name;?>, veebiprogrammeerimine</title>
	<meta charset="utf-8">
</head>
<body>
	<h1><?php echo $author_name;?>, veebiprogrammeerimine</h1>
		<p>See veebileht tehti <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudi</a> veebiprogrammeerimise tunnis ja <em>ei sisalda tõsiseltvõetavat sisu</em>!</p>
		<p><small>Loodetavasti saan serveriga ühendust</small></p>
	</h1>
	<hr>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<input type="email" name="email_input" placeholder="email ehk kasutajatunnus" value="<?php echo $email ?>">
		<input type="password" name="password_input" placeholder="salasõna">
		<input type="submit" name="login_submit" value="Logi sisse">
		<span><?php echo $notice?></span>
		
	</hr>
	<p>Loo endale <a href="add_user.php"> kasutajakonto </a></p>
	</hr>
	<form method="POST">
		<input type="text" name="adjective_input" placeholder="omadussõna tänase kohta" value="<?php echo $inserted_adjective ?>">
		<input type="submit" name="todays_adjective_input" value="Saada ära!">
		<span><?php echo $adjective_error; ?></span>
	</form>
	<hr>
	<?php
	echo $todays_evaluation;
		
	?>
	<hr>
	<?php echo show_latest_public_photo();?>
	<form method="POST">
	<?php echo $photo_select_html; ?>
		<input type="submit" name="photo_select_submit" value="Näita valitud fotot">
	</form>
	<?php
	echo $pic_html; 
	echo $list_html;
	?>
</body>


</html>
