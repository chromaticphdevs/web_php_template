<?php 

    class AccountService extends _Service {
        public $_tableName = 'a_accounts';

        public function authenticate($key,$secret) 
        {
            $account = $this->single([
                'where' => [
                    'email' => $key
                ]
            ]);

            if(!$account) {
                $this->addError("User {$key} not found.");
                return false;
            } else {
                //check password

                if(!isEqual($account['password'], $secret)) {
                    $this->addError("Incorrect password.");
                    return false;
                }else{
                    //set user session
                    Session::set('auth', $account);
                    return true;
                }
            }
        }
    }