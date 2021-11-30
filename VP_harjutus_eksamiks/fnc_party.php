<?php
	$database = "if21_kert_lil";
	
	function register_to_party($first_name, $last_name, $student_code){
		$notice = null;
		$payment = 2;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("INSERT INTO vp_pidu (first_name, last_name, student_code, payment) VALUES (?,?,?,?)");
		$stmt->bind_param("sssi", $first_name, $last_name, $student_code, $payment);
		echo $stmt->error;
		if($stmt->execute()){
			$notice = "Registreerimine õnnestus!";
		} else {
			$notice = "Registreerimisel tekkis viga!" .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function read_party_list(){
		$list_html = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT first_name, last_name, payment FROM vp_pidu WHERE cancel IS NULL");
		$stmt->bind_result($first_name_from_db, $last_name_from_db, $payment);
		echo $stmt->error;
		$stmt->execute();
		$list_html .= "<ol> \n";
		while($stmt->fetch()){
			$list_html .= "<li>" .$first_name_from_db ." " .$last_name_from_db;
			if(empty($payment) or $payment == 2){
				$list_html .= ", <strong>maksmata</strong></li> \n";
			} else {
				$list_html .= ", <strong>makstud</strong></li> \n";
			}
		}
		$list_html .= "</ol> \n";
		if(empty($list_html)){
			$list_html = "Registreerunuid pole!";
		}
		$stmt->close();
		$conn->close();
		return $list_html;
		
	}
	
	function list_person($selected_person){
		$list_html = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT first_name, last_name, student_code, payment FROM vp_pidu WHERE id = ?");
		$stmt->bind_param("i", $selected_person);
		$stmt->bind_result($first_name_from_db, $last_name_from_db, $student_code_from_db, $payment);
		$stmt->execute();
		if($stmt->fetch()){
			$list_html .= "<p>Nimi: " .$first_name_from_db ." " .$last_name_from_db ."</p> \n";
			$list_html .= "<p>üliõpilaskood: " .$student_code_from_db ."<p> \n";
			$list_html .= '<input type="radio" name="payment_input" id="payment_input_1" value=1';
			if($payment == 1){
				$list_html .= " checked>";
			}
			$list_html .= '<label for="payment_input_1">Makstud</label><br>';
			$list_html .= '<input type="radio" name="payment_input" id="payment_input_2" value=2';
			if($payment == 2 or !empty($payment)){
				$list_html .=" checked>";
			}
			$list_html .= '<label for="payment_input_2">Maksmata</label><br>';
		} else {
			$list_html = "Tekkis viga: " .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $list_html;
	}


	function read_all_person($selected){
		$html = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id, first_name, last_name FROM vp_pidu WHERE cancel IS NULL");
		$stmt->bind_result($id_from_db, $first_name_from_db, $last_name_from_db);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<option value=' .$id_from_db .'';
			if($selected == $id_from_db){
				$html .= " selected";
			}
			$html .= ">" .$first_name_from_db ." " .$last_name_from_db ."</option> \n";
		}
		$stmt->close();
		$conn->close();
		return $html;
	}
	
	function save_payment_info($selected_person, $payment){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("UPDATE vp_pidu SET payment = ? WHERE id = ? AND cancel IS NULL");
		$stmt->bind_param("ii", $payment, $selected_person);
		if($stmt->execute()){
			$notice = "Edukalt salvestatud!";
		} else {
			$notice = "Tekkis viga: " .$stmt->error .$conn->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function read_attending_people(){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT COUNT(id) FROM vp_pidu WHERE cancel IS NULL");
		$stmt->bind_result($attending_amount);
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();
		$stmt = $conn->prepare("SELECT COUNT(payment) FROM vp_pidu WHERE payment=1 AND cancel IS NULL");
		$stmt->bind_result($paid_amount);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = $attending_amount ." peolist, kellest " .$paid_amount ." tuleb kindlasti!";
		} else {
			$notice = "Tekkis viga: " .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function cancel_registration($student_code){
		$response = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("UPDATE vp_pidu SET cancel = NOW() WHERE student_code = ?");
		$stmt->bind_param("s", $student_code);
		if($stmt->execute()){
			$response = "Edukalt tühistatud!";
		} else {
			$response = "Tekkis viga: " .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $response;
	}
?>