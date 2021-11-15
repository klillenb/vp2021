<?php
	//alustame sessiooni
	session_start();
	//kas on sisselogitud
	if(!isset($_SESSION["user_id"])){
		header("Location: page2.php");
	}

	require_once("../../../config.php");
	
	$database = "if21_kert_lil";
	
	$id = $_GET["photo"];
	$rating = $_GET["rating"];
	
	$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
	$conn->set_charset("utf8");
	$stmt = $conn->prepare("INSERT INTO vp_photoratings (photoid, userid, rating) VALUES (?,?,?)");
	echo $conn->error;
	$stmt->bind_param("iii", $id, $_SESSION["user_id"], $rating);
	$stmt->exeute();
	$stmt->close();
	
	//loeme keskmise hinde
	
	$stmt = $conn->prepare("SELECT AVG(rating) as avgValue FROM vp_photoratings WHERE photoid = ?");
	echo $stmt->error;
	$stmt->bind_param("i", $photoid);
	$stmt->bind_result($score);
	$stmt->execute();
	$stmt->fetch();
	$stmt->close();
	$conn->close();
	echo round($score, 2);

?>