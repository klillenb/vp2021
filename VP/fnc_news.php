<?php
	$database = "if21_kert_lil";
	
	function upload_news($news_title, $news_content, $expire_date, $news_photo){
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$notice = null;
		if($news_photo){
			$stmt = $conn->prepare("SELECT id FROM vp_newsphotos WHERE added = (SELECT MAX(added) FROM vp_newsphotos)");
			$stmt->bind_result($photo_id_from_db);
			$stmt->execute();
			echo $stmt->error;
			if($stmt->fetch){
			$photo_id = $photo_id_from_db;
			}	
			$stmt->close();
			$conn->close();
		}
		$stmt = $conn->prepare("INSERT INTO vp_news (userid, title, content, expire, photoid) VALUES (?,?,?,?,?)");
		$stmt->bind_param("isssi", $_SESSION["user_id"], $news_title, $news_content, $expire_date, $photo_id);
		if($stmt->execute()){
			$notice = "Salvestamine õnnestus!";
		} else {
			$notice = "Salvestamine ebaõnnestus!" .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function save_news_image_to_db($filename){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("INSERT INTO vp_newsphotos (filename, userid) VALUES (?,?)");
		$stmt->bind_param("si", $filename, $_SESSION["user_id"]);
		if($stmt->execute()){
			$notice = "Foto edukalt üleslaetud!";
		} else {
			$notice = "Foto üleslaadimisel tekkis viga!" .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
