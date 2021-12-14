<?php
	$database = "if21_kert_lil";
	require_once("../../../config.php");
	
	
	function entering_grain_transport($car, $grain_type, $entering_weight, $exit_weight){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("INSERT INTO vp_grain_transport(car_id, grain_type, entering_weight, exit_weight) VALUES (?,?,?,?)");
		echo $conn->error;
		$stmt->bind_param("ssss", $car, $grain_type, $entering_weight, $exit_weight);
		if($stmt->execute()){
			$notice = "Salvestamine õnnestus!";
		} else {
			$notice = "Tekkis viga: " .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function show_grain_transport(){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT car_id, grain_type, entering_weight, exit_weight FROM vp_grain_transport");
		echo $conn->error;
		$stmt->bind_result($car_from_db, $grain_type_from_db, $entering_weight_from_db, $exit_weight_from_db);
		$stmt->execute();
		while($stmt->fetch()){
			$notice .= "<p>" .$car_from_db ."</p> \n";
			$notice .= "<ul> \n";
			$notice .= "<li> Viljatüüp: " .$grain_type_from_db ."</li> \n";
			$notice .= "<li> Sisenemismass: " .$entering_weight_from_db ." kg</li> \n";
			if(empty($exit_weight_from_db)){
				$notice .= "<li> Väljumismass: Auto alles tühjendamisel</li> \n";
			} else {
				$notice .= "<li> Väljumismass: " .$exit_weight_from_db ." kg</li> \n";
			}
			$notice .= "</ul> \n";
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function read_all_cars($selected){
		$html = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id, car_id FROM vp_grain_transport");
		echo $conn->error;
		$stmt->bind_result($id_from_db, $car_from_db);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<option value="' .$id_from_db .'"';
			if($selected == $id_from_db){
				$html .= " selected";
			}
			$html .= ">" .$car_from_db ."</option> \n";
		}
		$stmt->close();
		$conn->close();
		return $html;
	}
	
	function show_transported_grains($selected_car){
		$html = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT car_id, grain_type, entering_weight, exit_weight FROM vp_grain_transport WHERE id = ?");
		$stmt->bind_param("i", $selected_car);
		$stmt->bind_result($car_id_from_db, $grain_type_from_db, $entering_weight_from_db, $exit_weight_from_db);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= "<p> Auto nr: " .$car_id_from_db ."</p> \n";
			$html .= "<p> Viljatüüp: " .$grain_type_from_db ."</p> \n";
			$html .= "<p> Transporditud vilja mass: " .($entering_weight_from_db - $exit_weight_from_db) ." kg</p> \n";
		}
		$stmt->close();
		$conn->close();
		return $html;
	}
	
	function transported_sum(){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT entering_weight, exit_weight FROM vp_grain_transport");
		$stmt->bind_result($entering_weight_from_db, $exit_weight_from_db);
		$stmt->execute();
		while($stmt->fetch()){
			if($exit_weight_from_db != null){
			$notice += ($entering_weight_from_db - $exit_weight_from_db);
			}
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function cars_without_exit_weight($selected){
		$html = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id, car_id FROM vp_grain_transport WHERE exit_weight IS NULL");
		echo $conn->error;
		$stmt->bind_result($id_from_db, $car_from_db);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<option value="' .$id_from_db .'"';
			if($selected == $id_from_db){
				$html .= " selected";
			}
			$html .= ">" .$car_from_db ."</option> \n";
		}
		$stmt->close();
		$conn->close();
		return $html;
	}
	
	function change_exit_weight($selected_car, $update_exit_weight){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("UPDATE vp_grain_transport SET exit_weight = ? WHERE id = ?");
		$stmt->bind_param("si", $update_exit_weight, $selected_car);
		if($stmt->execute()){
			$notice = "Salvestamine õnnestus!";
		} else {
			$notice = "Tekkis viga: " .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}


?>