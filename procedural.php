<?php 
	
	function fetchProperties() {
		$sql = "SELECT * FROM properties";
		return $query->get($sql);
	}


	function fetchAds() {
		$sql = "SELECT * FROM ads";
		return $query->get($sql);
	}


	function giveMeTableData() {

	}

	function propertyService() {
		
	}
?>