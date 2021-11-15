<?php
	$database = "if21_kert_lil";
	require_once("../../../config.php");
	require_once("fnc_general.php");
	
	function show_latest_public_photo(){
		$photo_html = null;
		$privacy = 3;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT filename, alttext FROM vp_photos WHERE id = (SELECT MAX(id) FROM vp_photos WHERE privacy = ? AND deleted IS NULL)");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filename_from_db, $alttext_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			//<img src="kataloog.file" alt="tekst">
			$photo_html = '<img src="' .$GLOBALS["photo_normal_upload_dir"] .$filename_from_db .'" alt="';
			if(empty($alttext_from_db)){
				$photo_html .= "Üleslaetud foto";
			} else {
				$photo_html .= $alttext_from_db;
			}
		$photo_html .= '">' ."\n";
		}
		if(empty($photo_html)){
			$photo_html = "<p>Kahjuks avalikke fotosid üles laetud pole!</p> \n";
		}
		
		$stmt->close();
		$conn->close();
		return $photo_html;
	}
	
/*	function read_public_photo_thumbs(){
		$gallery_html = null;
		$privacy = 2;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT filename, alttext FROM vp_photos WHERE privacy >= ? AND deleted IS NULL ORDER BY id DESC LIMIT 3,3");
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filename_from_db, $alttext_from_db);
		$stmt->execute();
		while($stmt->fetch()){
			//<img src="kataloog.file" alt="tekst">
			$gallery_html .= '<img src="' .$GLOBALS["photo_thumbnail_upload_dir"] .$filename_from_db .'" alt="';
			if(empty($alttext_from_db)){
				$gallery_html .= "Üleslaetud foto";
			} else {
				$gallery_html .= $alttext_from_db;
			}
		$gallery_html .= '">' ."\n";
		}
		if(empty($gallery_html)){
			$gallery_html = "<p>Kahjuks avalikke fotosid üles laetud pole!</p> \n";
		}
		
		$stmt->close();
		$conn->close();
		return $gallery_html;
	} */
	
	function read_public_photo_thumbs($page_limit, $page){
		$gallery_html = null;
		$privacy = 2;
		$skip = ($page - 1) * $page_limit;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id, filename, alttext, created FROM vp_photos WHERE privacy >= ? AND deleted IS NULL ORDER BY id DESC LIMIT ?,?");
		$stmt->bind_param("iii", $privacy, $skip, $page_limit);
		$stmt->bind_result($id_from_db, $filename_from_db, $alttext_from_db, $date);
		$stmt->execute();
		while($stmt->fetch()){
			//<div class="thumbgallery">
			//<img src="kataloog.file" alt="tekst" class="thumbs" data-id="x" data-fn="see.jpg">
			//</div>
			$gallery_html .= '<div class="thumbgallery">' ."\n";
			$gallery_html .= '<img src="' .$GLOBALS["photo_thumbnail_upload_dir"] .$filename_from_db .'" alt="';
			if(empty($alttext_from_db)){
				$gallery_html .= "Üleslaetud foto";
			} else {
				$gallery_html .= $alttext_from_db;
			}
			$gallery_html .= '" class="thumbs" data-id="' .$id_from_db .'" data-fn="' .$filename_from_db .'">' ."\n";
			$gallery_html .= "<p>" .date_to_est_format($date) ."</p>";
			$gallery_html .= "</div> \n";
		}
		if(empty($gallery_html)){
			$gallery_html = "<p>Kahjuks avalikke fotosid üles laetud pole!</p> \n";
		}
		
		$stmt->close();
		$conn->close();
		return $gallery_html;
	}
	
	function read_own_photo_thumbs($page_limit, $page){
		$gallery_html = null;
		$skip = ($page - 1) * $page_limit;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id, filename, alttext, created FROM vp_photos WHERE userid = ? AND deleted IS NULL ORDER BY id DESC LIMIT ?,?");
		$stmt->bind_param("iii", $_SESSION["user_id"], $skip, $page_limit);
		$stmt->bind_result($id_from_db, $filename_from_db, $alttext_from_db, $date);
		$stmt->execute();
		while($stmt->fetch()){
			//<div class="thumbgallery">
			//<img src="kataloog.file" alt="tekst">
			//</div>
			$gallery_html .= '<div class="thumbgallery">' ."\n";
			$gallery_html .= '<a href="edit_gallery_photo.php?photo=' .$id_from_db .'">';
			$gallery_html .= '<img src="' .$GLOBALS["photo_thumbnail_upload_dir"] .$filename_from_db .'" alt="';
			if(empty($alttext_from_db)){
				$gallery_html .= "Üleslaetud foto";
			} else {
				$gallery_html .= $alttext_from_db;
			}
			$gallery_html .= '" class="thumbs">' ."\n";
			$gallery_html .= "</a> \n";
			$gallery_html .= "<p><b>Lisatud: </b>" .date_to_est_format($date) ."</p>";
			$gallery_html .= "</div> \n";

		}
		if(empty($gallery_html)){
			$gallery_html = "<p>Kahjuks avalikke fotosid üles laetud pole!</p> \n";
		}
		
		$stmt->close();
		$conn->close();
		return $gallery_html;
	}
	
	function count_public_photos($privacy){
		$photo_count = 0;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT COUNT(id) FROM vp_photos WHERE privacy >= ? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($count);
		$stmt->execute();
		if($stmt->fetch()){
			$photo_count = $count;
		}
		$stmt->close();
		$conn->close();
		return $photo_count;
	}
	
	function show_photo(){
		$photo_html = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT filename, alttext, privacy FROM vp_photos WHERE id = ? AND userid = ? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("ii", $_SESSION["photo"], $_SESSION["user_id"]);
		$stmt->bind_result($filename_from_db, $alttext_from_db, $privacy_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			//<img src="kataloog.file" alt="tekst">
			$photo_html = '<img src="' .$GLOBALS["photo_normal_upload_dir"] .$filename_from_db .'" alt="';
			if(empty($alttext_from_db)){
				$photo_html .= "Üleslaetud foto";
			} else {
				$photo_html .= $alttext_from_db;
			}
		$photo_html .= '">' ."\n";
		}
		if(empty($photo_html)){
			$photo_html = "<p>Kahjuks avalikke fotosid üles laetud pole!</p> \n";
		}
		$stmt->close();
		$conn->close();
		return $photo_html;
	}

	/*function show_photo_alttext(){
		$alt_text = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT alttext FROM vp_photos WHERE id = ? AND userid = ? AND deleted IS NULL");
		echo $stmt->error;
		$stmt->bind_param("ii", $_GET["photo"], $_SESSION["user_id"]);
		$stmt->bind_result($alttext_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$alt_text = $alttext_from_db;
		} else {
			$alt_text = "Üleslaetud foto";
		}
		$stmt->close();
		$conn->close();
		return $alt_text;
	}*/
	
	/*function show_photo_privacy(){
		$privacy = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT privacy FROM vp_photos WHERE id = ? AND userid = ? AND deleted IS NULL");
		echo $stmt->error;
		$stmt->bind_param("ii", $_GET["photo"], $_SESSION["user_id"]);
		$stmt->bind_result($privacy_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$privacy = $privacy_from_db;
		} else {
			$privacy = "Tekkis viga: " .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $privacy;
	}*/
	
	function update_photo_data($alt_text, $privacy){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("UPDATE vp_photos SET alttext = ?, privacy = ? WHERE id = ? AND userid = ? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("siii", $alt_text, $privacy, $_SESSION["photo"], $_SESSION["user_id"]);
		echo $stmt->error;
		if($stmt->execute()){
			$notice = "Edukalt salvestatud!";
		} else {
			$notice = "Salvestamisel tekkis viga!" .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function validate_user_photo($photo_id){
		$alt_text = null;
		$privacy = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT alttext, privacy FROM vp_photos WHERE id = ? AND userid = ?");
		echo $conn->error;
		$stmt->bind_param("ii", $photo_id, $_SESSION["user_id"]);
		$stmt->bind_result($alttext_from_db, $privacy_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$alt_text = $alttext_from_db;
			$privacy = $privacy_from_db;
		}
		$stmt->close();
		$conn->close();
		return [$alt_text, $privacy];
	}
	
	function delete_photo($photo_id){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("UPDATE vp_photos SET deleted = NOW() WHERE id = ? AND userid = ?");
		$stmt->bind_param("ii", $photo_id, $_SESSION["user_id"]);
		echo $stmt->error;
		echo $conn->error;
		if($stmt->execute()){
			$notice = "Foto edukalt kustutatud!";
		} else {
			$notice = "Tekkis viga: " .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
?>
