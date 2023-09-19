<?php

    use function PHPSTORM_META\map;

    class AccountService extends _Service {
        public $_tableName = 'a_accounts';

        public $_columnFillables = [
            'email',
            'password',
            'membertype',
            'memberfname',
            'memberlname',
            'membercellno',
            'memberstatus',
            'membertag',
            'dateinserted',
            'memberinfo',
            'memberlicense',
            'memberabout',
            'memberviber',
            'memberlink',
            'memberviberno',
            'memberpagename'
        ];

        const DEFAULT_MEMBER_TYPE = '1'; //blue 
        const DEFAULT_MEMBER_STATUS = 'Verified';

        const TYPE_BROKER = 'broker';
        const TYPE_AGENT = 'agent';
        const TYPE_OWNER = 'owner';
        const TYPE_REPRESENTATIVE = "representative";

        public static function memberTypeInfos() {
            return [
                self::TYPE_BROKER => self::TYPE_BROKER,
                self::TYPE_AGENT => self::TYPE_AGENT,
                self::TYPE_OWNER => self::TYPE_OWNER,
                self::TYPE_REPRESENTATIVE => "owner's representative"
            ]; 
        }

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
                if(!password_verify($secret, $account['password'])){
                    $this->addError("Incorrect password.");
                    return false;
                } else {
                    $this->startAuth($account['recno']);
                    return true;
                }
            }
        }

        public function register($userData) {
            $errors = [];

            $this->accountValidation($userData, $errors);

            if(!empty($errors)) {
                $this->addError(implode(',', $errors));
                return false;
            }

            $userFilteredData = parent::_getFillablesOnly($userData);
            /**
             * export some values
             */
            $password = $this->_createPassword($userData['password']);
            $userFilteredData['password'] = $password;
            $userFilteredData['membertype'] = self::DEFAULT_MEMBER_TYPE;
            $userFilteredData['usercode'] = $this->_generateUserCode(substr($userFilteredData['memberfname'],0,1).substr($userFilteredData['memberlname'],0,1));
            $userFilteredData['memberstatus'] = self::DEFAULT_MEMBER_STATUS;
            $isOkay = parent::store($userFilteredData);

            if($isOkay) {
                $this->addRetVal('userid', $isOkay);
                return $isOkay;
            }

            return false;
        }
 
        public function updateAccountDetail($userData, $userRecno) {
            $errors = [];
            $this->accountValidation($userData, $errors);

            if(!empty($errors)) {
                $this->addError(implode(',', $errors));
                return false;
            }

            $userFilteredData = parent::_getFillablesOnly($userData);
            $isOkayUpdate = parent::update($userFilteredData, [
                'recno' => $userRecno
            ]);

            if($isOkayUpdate) {
                $this->addMessage("User detail updated");
                //update account is same account is logged in
                if(whoIs('recno') == $userRecno) {
                    $this->startAuth($userRecno);
                }
                return true;
            } else {
                $this->addError("Invalid User data");
                return false;
            }
        }

        public function updatePassword($userData, $userRecno) {
            //check password is matching
            $errors = [];

            $this->accountValidation($userData, $errors);

            if(!empty($errors)) {
                $this->addError(implode(',', $errors));
                return false;
            }

            //check user password
            $user = parent::single([
                'where' => [
                    'recno' => $userRecno
                ]
            ]);

            if(!password_verify($userData['old_password'], $user['password'])) {
                $this->addError("Unable to update password, Invalid password entered.");
                return false;
            }

            parent::update([
                'password' => $this->_createPassword($userData['password'])
            ], [
                'recno' => $userRecno
            ]);

            return true;
        }

        public function updateEmail($userData, $userRecno) {
            $errors = [];
            //append for email checking
            $this->accountValidation($userData, $errors);

            if(!empty($errors)) {
                $this->addError(implode(',', $errors));
                return false;
            }

            $user = parent::single([
                'where' => [
                    'recno' => $userRecno
                ]
            ]);

            if(!password_verify($userData['password'], $user['password'])) {
                $this->addError("Unable to update email, Invalid Password Entered.");
                return false;
            }

            $isOkay = parent::update([
                'email' => $userData['email']
            ], [
                'recno' => $userRecno
            ]);

            if($isOkay) {
                $this->addMessage("Email Updated");
                return true;
            } else {
                $this->addError("Something went wrong");
                return false;
            }
        }

        private function _validateEmail($email, $userRecno = '') {
            $user = false;
            if(!$this->_validateCharLength($email, 'EMAIL', 1)){
                $this->addError($this->getRetVal('VALIDATION'));
                return false;
            }

            if(empty($userRecno)) {
                $user = parent::single([
                    'where' => [
                        'email' => $email
                    ]
                ]);
            } else {
                $user = parent::single([
                    'where' => [
                        'email' => $email,
                        'recno' => [
                            'condition' => 'not equal',
                            'value' => $userRecno
                        ]
                    ]
                ]);
            }

            if($user) {
                $this->addRetVal('VALIDATION_EMAIL', "Email {$email} is already taken.");
                return false;
            }else{
                return true;
            }
        }
        /**
         * prefix must be 2 only
         */
        private function _generateUserCode($prefix = '') {
            if(strlen($prefix) < 2) {
                echo die("Invalid Prefix");
            }
            return strtoupper("{$prefix}".referenceSeries(1, 5, null));
        }

        private function _validateCharLength($str, $key, $requiredLength = 1) {

            if(strlen($str) < $requiredLength) {
                $this->addRetVal("VALIDATION", "{$key} must atleast be {$requiredLength} characters long.");
                return false;
            }

            return true;
        }

        /**
         * must be moved to auth service
         */
        public function startAuth($userRecno) {
            $user = parent::single([
                'where' => [
                    'recno' => $userRecno
                ]
            ]);

            if($user) {
                Session::set('auth', $user);
                return Session::get('auth');
            } else {
                return false;
            }
        }

        private function accountValidation($userData, &$errors) {
            //validations
            if(isset($userData['password'])) {
                if(!$this->_validateCharLength($userData['password'], 'Password', 4)) {
                    $errors [] = " Password must atleast be 4 characters long.";
                }
            }

            if(isset($userData['confirm_password']) && isset($userData['password'])) {
                if($userData['password'] != $userData['confirm_password']) {
                    $errors [] = " Password and Confirm password does not matched ";
                }
            }

            if(isset($userData['email'])) {
                if(!$this->_validateEmail($userData['email'], $userData['checkUserRecno'] ?? '')) {
                    $errors [] = $this->getRetVal('VALIDATION_EMAIL');
                }
            }

            if(!empty($userData['membercellno'])) {
                if(strlen($userData['membercellno']) < 10) {
                    $errors [] = "Invalid Mobile Number";
                }
            }

            if(!empty($userData['memberviberno'])) {
                if(strlen($userData['memberviberno']) < 10) {
                    $errors [] = "Invalid Viber Number";
                }
            }
            
            if(isset($userData['memberfname'])) {
                if(!$this->_validateCharLength($userData['memberfname'], 'First Name')){
                    $errors [] = "First Name is required";
                }
            }

            if(isset($userData['memberlname'])) {
                if(!$this->_validateCharLength($userData['memberlname'], 'Last Name')){
                    $errors [] = "Last Name is required";
                }
            }

            if(isset($userData['memberinfo']) && $userData['memberinfo'] == self::TYPE_BROKER) {
                if(empty($userData['memberlicense'])) {
                    $errors [] = "Member Type : ". self::TYPE_BROKER . ' must have a license number' ;
                    return false;
                }
            }
            return $errors;
        }

        private function _createPassword($password) {
            return password_hash($password, PASSWORD_DEFAULT);
        }
    }