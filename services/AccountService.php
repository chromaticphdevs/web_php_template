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
        const DEFAULT_MEMBER_STATUS = 'Unverified';
        const MEMBER_STATUS_VERIFIED = 'Verified';
        const MEMBER_TYPE_SILVER  = 2;
        const MEMBER_TYPE_GOLD  = 3;

        const TYPE_BROKER = 'broker';
        const TYPE_AGENT = 'agent';
        const TYPE_OWNER = 'owner';
        const TYPE_REPRESENTATIVE = "representative";

        const ACTION_VERIFY_ACCOUNT_VIA_EMAIL = 'VERIFY_ACCOUNT_VIA_EMAIL';
        const ACTION_SEND_VERIFY_ACCOUNT_VIA_EMAIL = 'SEND_VERIFY_ACCOUNT_VIA_EMAIL';


        public function getAll($params =[]) {
            $where = null;
            $order = null;
            $limit = null;

            if(!empty($params['where'])) {
                $where = " WHERE ".parent::conditionConvert($params['where']);
            }

            if(!empty($params['order'])) {
                $order = " ORDER BY {$params['order']} ";
            }

            if(!empty($params['limit'])) {
                $limit = " LIMIT BY {$params['limit']} ";
            }

            $this->databaseInstance->query(
                "SELECT a_account.*, a_membertype.membertypecode,
                    a_membertype.membertypedesc
                    FROM {$this->_tableName} as a_account

                    LEFT JOIN a_membertype
                        ON a_account.membertype = a_membertype.recno
                    {$where} {$order} {$limit}"
            );
            return $this->databaseInstance->resultSet();
        }

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
                    if(!isEqual($account['memberstatus'], 'Verified')) {
                        $sendAccountVerificationLink = wLinkDefault(URL.DS._route('landing_actions',[
                            'action' => AccountService::ACTION_SEND_VERIFY_ACCOUNT_VIA_EMAIL,
                            'userRecno' => seal($account['recno'])
                        ]), 'I did not recieve any email, please send confirmation again.');
                        $this->addError("Check your email and verify your account.".$sendAccountVerificationLink);
                        return false;
                    }
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
                    'a_account.recno' => $userRecno
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

        public function updateEmail($email, $userRecno) {
            $isOkay = parent::update([
                'email' => $email
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

        public function updateEmailCheck($userData, $userRecno) {
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

            return true;
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
                        'a_account.email' => $email
                    ]
                ]);
            } else {
                $user = parent::single([
                    'where' => [
                        'email' => $email,
                        'a_account.recno' => [
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
            return strtoupper("{$prefix}".referenceSeries(parent::count() + 1, 5, null));
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
                    'a_account.recno' => $userRecno
                ]
            ]);

            if($user) {
                Session::set('auth', $user);
                return Session::get('auth');
            } else {
                return false;
            }
        }

        public function requestChangeEmail($userId, $newEmail) {
            $user = parent::single([
                'where' => [
                    'a_account.recno' => $userId
                ]
            ]);


            if(isEqual($user['email'], $newEmail)) {
                $this->addError("You are already using this email, change email request failed.");
                return false;
            }
            $validateEmail = $this->_validateEmail($newEmail, $userId);

            if(!$validateEmail) {
                $this->addError($this->getRetVal('VALIDATION_EMAIL'));
                return false;
            }

            $user = parent::single([
                'where' => [
                    'a_account.recno' => $userId
                ]
            ]);

            $intentService = new IntentService();

            $intentID = $intentService->addRecord([
                'category' => IntentService::REQUEST_EMAIL_CHANGE,
                'intent_value' => json_encode([
                    'user_id' => $userId,
                    'email'   => $newEmail,
                ]),
                'intent_status' => 'pending',
                'created_at' => nowMilitary()
            ]);

            if($intentID) {
                $approveChangeEmailRequestHREF = URL.DS._route('intent_actions',[
                    'action' => IntentService::REQUEST_EMAIL_CHANGE,
                    'intentID' => seal($intentID)
                ]);
                //send email
                $subject = "New Email Re-verification from ".COMPANY_NAME;
                $message = "Good day {$user['memberlname']} {$user['memberfname']} ,<br>";
                $message .= "Pls follow the link below to complete your Email re-verification...<br>";
                $message .= "<a href='{$approveChangeEmailRequestHREF}'>Verify My New Email Address!</a>";

                _mail_base($subject, $message,[$newEmail]);
                return true;
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

        public function sendAccountVerificationViaEmail($userRecno) {
            $user = parent::single([
                'where' => [
                    'a_account.recno' => $userRecno
                ]
            ]);
            if(!$user) {
                $this->addError("No user found");
                return false;
            }

            $verifyHref = URL.DS._route('landing_actions', [
                'action' => self::ACTION_VERIFY_ACCOUNT_VIA_EMAIL,
                'userRecno' => seal($userRecno)
            ]);

            $subject = "Email verification from ".COMPANY_NAME;
            $message = "Good day {$user['memberfname']} {$user['memberlname']},<br>";
            $message .= "Pls follow the link below to complete your registration...<br>";
            $message .= "<a href='{$verifyHref}'>Verify My Email Address!</a>";
            _mail_base($subject, $message, [$user['email']]);

            return true;
        }

        public function resetPassword($email) {
            $user = parent::single([
                'where' => [
                    'a_account.email' => $email
                ]
            ]);

            if($user) {
                $newPassword = random_number(4);
                //create intent
                $intent = new IntentService();
                $intentID = $intent->addRecord([
                    'category' => IntentService::REQUEST_PASSWORD_CHANGE,
                    'intent_value' => json_encode([
                        'user_id' => $user['recno'],
                        'newPassword' => $newPassword
                    ]),
                    'intent_status' => 'pending'
                ]);

                $href = URL.DS._route('intent_actions', [
                    'intentID' => seal($intentID),
                    'action'   => IntentService::REQUEST_PASSWORD_CHANGE,
                    'userRecno' => seal($user['recno'])
                ]);

                //send reset password
                $subject = "Reset Password Request";
                $message = "Good day {$user['memberfname']} {$user['memberlname']},<br>";
                $message .= "Pls follow the link below to reset your password...<br>";
                $message .= "This is your temporary password : <b>{$newPassword}</b> please change after logging in<br>";
                $message .= "<a href='{$href}'>Reset My Password!</a>";

                _mail_base($subject, $message, [$user['email']]);
                return true;
            } else {
                return false;
            }
        }

        public function _createPassword($password) {
            return password_hash($password, PASSWORD_DEFAULT);
        }
    }