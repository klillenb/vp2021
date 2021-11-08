<?php
	class Photoupload{
		private $photo_to_upload;
		private $file_type;
		private $my_temp_image;
		private $my_new_temp_image;
		
		function __construct($photo, $type){
			$this->photo_to_upload = $photo;
			$this->file_type = $type;//hiljem teeb klass selle ise kindlaks
			$this->my_temp_image = $this->create_image_from_file($this->photo_to_upload["tmp_name"] ,$this->file_type);
		}
		
		private function create_image_from_file($file, $file_type = "png"){
			$my_temp_image = null;
			//teen graafikaobjekti, image objekti
			if($file_type == "jpg"){
				$my_temp_image = imagecreatefromjpeg($file);
			}
			if($file_type == "png"){
				$my_temp_image = imagecreatefrompng($file);
			}
			if($file_type == "gif"){
				$my_temp_image = imagecreatefromgif($file);
			}
			
			return $my_temp_image;
		}
		
		public function resize_image($width, $height, $keep_orig_proportion = true){
			$image_w = imagesx($this->my_temp_image);
			$image_h = imagesy($this->my_temp_image);
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
			$this->my_new_temp_image = imagecreatetruecolor($new_w, $new_h);
			//säilitame png piltide puhul läbipasitvuse
			imagesavealpha($this->my_new_temp_image, true);
			$trans_color = imagecolorallocatealpha($this->my_new_temp_image, 0, 0, 0, 127);
			imagefill($this->my_new_temp_image, 0, 0, $trans_color);
			
			//kopeerime vajalikud pikslid uude objekti
			imagecopyresampled($this->my_new_temp_image, $this->my_temp_image, 0, 0, $cut_x, $cut_y, $new_w, $new_h, $cut_size_w, $cut_size_h);
		}
		
	}//class lõppeb


?>