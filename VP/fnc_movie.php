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
	
	function read_all_genres($selected){
		$html = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id, genre_name FROM genre");
		$stmt->bind_result($id_from_db, $genre_from_db);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<option value="' .$id_from_db .'"';
			if($selected == $id_from_db){
				$html .= " selected";
			}
			$html .= ">" .$genre_from_db ."</option \n";
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
	
	function read_person_name_for_filename($id){
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT first_name, last_name FROM person WHERE id = ?");
		$stmt->bind_param("i", $id);
		$stmt->bind_result($first_name_from_db, $last_name_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = $first_name_from_db ."_" .$last_name_from_db;
		} else {
			$notice = $id;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function store_person_photo($file_name, $person_id){
		$notice = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
		$stmt = $conn->prepare("INSERT INTO picture (picture_file_name, person_id) VALUES (?, ?)"); 
        $stmt->bind_param("si", $file_name, $person_id);
		if($stmt->execute()){
			$notice = "Uus foto edukalt salvestatud!";
		} else {
			$notice = "Foto üleslaadimisel tekkis viga: " .$stmt->error;
		}
		$stmt->close();
        $conn->close();
        return $notice;
	}
	
	function store_movie_genre($selected_movie, $selected_genre){
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id FROM movie_genre WHERE movie_id = ? AND genre_id = ?");
		$stmt->bind_param("ii", $selected_movie, $selected_genre);
		$stmt->bind_result($id_from_db);
		$stmt->execute();
		if($stmt->fetch){
			$notice = "Selline seos on juba olemas!";
		} else {
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO movie_genre (movie_id, genre_id) VALUES (?, ?)");
			$stmt->bind_param("ii", $selected_movie, $selected_genre);
			if($stmt->execute()){
				$notice = "Uus seos edukalt salvestatud!";
			} else {
				$notice = "Salvestamisel tekkis viga: " .$stmt->error;
			}
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	
	function list_movie($selected_movie){
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT title, production_year, duration, description FROM movie WHERE id = ?");
		$stmt->bind_param("i", $selected_movie);
		$stmt->bind_result($title_from_db, $year_from_db, $duration_from_db, $description_from_db);
		$stmt->execute();
		$movie_html = null;
		$duration = null;
		$duration_h = null;
		$duration_min = null;
		if($stmt->fetch()){
			$movie_html .= "<h3>" .$title_from_db ."</h3> \n";
			$movie_html .= "<ul> \n";
			$movie_html .= "<li> Valmimisaasta: " .$year_from_db ."</li> \n";
			if($duration_from_db < 60){
				$duration .= $duration_from_db ." minutit";
			} else {
				if($duration_from_db > 120){
					$duration_h = 2;
				} else {
					$duration_h = 1;
				}
				$duration .= $duration_h ." h ";
				$duration_min = $duration_from_db - 60;
				$duration .= $duration_min ." minutit";
			}
			$movie_html .= "<li> Kestus: " .$duration ."</li> \n";
			$movie_html .= "<li> Kirjeldus: " .$description_from_db ."</li> \n";
			$stmt->close();
			$stmt = $conn->prepare("SELECT g.genre_name FROM movie_genre AS mg JOIN genre AS g ON g.id = mg.genre_id WHERE movie_id = ?");
			$stmt->bind_param("i", $selected_movie);
			$stmt->bind_result($genre_from_db);
			$stmt->execute();
			$movie_html .= "<li> Žanr(id):</li> \n";
			$movie_html .= "</ul> \n";
			$movie_html .= "<ol> \n";
			while($stmt->fetch()){
				$movie_html .= "<li>" .$genre_from_db ."</li> \n";
			}
			$movie_html .= "</ol> \n";
		} else {
			$movie_html = "Tekkis viga: " .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $movie_html;
	}
	