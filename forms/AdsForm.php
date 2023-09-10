<?php 
    namespace Form;
    use Core\Form;
    use ListingService;
    use ListingTypeService;

    class AdsForm extends Form {

        private $serviceListingType, $serviceListing;

        public function __construct()
        {
            parent::__construct();

            $this->serviceListingType = new ListingTypeService();
            $this->serviceListing = new ListingService();

            $this->adsCode();
            $this->addListTypeCode();
            $this->addListCode();
            $this->addDescription();
            $this->addTags();
            $this->addPrice();
            $this->addSecurityDeposit();
            $this->addMinimumContract();
            $this->addDownPayment();
            $this->addPaymentTerm();
            $this->addTitle();
        }

        public function addListTypeCode() {
            $listingTypes = $this->serviceListingType->getAll([
                'order' => 'listtypedesc asc'
            ]);

            $listingTypesArray = arr_layout_keypair($listingTypes, ['listtypecode', 'listtypedesc']);
            $this->add([
                'type' => 'select',
                'name' => 'listtypecode',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Listing Type Code',
                    'option_values' => $listingTypesArray
                ]
            ]);
        }

        public function addListCode() {
            $listings = $this->serviceListing->getAll([
                'where' => [
                    'usercode' => whoIs('usercode')
                ],
                'order' => 'listingcode asc'
            ]);
            $listingArray = arr_layout_keypair($listings, ['listingkeys', 'listingcode']);
            $this->add([
                'type' => 'select',
                'name' => 'listingcode',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Listing Code',
                    'option_values' => $listingArray
                ]
            ]);
        }

        public function addDescription() {
            $this->add([
                'type' => 'textarea',
                'name' => 'adsdesc',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Ads Description(optional)'
                ],

                'attributes' => [
                    'rows' => 3,
                    'style' => 'height: 200px'
                ],
                
            ]);
        }

        public function addTags() {
            $this->add([
                'type' => 'text',
                'name' => 'word_tags',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Tags'
                ]
            ]);
        }

        public function addPrice() {
            $this->add([
                'type' => 'text',
                'name' => 'price',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Price'
                ]
            ]);
        }

        public function addSecurityDeposit() {
            $this->add([
                'type' => 'text',
                'name' => 'securitydeposit',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Security Deposit'
                ]
            ]);
        }

        public function addMinimumContract() {
            $this->add([
                'type' => 'text',
                'name' => 'mincontract',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Minimum Contract'
                ]
            ]);
        }

        public function addDownPayment() {
            $this->add([
                'type' => 'text',
                'name' => 'downpayment',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Down Payment (If for sale)'
                ]
            ]);
        }

        public function addPaymentTerm() {
            $this->add([
                'type' => 'textarea',
                'name' => 'paymentterm',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Payment Terms'
                ],
                'attributes' => [
                    'rows' => 5,
                    'style' => 'height: 100px'
                ]
            ]);
        }

        public function addTitle() {
            $this->add([
                'type' => 'text',
                'name' => 'adstitle',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Ads Title'
                ]
            ]);
        }

        public function adsCode() {
            $this->add([
                'type' => 'hidden',
                'name' => 'adscode',
                'value' => whoIs('usercode')
            ]);
        }
    }