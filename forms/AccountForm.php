<?php 
    namespace Form;

use AccountService;
use Core\Form;
    load(['Form'],CORE);

    class AccountForm extends Form {

        public function __construct()
        {
            parent::__construct();
            $this->addEmail();
            $this->addPassword();
            $this->addFirstName();
            $this->addLastName();
            $this->addMobileNumber();
            $this->addViberNumber();
            $this->addMemberLicense();
            $this->addMemberInfo();
            $this->addMemberAbout();
        }

        public function addCode() {
            $this->add([
                'name' => 'usercode',
                'type' => 'test',
                'class' => 'form-control',
                'options' => [
                    'label' => 'User Code'
                ],

                'required' => true
            ]);
        }

        public function addEmail() {
            $this->add([
                'name' => 'email',
                'type' => 'email',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Email Address'
                ],
                'required' => true
            ]);
        }


        public function addPassword() {
            $this->add([
                'name' => 'password',
                'type' => 'password',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Password'
                ],
                'required' => true,
                'attributes' => [
                    'id' => 'password'
                ]
            ]);
        }
        
        public function addFirstName() {
            $this->add([
                'name' => 'memberfname',
                'type' => 'text',
                'class' => 'form-control',
                'options' => [
                    'label' => 'First Name'
                ],
                'required' => true
            ]);
        }

        public function addLastName() {
            $this->add([
                'name' => 'memberlname',
                'type' => 'text',
                'class' => 'form-control',
                'options' => [
                    'label' => 'First Name'
                ],
                'required' => true
            ]);
        }

        public function addMobileNumber() {
            $this->add([
                'name' => 'membercellno',
                'type' => 'text',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Mobile Number'
                ],
                'required' => true
            ]);
        }

        public function addViberNumber() {
            $this->add([
                'name' => 'memberviberno',
                'type' => 'text',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Viber Number'
                ]
            ]);
        }

        public function addMemberLicense() {
            $this->add([
                'name' => 'memberlicense',
                'type' => 'text',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Your Real Estate Broker License Number'
                ],
                'attributes' => [
                    'id' => 'memberlicense'
                ]
            ]);
        }

        public function addMemberInfo() {
            $this->add([
                'name' => 'memberinfo',
                'type' => 'select',
                'class' => 'form-control',
                'options' => [
                    'label' => "Member's Info",
                    'option_values' => AccountService::memberTypeInfos()
                ],
                'required' => true,
                'attributes' => [
                    'id' => 'memberinfo'
                ]
            ]);
        }


        public function addMemberAbout() {
            $this->add([
                'name' => 'memberabout',
                'type' => 'textarea',
                'class' => 'form-control',
                'options' => [
                    'label' => 'About me'
                ],
                'attributes' => [
                    'style' => 'min-height:150px'
                ]
            ]);
        }
    }

    