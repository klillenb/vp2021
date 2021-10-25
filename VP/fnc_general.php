<?php
	function test_input($data) {
		$data = htmlspecialchars($data);
		$data = stripslashes($data);
		$data = trim($data);
		return $data;
	}
	
	function date_to_est_format($value){
		$temp_date = new DateTime($value);
		return $temp_date->format("d.m.Y");
	}