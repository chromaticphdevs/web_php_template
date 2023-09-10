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
            $id =  parent::store($toInsertData);

            if($id) {
                parent::addRetVal('recno', $id);
                parent::addMessage("Ads saved");
            } else{
                parent::addError("Unable to save ads");
            }

            return $id;
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
                    ads.recno as recno, ads.listingcode as listingcode
                    FROM {$this->_tableName} as ads
                    LEFT JOIN b_listing as listing 
                        ON listing.listingkeys = ads.listingcode
                    LEFT JOIN a_listingtype as listing_type
                        ON ads.listtypecode = listing_type.listtypecode

                    {$where} {$order} {$limit}"
            );

            return $this->databaseInstance->resultSet();
        }
    }