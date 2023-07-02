<?php 
	namespace Core;
	class DatabaseHelper {
		
		private $databaseInstance = null;

		public function __construct($databaseInstance) {
			$this->databaseInstance = $databaseInstance;
		}

		public function insert($tableName, $fieldsAndValues) {
			$fields = array_keys($fieldsAndValues);
			$values = array_values($fieldsAndValues);

			$cleansedValues = [] ;
			$retunData = [];

			foreach($values as $key => $val) {
				$cleansedValues[] = str_escape($val , FILTER_SANITIZE_STRING);
			}
			foreach($fields as $key => $field) {

				$retunData[$field] = $cleansedValues[$key]; 
			}

			$sql = "INSERT INTO $tableName(".implode(",", $fields).")
				VALUES('".implode("','", $cleansedValues)."')";
			
			try{
				return $this->databaseInstance->query($sql);
			}catch(Exception $e) {
				dump($e->getMessage());
			}
		}
	}