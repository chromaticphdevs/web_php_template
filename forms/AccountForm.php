<?php 
    namespace Form;
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
                'required' => true
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
    }