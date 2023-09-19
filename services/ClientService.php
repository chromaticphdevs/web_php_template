<?php

use function PHPSTORM_META\map;

    class ClientService extends _Service {
        public $_tableName = 'b_clients';
        public $_primaryKey = 'recno';
        public $_columnFillables = [
            'clientcode',
            'clientlname',
            'clientfname',
            'clientemail',
            'clientcellno',
            'clientremarks',
            'clientmsg',
            'agentcode',
            'fk_ads_key',
            'agent_notes',
            'showhide',
            'clientstatus'
        ];

        public function getClients($params = []) {
            $where = null;
            $order = null;

            if(!empty($params['where'])) {
                $where = " WHERE ". parent::conditionConvert($params['where']);
            }

            if(!empty($params['order'])) {
                $order = " ORDER BY {$params['order']}";
            } else {
                $order = " ORDER BY client.recno desc";
            }

            $this->databaseInstance->query(
                "SELECT * FROM {$this->_tableName} as client
                    {$where}
                    GROUP BY clientemail
                    {$order}"
            );

            return $this->databaseInstance->resultSet();
        }
        public function getAll($params = [])
        {
            $where = null;
            $order = null;
            $limit = null;

            if(!empty($params['where'])) {
                $where = " WHERE " . parent::conditionConvert($params['where']);
            }

            if(!empty($params['order'])) {
                $order = " ORDER BY {$params['order']}";
            }

            if(!empty($params['limit'])) {
                $limit = " LIMIT {$params['limit']}";
            }

            $this->databaseInstance->query(
                "SELECT client.*, 
                    ad.adstitle, ad.listtypecode, ad.price,
                    ad.paymentterm,
                    listing.listingcode, listing.listingdescription,
                    listing.propaddress

                    FROM {$this->_tableName} as client
                        LEFT JOIN b_ads as ad
                            ON ad.recno = client.fk_ads_key
                        LEFT JOIN b_listing as listing
                            ON listing.listingkeys = ad.listingcode
                    {$where} {$order} {$limit}"
            );

            return $this->databaseInstance->resultSet();
        }
        /**
         * for new entry
         */
        public function saveNewInquiry($data) {
            $_columnFillables = parent::_getFillablesOnly($data);
            $_columnFillables['clientcode'] = $data['clientlname'].'_'.time();
            $_columnFillables['dateinserted'] = now();
            $_columnFillables['clientstatus'] = 'Lead';
            
            return parent::store($_columnFillables);
            //create client code
        }
    }