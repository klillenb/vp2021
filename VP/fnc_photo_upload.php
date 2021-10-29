<?php
	$database = "if21_kert_lil";
	
	function save_image($image, $file_type, $target){
		$notice = null;
		if($file_type == "jpg"){
			if(imagejpeg($image, $target, 90)){
				$notice = "Vähendatud pildi salvestamine õnnestus!";
			} else {
				$notice = "Vähendatud pildi salvestamisel tekkis tõrge!";
			}
		}
		if($file_type == "png"){
			if(imagepng($image, $target, 6)){
				$notice = "Vähendatud pildi salvestamine õnnestus!";
			} else {
				$notice = "Vähendatud pildi salvestamisel tekkis tõrge!";
			}
		}
		if($file_type == "gif"){
			if(imagegif($image, $target)){
				$notice = "Vähendatud pildi salvestamine õnnestus!";
			} else {
				$notice = "Vähendatud pildi salvestamisel tekkis tõrge!";
			}
		}
		
		return $notice;
	}
	
	
	function resize_image($my_temp_image){
		$watermark_file = "./pics/vp_logo_w100_overlay.png";
		$normal_photo_max_width = 600;
		$normal_photo_max_height = 400;
		//otsustame, kas tuleb laiuse või kõrguse järgi suhe
		//kõigepealt pildi mõõdud
		$image_width = imagesx($my_temp_image);
		$image_height = imagesy($my_temp_image);
		if($image_width / $normal_photo_max_width > $image_height / $normal_photo_max_height){
			$photo_size_ratio = $image_width / $normal_photo_max_width;
		} else {
			$photo_size_ratio = $image_height / $normal_photo_max_height;
		}
				
		//arvutame uue laiuse ja kõrguse
		$new_width = round($image_width / $photo_size_ratio);
		$new_height = round($image_height / $photo_size_ratio);
		//loome uue pikslikogumi
		$my_new_temp_image = imagecreatetruecolor($new_width, $new_height);
		//kopeerime vajalikud pikslid uude objekti
		imagecopyresampled($my_new_temp_image, $my_temp_image, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
				
		//lisan vesimärgi
		$watermark = imagecreatefrompng($watermark_file);
		$watermark_width = imagesx($watermark);
		$watermark_height = imagesy($watermark);
		$watermark_x = $new_width - $watermark_width - 10;
		$watermark_y = $new_height - $watermark_height - 10;
		imagecopy($my_new_temp_image, $watermark, $watermark_x, $watermark_y, 0, 0, $watermark_width, $watermark_height);
		imagedestroy($watermark);
		imagedestroy($my_temp_image);
		
		return $my_new_temp_image;
	}
	
	function save_image_to_db($file_name, $alt_text){
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("INSERT INTO vp_photos (userid, filename, alttext, privacy) VALUES (?,?,?,?)");
		$stmt->bind_param("issi", $_SESSION["user_id"], $file_name, $alt_text, $_POST["privacy_input"]);
		if($stmt->execute()){
			$notice = "Foto edukalt salvestatud!";
		} else {
			$notice = "Salvestamisel tekkis viga!" .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}