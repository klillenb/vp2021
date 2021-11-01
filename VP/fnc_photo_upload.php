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
	
	
	function resize_image($my_temp_image, $width, $height, $keep_orig_proportion = true){
		$image_w = imagesx($my_temp_image);
		$image_h = imagesy($my_temp_image);
		$new_w = $width;
		$new_h = $height;
		$cut_x = 0;
		$cut_y = 0;
		$cut_size_w = $image_w;
		$cut_size_h = $image_h;

		if($width == $height){
			if($image_w > $image_h){
				$cut_size_w = $image_h;
				$cut_x = round(($image_w - $cut_size_w) / 2);
			} else {
				$cut_size_h = $image_w;
				$cut_y = round(($image_h - $cut_size_h) / 2);
			}	
		} elseif($keep_orig_proportion){//kui tuleb originaaproportsioone säilitada
			if($image_w / $width > $image_h / $height){
				$new_h = round($image_h / ($image_w / $width));
			} else {
				$new_w = round($image_w / ($image_h / $height));
			}
		} else { //kui on vaja kindlasti etteantud suurust, ehk pisut ka kärpida
			if($image_w / $width < $image_h / $height){
				$cut_size_h = round($image_w / $width * $height);
				$cut_y = round(($image_h - $cut_size_h) / 2);
			} else {
				$cut_size_w = round($image_h / $height * $width);
				$cut_x = round(($image_w - $cut_size_w) / 2);
			}
		}
		
		//loome uue pikslikogumi
		$my_new_temp_image = imagecreatetruecolor($new_w, $new_h);
		//säilitame png piltide puhul läbipasitvuse
		imagesavealpha($my_new_temp_image, true);
		$trans_color = imagecolorallocatealpha($my_new_temp_image, 0, 0, 0, 127);
		imagefill($my_new_temp_image, 0, 0, $trans_color);
		
		//kopeerime vajalikud pikslid uude objekti
		imagecopyresampled($my_new_temp_image, $my_temp_image, 0, 0, $cut_x, $cut_y, $new_w, $new_h, $cut_size_w, $cut_size_h);
				
		return $my_new_temp_image;
	}
	
	function add_watermark($my_new_temp_image, $watermark_file){
		//lisan vesimärgi
		$watermark = imagecreatefrompng($watermark_file);
		$watermark_width = imagesx($watermark);
		$watermark_height = imagesy($watermark);
		$watermark_x = imagesx($my_new_temp_image) - $watermark_width - 10;
		$watermark_y = imagesy($my_new_temp_image) - $watermark_height - 10;
		imagecopy($my_new_temp_image, $watermark, $watermark_x, $watermark_y, 0, 0, $watermark_width, $watermark_height);
		imagedestroy($watermark);
		
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