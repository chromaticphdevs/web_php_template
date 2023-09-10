<?php 
    namespace Form;
    use Core\Form;

    class InquiryForm extends Form {
        
        public function __construct()
        {
            parent::__construct();

            $this->addFirstname();
            $this->addLastname();
            $this->addEmail();
            $this->addContactNumber();
            $this->addRequest();
            $this->addMessage();
        }

        public function addFirstname() {
            $this->add([
                'name' => 'clientfname',
                'type' => 'text',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'First name'
                ],
                'attributes' => [
                    'placeholder' => 'First Name'
                ]
            ]);
        }
        
        public function addLastname() {
            $this->add([
                'name' => 'clientlname',
                'type' => 'text',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Last name'
                ],
                'attributes' => [
                    'placeholder' => 'Last Name'
                ]
            ]);
        }

        public function addEmail() {
            $this->add([
                'name' => 'clientemail',
                'type' => 'text',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Email'
                ],
                'attributes' => [
                    'placeholder' => 'Email'
                ]
            ]);
        }

        public function addContactNumber() {
            $this->add([
                'name' => 'clientcellno',
                'type' => 'text',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Contact Number'
                ],
                'attributes' => [
                    'placeholder' => 'Contact Number'
                ]
            ]);
        }

        public function addRequest() {
            $this->add([
                'name' => 'clientremarks',
                'type' => 'select',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Request',
                    'option_values' => [
                        'For viewing.',
                        'Please call back.'
                    ]
                ],
                'attributes' => [
                    'placeholder' => 'Request',
                ]
            ]);
        }

        public function addMessage() {
            $this->add([
                'name' => 'clientmsg',
                'type' => 'textarea',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Your Message'
                ],
                'attributes' => [
                    'placeholder' => 'Your Message',
                    'row' => '3'
                ]
            ]);
        }

        public function addFKAdsKey($value) {
            $this->add([
                'name' => 'fk_ads_key',
                'value' => $value,
                'type' => 'hidden'
            ]);
        }

        public function addAgentCode($value) {
            $this->add([
                'name' => 'agentcode',
                'value' => $value,
                'type' => 'hidden'
            ]);
        }
    }