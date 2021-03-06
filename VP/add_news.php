<?php
	//alustame sessiooni
	require_once("use_session.php");
	
	require_once("../../../config.php");
	require_once("fnc_general.php");
	require_once("fnc_photo_upload.php");
	require_once("fnc_news.php");
	//fotode üleslaadimise klass
	require_once("classes/Photoupload.class.php");
	

	$news_notice = null;
	$news_title = null;
	$news = null;
	$news_error = null;
	
	//uudise aegumine
	$expire = new DateTime("now");
	$expire->add(new DateInterval("P7D"));
	$expire_date = date_format($expire, "Y-m-d");
	
	$photo_file = null;
	$photo_filename_prefix = "vpnews_";
	$photo_upload_size_limit = 1024 * 1024;
	$allowed_photo_types = ["image/jpeg", "image/png", "image/gif"];
	

	$normal_photo_max_height = 400;
	$normal_photo_max_width = 600;
	$thumbnail_width = $thumbnail_height = 100;
	
	
	if(isset($_POST["news_submit"])){
		//uudise tekst sisaldab nüüd HTML märgendeid (näiteks <b> ...)
		//kindlasti tuleks kasutada meie funktsiooni test_input()
		//selles on ka htmlspecialchars funktsioon, mis kodeerib HTML erimärgid ringi, ohutuks("<" -> &lt)
		//pärast, uudise näitemisel, et HTML taastuks, on vaja: htmlspecialchars_decode()
		//kui on ka foto valitud, salvestage see esimesena, ka andmetabelisse. Siis saate kohe ka tema id kätte: $photo_id = $conn->insert_id;
		//uudise näitamisel tuleb arvestada ka aegumist
		//$today = date("Y-m-d");
		//SQL lauses	WHERE added >= ?
		if(empty($_POST["title_input"])){
			$news_error = "Uudise pealkiri puudu!";
		} else {
			$news_title = test_input(filter_var($_POST["title_input"], FILTER_SANITIZE_STRING));
		}
		if(empty($_POST["news_input"])){
			$news_error .= " Uudise sisu on puudu!";
		} else {
			$news_content = test_input(filter_var($_POST["news_input"], FILTER_SANITIZE_STRING));
		}
		if(empty($_POST["expire_input"])){
			$news_error .= " Palun vali viimase esitamise kuupäev!";
		} else {
			$expire_date = $_POST["expire_input"];
		}
		if($expire_date < date("Y-m-d")){
			$news_error .= " Aegumistähtaeg on minevikus!";
		}
		
		//kas foto on valitud
		if(isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])){
			$photo_upload = new Photoupload($_FILES["photo_input"]);
			if(empty($photo_upload->error)){
				$photo_upload ->check_allowed_type($allowed_photo_types);
				if(empty($photo_upload->error)){
					$photo_upload->check_size($photo_upload_size_limit);
					if(empty($photo_upload->error) and empty($news_error)){
						$photo_upload->file_name($photo_filename_prefix);
						$photo_upload->resize_image($normal_photo_max_width, $normal_photo_max_height);
						$news_notice = "Uudise pildi " .$photo_upload->save_image($photo_news_upload_dir .$photo_upload->file_name);
						$photo_file .= $photo_upload->file_name;
						//echo $photo_file;
					}
				}
			}
			$news_error .= $photo_upload->error;
			unset($photo_upload);
		}

		
		if(empty($news_error)){
			$news_notice = upload_news($news_title, $news, $expire_date, $photo_file);
		}
	}
	
	$to_head = '<script src="javascript/checkFileSize.js" defer></script>' ."\n";
	$to_head = '<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>';
	
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
	<h3>Uudise lisamine</h3>
	<span><?php echo $news_notice;?></span>
	<form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label for="title_input">Uudise pealkiri:</label>
		<input type="text" id="title_input" name="title_input" value="<?php echo $news_title;?>">
		<br>
		<label for="news_input">Uudis:</label>
		<textarea id="news_input" name="news_input"></textarea>
		<script>CKEDITOR.replace('news_input');</script>
		<br>
		<label for="expire_input">Viimane kuvamise kuupäev</label>
		<input id="expire_input" name="expire_input" type="date" value="<?php echo $expire_date; ?>">
		<br>
		<label for="photo_input">Vali pildi fail (max 1 MB):</label>
		<input type="file" name="photo_input" id="photo_input">
		<br>
		<input type="submit" name="news_submit" id="news_submit" value="Salvesta uudis"><span id="notice"></span>
	</form>
	<span><?php echo $news_notice;?></span>

	
</body>
</html>