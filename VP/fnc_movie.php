<?php
	$database = "if21_kert_lil";
	
	function read_all_person($selected){
		$html = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		//<option value="x" selected>Eesnimi Perekonnanimi</option>
		$stmt = $conn->prepare("SELECT id, first_name, last_name, birth_date FROM person");
		$stmt->bind_result($id_from_db, $first_name_from_db, $last_name_from_db, $birth_date_from_db);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<option value="' .$id_from_db .'"';
			if($selected == $id_from_db){
				$html .= " selected";
			}
			$html .= ">" .$first_name_from_db ." " .$last_name_from_db ." (" .$birth_date_from_db .")</option> \n";
		}
		$stmt->close();
		$conn->close();
		return $html;
	}
	
	function read_all_movies($selected){
		$html = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		//<option value="x" Nimi Aasta</option>
		$stmt = $conn->prepare("SELECT id, title, production_year FROM movie");
		$stmt->bind_result($id_from_db, $title_from_db, $production_year_from_db);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<option value="' .$id_from_db .'"';
			if($selected == $id_from_db){
				$html .= " selected";
			}
			$html .= ">" .$title_from_db ." (" .$production_year_from_db .")</option> \n";
		}
		$stmt->close();
		$conn->close();
		return $html;
	}
	
	function read_all_positions($selected){
		$html = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		//<option value="x" positsiooni nimi</option>
		$stmt = $conn->prepare("SELECT id, position_name FROM position");
		$stmt->bind_result($id_from_db, $position_name_from_db);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<option value="' .$id_from_db .'"';
			if($selected == $id_from_db){
				$html .= " selected";
			}
			$html .= ">" .$position_name_from_db ."</option> \n";
		}
		$stmt->close();
		$conn->close();
		return $html;
	}
	
    function store_person_in_movie($selected_person, $selected_movie, $selected_position, $role){
        $notice = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        //<option value="x" selected>Film</option>
        $stmt = $conn->prepare("SELECT id FROM person_in_movie WHERE person_id = ? AND movie_id = ? AND position_id = ? AND role = ?");
        $stmt->bind_param("iiis", $selected_person, $selected_movie, $selected_position, $role);
        $stmt->bind_result($id_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            //selline on olemas
            $notice = "Selline seos on juba olemas!";
        } else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO person_in_movie (person_id, movie_id, position_id, role) VALUES (?, ?, ?, ?)"); 
            $stmt->bind_param("iiis", $selected_person, $selected_movie, $selected_position, $role);
            if($stmt->execute()){
                $notice = "Uus seos edukalt salvestatud!";
            } else {
                $notice = "Uue seose salvestamisle tekkis viga: " .$stmt->error;
            }
        }
        $stmt->close();
        $conn->close();
        return $notice;
    }
	
	
	//------------Vana kraam----------------------------------------------------------------------------------------------------//
	function read_all_films(){
		//avan andmebaasi ühenduse		server, kasutaja, parool, andmebaas
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		//määrame vajaliku kodeeringu
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT * FROM film");
		//igaksjuhuks kui on vigu, väljastame need
		echo $conn->error;
		//seome tulemused muutujatega
		$stmt->bind_result($title_from_db, $year_from_db, $duration_from_db, $genre_from_db, $director_from_db, $studio_from_db);
		//käsk täita
		$stmt->execute();
		//fetch()
		//<h3>pealkiri</h3>
		//<ul>
		//<li>valmimisaasta: 1997</li>
		//...
		//</ul>
		$films_html = null;
		//while(tingimus){
			//mida teha...
		//}
		while($stmt->fetch()){
			$films_html .= "<h3>" .$title_from_db ."</h3> \n";
			$films_html .= "<ul> \n";
			$films_html .= "<li> Valmimisaasta: " .$year_from_db ."</li> \n";
			$films_html .= "<li> Kestus: " .$duration_from_db ."</li> \n";
			$films_html .= "<li> Žanr: " .$genre_from_db ."</li> \n";
			$films_html .= "<li> Lavastaja: " .$director_from_db ."</li> \n";
			$films_html .= "<li> tootja: " .$studio_from_db ."</li> \n";
			$films_html .= "</ul> \n";
		}
		//sulgeme SQL käsu
		$stmt->close();
		//sulgeme andmebaasi ühenduse
		$conn->close();
		return $films_html;
	}
	
	function store_film($title_input, $year_input, $duration_input, $genre_input, $director_input, $studio_input){
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("INSERT INTO film (pealkiri, aasta, kestus, zanr, lavastaja, tootja) value(?,?,?,?,?,?)");
		echo $conn->error;
		//seon SQL käsu päris andmetega :i - integar, d - decimal, s - string
		$stmt->bind_param("siisss", $title_input, $year_input, $duration_input, $genre_input, $director_input, $studio_input);
		$success = null;
		if($stmt->execute()){
			$success = "Salvestamine õnnestus!";
		} else {
			$success = "Salvestamisel tekkis viga: " .$stmt->error;
		}
		
		$stmt->close();
		$conn->close();
		return $success;
	}
	
?>