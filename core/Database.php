<?php 
	namespace Core;

	class Database {
		
		public $conn;
		//for static call
		public static $instance = null;
		private $result;

		public function __construct() {

			$databaseConf = _config('database')[ENV_DATABASE];

			$localhost = $databaseConf['hostname'];
			$username = $databaseConf['db_user'];
			$password = $databaseConf['db_password'];
			$dbname = $databaseConf['db_name'];


			// db connection
			$this->conn = new \mysqli($localhost, $username, $password, $dbname);
			// check connection
			if($this->conn->connect_error) {
			  die("Connection Failed : " . $this->conn->connect_error);
			}
		}

		public static function getInstance() {
			if(is_null(self::$instance)) {
				self::$instance = new Database();
			}
			return self::$instance;
		}

		public function query($sql) {
			$this->result  = $this->conn->query($sql);
			return $this->result;
		}

		public function single() {
			return $this->result->fetch_assoc();
			// $this->_cleanQuery();
		}

		public function resultSet() {
			$retVal = [];
			while($row = $this->result->fetch_assoc()){
				$retVal[] = $row;
			}
			// $this->_cleanQuery();
			return $retVal;
		}

		private function _cleanQuery() {
			$this->result->free_result();
			$this->conn->close();
		}
	}	