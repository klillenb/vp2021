<?php
	$author_name = "Kert Lillenberk";
	$weekday_names_et = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	$full_time_now = date("d.m.Y H:i:s");
	$hour_now = date("H");
	//echo $hour_now;
	$weekday_now = date("N");
	//echo $weekday_now;
	$day_category = "ebamäärane";
	if($weekday_now <= 5){
		$day_category = "koolipäev";
	} 
	else {
		$day_category = "puhkepäev";
	}
	if($day_category = "koolipäev"){
		if($hour_now<8 or $hour_now>=23){
			$activity = "uneaeg";
		}
		if($hour_now>=8 and $hour_now<=18){
			$activity = "tundide aeg";
		}
		if($hour_now>=18 and $hour_now<23){
			$activity = "vabaaeg";
		}
	}
	if($day_category = "puhkepäev"){
		if($hour_now>10 and $hour_now<24){
			$activity = "vabaaeg";
		}
		else{
			$activity = "uneaeg";
		}
	}
	//echo $day_category;
	//loeme fotode kataloogi sisu
	$photo_dir = "photos/";
	$allowed_photo_types = ["image/jpeg", "image/png"];
	//$all_files = scandir($photo_dir);
	$all_files = array_slice(scandir($photo_dir), 2);
	//echo $all_files vale
	//var_dump($all_files);
	//$only_files = array_slice($all_files, 2);
	//var_dump($only_files);
	
	//sõelun välja ainult lubatud pildid
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
	//echo $limit;
	$pic_num = mt_rand(0, $limit - 1);
	$pic_file = $all_files[$pic_num];
	// <img src = "pilt.jpg" alt = "Tallinna Ülikool">
	$pic_html = '<img src = "' .$photo_dir .$pic_file .'" alt = "Tallinna Ülikool">';
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
		<img src="tlu_terra.jpg" alt="Tallinna Ülikooli Terra hoone" width="800" height="300">
	<p>Lehe avamise hetk: <span><?php echo $weekday_names_et[$weekday_now - 1] .", " .$full_time_now. ", on " .$day_category. " ja " .$activity; ?></span></p>
	<h2>Veebiprogrammeerimise tunnis õpime järgnevat:</h2>
	<ul>
		<li>PHP programmeerimiskeel</li>
		<li>HTML programmeerimiskeel</li>
		<li>SQL päringukeel</li>
		<li>jne</li>
	</ul>
	<?php echo $pic_html; ?>
</body>


</html>
