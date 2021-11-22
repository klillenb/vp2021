<?php
	require_once("classes/SessionManager.class.php");
	SessionManager::sessionStart("vp", 0, "/~kertlil/vp2021/", "greeny.cs.tlu.ee");
	
	if(!isset($_SESSION["user_id"])){
		header("Location: page2.php");
	}
	
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page2.php");
	}
?>