<?php 

    class AdService extends _Service {
        public $_tableName = 'b_ads';

        public $_columnFillables = [
            'adscode','xy',
            'listtypecode',
            'start_id','adstitle',
            'adsdesc', 'listingcode',
            'price', 'securitydeposit',
            'mincontract', 'downpayment',
            'paymentterm', 'status',
            'word_tags', 'dateinserted'
        ];

        public $_primaryKey = 'recno';


        public function store($adData) {
            $toInsertData = parent::_getFillablesOnly($adData);

            if(!empty($toInsertData['securitydeposit'])) {
                $this->_validateNumberOnly($toInsertData['securitydeposit'], 'Security Deposit');
            }

            // if(!empty($toInsertData['downpayment'])) {
            //     $this->_validateNumberOnly($toInsertData['downpayment'], 'DownPayment');
            // }

            if(!empty($toInsertData['price'])) {
                $this->_validateNumberOnly($toInsertData['price'], 'Price');
            }

            if(!empty($this->getErrors())) {
                return false;
            }

            if($toInsertData['price'] < 1) {
                $this->addError("Invalid Price");
                return false;
            }
            
            $id =  parent::store($toInsertData);

            if($id) {
                parent::addRetVal('recno', $id);
                parent::addMessage("Ads saved");
            } else{
                parent::addError("Unable to save ads");
            }

            return $id;
        }

        public function updateDetails($adData, $condition) {
            $toUpdateData = parent::_getFillablesOnly($adData);

            if(!empty($toUpdateData['securitydeposit'])) {
                $this->_validateNumberOnly($toUpdateData['securitydeposit'], 'Security Deposit');
            }

            if(!empty($toUpdateData['downpayment'])) {
                $this->_validateNumberOnly($toUpdateData['downpayment'], 'DownPayment');
            }

            if(!empty($toUpdateData['price'])) {
                $this->_validateNumberOnly($toUpdateData['price'], 'Price');
            }

            if(!empty($this->getErrors())) {
                return false;
            }
            return parent::update($toUpdateData, $condition);
        }

        private function _validateNumberOnly($string, $key) {
            if(!is_numeric($string)) {
                $this->addError("Invalid entry on {$key}");
                return false;
            }

            return true;
        }
        public function getAll($params = [])
        {
            $where  = null;
            $order = null;
            $limit = null;

            if(!empty($params['where'])) {
                $where = " WHERE " .parent::conditionConvert($params['where']);
            }

            if(!empty($params['order'])) {
                $order = " ORDER BY ".$params['order'];
            }

            if(!empty($params['limit'])) {
                $limit = " LIMIT {$params['limit']}";
            }

            $this->databaseInstance->query(
                "SELECT ads.*,listing_type.*, listing.recno as listing_recno,listing.*,
                    ads.recno as recno, ads.listingcode as listingcode,
                    a_locationcity.loccitytag, a_propertyclass.propclasstag,
                    a_membertype.membertypecode as memtype_code

                    FROM {$this->_tableName} as ads

                    LEFT JOIN b_listing as listing 
                        ON listing.listingkeys = ads.listingcode
                    LEFT JOIN a_listingtype as listing_type
                        ON ads.listtypecode = listing_type.listtypecode

                    LEFT JOIN a_locationcity on 
                        a_locationcity.loccitycode = listing.loccitycode

                    LEFT JOIN a_propertyclass ON
                        a_propertyclass.propclasscode = listing.propclasscode

                    LEFT JOIN a_accounts as a_account
                        ON a_account.usercode = listing.usercode

                    LEFT JOIN a_membertype as a_membertype
                        ON a_membertype.recno = a_account.membertype
                     
                    {$where} {$order} {$limit}"
            );
            return $this->databaseInstance->resultSet();
        }

        public function getAllCount($params = []) {
            $where  = null;
            $order = null;
            $limit = null;

            if(!empty($params['where'])) {
                $where = " WHERE " .parent::conditionConvert($params['where']);
            }

            if(!empty($params['order'])) {
                $order = " ORDER BY ".$params['order'];
            }

            if(!empty($params['limit'])) {
                $limit = " LIMIT {$params['limit']}";
            }

            $this->databaseInstance->query(
                "SELECT count(*) as total_result

                    FROM {$this->_tableName} as ads

                    LEFT JOIN b_listing as listing 
                        ON listing.listingkeys = ads.listingcode
                    LEFT JOIN a_listingtype as listing_type
                        ON ads.listtypecode = listing_type.listtypecode

                    LEFT JOIN a_locationcity on 
                        a_locationcity.loccitycode = listing.loccitycode

                    LEFT JOIN a_propertyclass ON
                        a_propertyclass.propclasscode = listing.propclasscode

                    LEFT JOIN a_accounts as a_account
                        ON a_account.usercode = listing.usercode

                    LEFT JOIN a_membertype as a_membertype
                        ON a_membertype.recno = a_account.membertype
                     
                    {$where} {$order} {$limit}"
            );
            return $this->databaseInstance->single()['total_result'] ?? 0;
        }
    }