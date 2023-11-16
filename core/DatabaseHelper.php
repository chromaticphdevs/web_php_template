<?php 
	namespace Core;
	class DatabaseHelper {
		
		public $databaseInstance = null;
		public $lastInsertedId = null;

		public function __construct() {
			$this->databaseInstance = Database::getInstance();
		}

		public function insert($tableName, $fieldsAndValues) {
			$fields = array_keys($fieldsAndValues);
			$values = array_values($fieldsAndValues);

			$cleansedValues = [] ;
			$retunData = [];

			foreach($values as $key => $val) {
				$cleansedValues[] = str_escape($val);
			}
			foreach($fields as $key => $field) {

				$retunData[$field] = $cleansedValues[$key]; 
			}

			$sql = "INSERT INTO $tableName(".implode(",", $fields).")
				VALUES('".implode("','", $cleansedValues)."')";
			
			try{
				$query = $this->databaseInstance->query($sql);
				$this->lastInsertedId = $this->databaseInstance->conn->insert_id;
				return $query;
			}catch(\Exception $e) {
				dump($this->databaseInstance->conn->error);
			}
		}

		public function update($tableName , $fieldsAndValues , $where = null)
		{
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

			$sql = " UPDATE $tableName set ";

			$count = 0;
			
			foreach($fields as $key => $field) {

				if($count < $key) {
					$sql .=',';
					$count++;
				}

				$sql .= " {$field} = '{$cleansedValues[$key]}' ";
			}

			if($where != null) {
				$sql .= " WHERE $where";
			}

			try{
				return $this->databaseInstance->query($sql);
			}catch(\Exception $e) {
				dump($e->getMessage());
			}
		}

		public function delete($tableName , $where)
		{
			$sql = "DELETE FROM $tableName where $where";
			try{
				return $this->databaseInstance->query($sql);
			}catch(\Exception $e) {
				dump($e->getMessage());
			}
		}

		public function getAll($tableName,$condition = '', $order = '', $limit = '',$column = '*') {
			$result = $this->databaseInstance->query(
				"SELECT {$column} FROM {$tableName}
					{$condition} {$order} {$limit}"
			);
			return $this->databaseInstance->resultSet();
		}

		public function getAllCount($tableName, $condition, $order, $limit) {
			$result = $this->databaseInstance->query(
				"SELECT count(*) as total_count 
					FROM {$tableName}
					{$condition} {$order} {$limit}"
			);

			echo "SELECT count(*) as total_count 
			FROM {$tableName}
			{$condition} {$order} {$limit}";
			die();

			return $this->databaseInstance->single()['total_count'] ?? 0;
		}
	}	