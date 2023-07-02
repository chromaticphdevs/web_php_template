<?php 

	namespace Core;


	class Database {
		
		private $conn;
		//for static call
		public static $instance = null;

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
			} else {
			  // echo "Successfully connected";
			}
		}

		public function query($sql) {
			return $this->conn->query($sql);
		}

		public static function getInstance() {

		}
	}