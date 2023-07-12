<?php
    class PropertyTypeService extends _Service{
        
        public $_tableName = 'a_propertytype';

		public $_columnFillables = [
			'prop_reference',
			'prop_name',
			'prop_keyword',
			'prop_type'
		];

        public function getAll($params = []) {
            $this->databaseInstance->query(
                "SELECT  * FROM {$this->_tableName}"
            );
            return $this->databaseInstance->resultSet();
        }
    }
?>