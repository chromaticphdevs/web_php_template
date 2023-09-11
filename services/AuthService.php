<?php 

	class AuthService extends _Service {
		public $_tableName = 'users';
		public $_columnFillables = [
			'username',
			'password'
		];


		public function authenticate($username, $password) {

			//database checks
			$user = parent::single([
				'where' => [
					'username' => $username
				]
			]);

			if(!$user) {
				// parent::addError("NO user found");
				echo ' user ' .$username . ' not exists .';
				return false;
			}

			//check passsword

			if($user['password'] != trim($password)) {
				echo 'invalid password';
				return false;
			}

			return $user;
		}

		public function store($data) {
			$fillablesOnly = parent::_getFillablesOnly($data);
			return $this->databaseHelper->insert($this->_tableName,$fillablesOnly);
		}
	}