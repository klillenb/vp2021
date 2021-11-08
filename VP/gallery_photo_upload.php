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
	require_once("fnc_photo_upload.php");
	//fotode üleslaadimise klass
	require_once("classes/Photoupload.class.php");

	$photo_upload_notice = null;

	$photo_error = null;
	$alt_text = null;
	$file_type = null;
	$file_name = null;
	$privacy = 1;
	$photo_filename_prefix = "vp_";
	$photo_upload_size_limit = 1024 * 1024;
	$photo_size_ratio = 1;
	$normal_photo_max_height = 400;
	$normal_photo_max_width = 600;
	$thumbnail_width = $thumbnail_height = 100;
	$watermark_file = "./pics/vp_logo_w100_overlay.png";
	
	
	if(isset($_POST["photo_submit"])){
		if(isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])){

			//kas on lubatud suurusega?
			if(empty($photo_error) and $_FILES["photo_input"]["size"] > $photo_upload_size_limit){
				$photo_error .= "Valitud fail on liiga suur!";
			}
			
			//kas alt tekst on 
			 if(isset($_POST["alt_input"]) and !empty($_POST["alt_input"])){
				// echo "Kontrollin alti"; 
                $alt_text = test_input(filter_var($_POST["alt_input"], FILTER_SANITIZE_STRING));
				//echo $alt_text;
                if(empty($alt_text)){
					$photo_error .= "Alternatiivtekst on lisamata!";
                }
            }
			
			if(isset($_POST["privacy_input"]) and !empty($_POST["privacy_input"])){
				$privacy = filter_var($_POST["privacy_input"], FILTER_VALIDATE_INT);
			}
			if(empty($privacy)){
				$photo_error .= " Privaatsus on määramata!";
			}
        
			
			if(empty($photo_error)){
				//võtame kasutusele klassi, kuni klass ise tüüpi kindlaks ei tee, saadan failitüübi
				$photo_upload = new Photoupload($_FILES["photo_input"]);
				$photo_error .= $photo_upload->check_size($photo_upload_size_limit);
				
				//moodustan failinime, kasutame eesliidet
				$photo_upload->file_name($photo_filename_prefix);
				
				$photo_upload->resize_image($normal_photo_max_width, $normal_photo_max_height);
				
				//lisan vesimärgi
				$photo_upload->add_watermark($watermark_file);
				

				$photo_upload_notice = "Vähendatud pildi " .$photo_upload->save_image($photo_normal_upload_dir .$file_name);
				
				//teen pisipildi
				$photo_upload->resize_image($thumbnail_width, $thumbnail_height);
                $photo_upload_notice .= " Pisipildi " .$photo_upload->save_image($photo_thumbnail_upload_dir .$file_name);
				
				$photo_upload->move_original_image($photo_orig_upload_dir .$photo_upload->file_name);
				
				//kirjutame andmetabelisse
				$photo_upload_notice .= " " .save_image_to_db($photo_upload->file_name, $alt_text, $privacy);
				unset($photo_upload);
			
				$alt_text = null;
				$privacy = 1;
			}
		} else {
			$photo_error = "Pildifaili pole valitud!";
		}
		if(empty($photo_upload_notice)){
		$photo_upload_notice = $photo_error;
		}
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
	<h3>Foto üleslaadimine</h3>
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label for="photo_input">Vali pildi fail (max 1 MB):</label>
		<input type="file" name="photo_input" id="photo_input">
		<br>
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
		<input type="submit" name="photo_submit" value="Lae pilt üles">
	</form>
	<span><?php echo $photo_upload_notice;?></span>

	
</body>
</html>