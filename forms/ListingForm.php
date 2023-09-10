<?php 

	namespace Form;
	use Core\Form;
	use ListingTypeService;
	use LocationCityService;
	use PropertyTypeService;
	use PropertyClassService;

	load(['Form'], CORE);

	class ListingForm extends Form {

		private $servicePropertyType,
		$servicePropertyClass,$serviceLocationCity,
		$serviceListingType;

		public function __construct() {
			parent::__construct();
			$this->servicePropertyType = new PropertyTypeService();
			$this->servicePropertyClass = new PropertyClassService();
			$this->serviceLocationCity = new LocationCityService();
			$this->serviceListingType = new ListingTypeService();

			$this->addListingCode();
			$this->addTypeCode();
			$this->addClassCode();
			$this->addCityCode();
			$this->addListingDescription();
			$this->addBuildingName();
			$this->addAddress();
			$this->addListingTag();
			$this->addListingKey();
			$this->addUserCode();
			$this->moduleFolder();
		}

		public function addTypeCode() {
			$propertyTypes = $this->servicePropertyType->getAll();
			$propertTypeArray = arr_layout_keypair($propertyTypes, ['proptypecode','proptypedesc']);
			$this->add([
				'type' => 'select',
				'name' => 'proptypecode',
				'options' => [
					'label' => 'Property Type Code',
					'option_values' => $propertTypeArray
				],
				'class' => 'form-control',
				'required' => true
			]);
		}

		public function addListingCode() {
			$this->add([
				'type' => 'text',
				'name' => 'listingcode',
				'options' => [
					'label' => 'Listing Name'
				],
				'class' => 'form-control',
				'required' => true
			]);
		}

		public function addListingDescription() {
			$this->add([
				'type' => 'textarea',
				'name' => 'listingdescription',
				'options' => [
					'label' => 'Listing Description'
				],
				'class' => 'form-control',
				'attributes' => [
					'rows' => 5
				],
				'required' => true
			]);
		}

		public function addBuildingName() {
			$this->add([
				'type' => 'text',
				'name' => 'buildingname',
				'options' => [
					'label' => 'Building Name'
				],
				'class' => 'form-control',
			]);
		}

		public function addAddress() {
			$this->add([
				'type' => 'text',
				'name' => 'propaddress',
				'options' => [
					'label' => 'Property Address'
				],
				'class' => 'form-control',
				'required' => true
			]);
		}
		
		public function addClassCode() {
			$propertyClasses = $this->servicePropertyClass->getAll();
			$propertyClassArray = arr_layout_keypair($propertyClasses, ['propclasscode','propclassdesc']);
			$this->add([
				'type' => 'select',
				'name' => 'propclasscode',
				'options' => [
					'label' => 'Property Class Code',
					'option_values' => $propertyClassArray
				],
				'class' => 'form-control',
				'required' => true
			]);
		}

		public function addCityCode() {
			$cityLocations = $this->serviceLocationCity->getAll();
			$cityLocationArray = arr_layout_keypair($cityLocations, ['loccitycode','loccitydesc']);
			$this->add([
				'type' => 'select',
				'name' => 'loccitycode',
				'options' => [
					'label' => 'Property Location Code',
					'option_values' => $cityLocationArray
				],
				'class' => 'form-control',
				'required' => true
			]);
		}

		public function addListingTag() {
			$listings = $this->serviceListingType->getAll();
			$listingTagArray = arr_layout_keypair($listings,['listtypecode','listtypedesc']);
			
			$this->add([
				'name' => 'listingtag',
				'type' => 'select',
				'class' => 'form-control',
				'options' => [
					'label' => 'Listing Type',
					'option_values' => $listingTagArray
				]
			]);
		}

		public function addUserCode() {
			$this->add([
				'type' => 'hidden',
				'name' => 'usercode'
			]);
		}
		public function addPecode() {

		}

		public function addListingKey() {
			$this->add([
				'type' => 'hidden',
				'value' => strtotime(nowMilitary()),
				'name' => 'listingkeys'
			]);
		}

		public function addDateInserted() {

		}

		public function addBuilding() {

		}

		public function moduleFolder() {
			$this->add([
				'type' => 'hidden',
				'name' => 'module_folder_name',
			]);
		}
	}