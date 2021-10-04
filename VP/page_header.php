<?php
	$css_color = null;
	/*<style>
		body {
			background-color: #FFFFFF;
			color: #000000;
			}
	</style>*/
	$css_color .= "<style> \n";
    $css_color .= "body { \n";
    $css_color .= "\t background-color: " .$_SESSION["bg_color"] ."; \n";
    $css_color .= "\t color: " .$_SESSION["text_color"] ."; \n";
    $css_color .= "} \n";
    $css_color .= "</style> \n";
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<title><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</title>
	<meta charset="utf-8">
	<?php echo $css_color; ?>
</head>
<body>
	<img src="./pics/vp_banner.png" alt="Veebiproge bänner">