<?php 

    class ListingService extends _Service {
        public $_tableName = 'b_listing';
        public $_primaryKey = 'recno';
        public $_columnFillables = [
            'listingcode',
            'listingdescription',
            'buildingname',
            'propaddress',
            'proptypecode',
            'propclasscode',
            'loccitycode',
            'usercode',
            'listingkeys',
            'listingtag',
            'dateinserted',
            'module_folder_name'
        ];

        public function store($propData) {
            $toInsertData = parent::_getFillablesOnly($propData);
            $insertId = parent::store($toInsertData);
            /**
             * on success
             */
            if($insertId) {
                /**
                 * create a message
                 */
                $this->addMessage("{$propData['listingcode']} created");
                return $insertId;
            }
            $this->addError("Unable to create listing");
            return false;
        }

        public function update($data, $recno) {
            $toInsertData = parent::_getFillablesOnly($data);
            $isOkay = parent::update($toInsertData, $recno);

            if($isOkay) {
                $this->addMessage("Property Updated Successfully");
            } else {
                $this->addError("Unable to update property");
            }
            
            return $isOkay;
        }

        public function getAll($params = []) {
            $where = null;
            $order = null;
            $limit = null;

            if(!empty($params['where'])) {
                $where = " WHERE " .parent::conditionConvert($params['where']);
            }

            $this->databaseInstance->query(
                "SELECT listing.*, proptype.proptypedesc,
                    proptype.proptypetag,
                    propclass.propclassdesc,
                    propclass.propclasstag,
                    loccity.loccitydesc,
                    loccity.loccitycode
                    
                    FROM {$this->_tableName} as listing
                    LEFT JOIN a_propertyclass as propclass
                        ON propclass.propclasscode = listing.propclasscode
                    LEFT JOIN a_propertytype as proptype
                        ON proptype.proptypecode = listing.proptypecode
                    LEFT JOIN a_locationcity as loccity
                        ON loccity.loccitycode = listing.loccitycode
                  {$where} {$order} {$limit}"
            );
            return $this->databaseInstance->resultSet();
        }

        public function getImages($moduleName) {
            if(file_exists(PATH_PUBLIC.DS."uploads/images/{$moduleName}")) {
                $scannedItems = scandir(PATH_PUBLIC.DS."uploads/images/{$moduleName}");
                return filter_files_only($scannedItems);
            }
        }

        public function imageRequiredCheck($moduleName) {
            $images = $this->getImages($moduleName);
            if(empty($images)) {
                $this->addError("No images found on this listing");
                return false;
            }

            return true;
        }
    }