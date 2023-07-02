<?php 
	use Core\Database;
	use Core\DatabaseHelper;
	require_once 'core/Database.php';
	require_once 'core/DatabaseHelper.php';
	
	class _Service{

		protected $dbHelper;
		protected $databaseInstance;

		/*
		*must have in every extended service
		*/
		public $_tableName;
		// public $_columnFillables = [];

		public function __construct() {
			$this->databaseInstance = new Database();
			$this->databaseHelper = new DatabaseHelper($this->databaseInstance);
		}

		protected function _getFillablesOnly($datas) {
			$return = [];

			foreach($datas as $key => $row) {
				$key = trim($key);
				if(in_array($key, $this->_columnFillables)) {
					$return[$key] = trim($row);
				}
			}
			return $return;
		}

		public function getAll($params = []) {
			$query = $this->databaseInstance->query("SELECT * FROM {$this->_tableName}");
			$retVal = [];

			while($row = $query->fetch_assoc()) {
				$retVal[] = $row;
			}

			return $retVal;
		}

		public function get($id) {
			$query = $this->databaseInstance->query("SELECT * FROM {$this->_tableName} WHERE id = '{$id}'");
			$retVal = [];

			while($row = $query->fetch_assoc()) {
				$retVal[] = $row;
			}

			return $retVal[0] ?? false;
		}

		public function single($params = []) {
			$where = $params['where'];

			$query = $this->databaseInstance->query(
				"SELECT * FROM {$this->_tableName}
					WHERE username = '{$where['username']}' "
			);

			$retVal = [];

			while($row = $query->fetch_assoc()) {
				$retVal[] = $row;
			}

			return $retVal[0] ?? false;
		}
	}