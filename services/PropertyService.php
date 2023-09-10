<?php 
	class PropertyService extends _Service {
		public $_tableName = 'properties';

		public $_columnFillables = [
			'prop_reference',
			'prop_name',
			'prop_keyword',
			'prop_type'
		];

		public function store($propData) {
			$fillablesOnly = parent::_getFillablesOnly($propData);

			// $this->checkIfDuplicate();
			//checks
			$fillablesOnly['prop_reference'] = date('hi');
			return $this->databaseHelper->insert($this->_tableName,$fillablesOnly);
			// $this->databaseInstance->query("INSERT INTO {$this->tableName}(prop_reference,prop_name) VALUES('{$propData['prop_reference']}','{$propData['prop_name']}')");		
		}


		private function checkIfDuplicate($data) {
			//code here
		}

	}
?>