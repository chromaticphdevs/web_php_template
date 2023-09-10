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
                "SELECT * FROM {$this->_tableName} as listing
                  {$where} {$order} {$limit}"
            );
            // $this->databaseInstance->query(
            //     "SELECT ads.*,listing_type.*,
            //     listing.recno as listing_recno,listing.*, 
            //     ads.recno as ad_recno
            //         FROM {$this->_tableName} as listing 
            //             LEFT JOIN b_ads as ads 
            //                 ON listing.listingkeys = ads.listingcode
            //             LEFT JOIN a_listingtype as listing_type
            //                 ON ads.listtypecode = listing_type.listtypecode
                            
            //     {$where}{$order}{$limit}"
            // );
            return $this->databaseInstance->resultSet();
        }

        public function getImages($moduleName) {
            $scannedItems = scandir(PATH_PUBLIC.DS."uploads/images/{$moduleName}");
            return filter_files_only($scannedItems);
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