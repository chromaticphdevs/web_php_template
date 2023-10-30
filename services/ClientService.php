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

            if(!isset($adService)) {
                 $adService = new AdService();
            }
            
            $ads = $adService->single([
                'where' => [
                    'ads.recno' => $data['fk_ads_key']
                ]
            ]);

            return parent::store($_columnFillables);
            //create client code
        }

        private function emailClient($inquiryData, $ads) {
            $href = URL.DS._route('prop_detail', [
                'adId' => seal($ads['recno'])
            ]);

            $subject = "Listing information from Eastwoodcitycondo";
            $message = "Good day {$inquiryData['clientfname']} {$inquiryData['clientlname'] } ,<br>";
            $message .= "You inquired for the following listing...<br>";
            $message .= "Link: <a href='{$href}'>{$ads['adstitle']}</a><br>";
    	    $message .= "Listing Type: {$ads['listtypetag']}<br>";
            $message .= "Price: {$ads['price']}<br>";
            $message .= "Location: {$ads['loccitytag']}<br>";
            $message .= "Location: {$ads['loccitytag']}<br>";

            _mail_base($subject, $message, [$inquiryData['clientemail']]);
        }

        private function emailAgent($inquiryData, $ads) {
		    $href = URL.DS._route('prop_detail', [
                'adId' => seal($ads['recno'])
            ]);

            if(!isset($accountService)) {
                $accountService = new AccountService();
            }
            //to get the agent of ads 
            $account = $accountService->single([
                'where' => [
                    'usercode' => $ads['usercode']
                ]
            ]);

    	    $subject = "You received a new inquiry at ".COMPANY_NAME;
            //email back to agent
            $message = "<span style='font-size:30px'>Dear {$account['memberfname']} {$account['memberlname']},</span><br>";
            $message .= "<br>";
            $message .= "Someone is interested in your property ads at ".COMPANY_NAME."<br>";
            $message .= "Please find the details of the inquiry below:<br>";
            $message .= "<br>";
            $message .= "Listing Code: {$ads['listingcode']}<br>";
    	    $message .= "Ads Title: <a href='{$href}'>{$ads['adstitle']}</a><br>";

            $message .= "Listing Type: {$ads['listtypetag']}<br>";
            $message .= "Price: {$ads['price']}<br>";
            $message .= "<br>";
            $message .= "You may get in touch with your client at the provided contact information<br>";
            $message .= "Name: {$inquiryData['clientfname']} {$inquiryData['clientlname']}<br>";
            $message .= "Email: {$inquiryData['clientemail']}<br>";
            $message .= "Contact: {$inquiryData['clientcellno']}<br>";
            $message .= "Remarks: {$inquiryData['clientremarks']}<br>";
            $message .= "Msg: {$inquiryData['clientmsg']}<br>";

            _mail_base($subject, $message, [$account['email']]);
        }
    }
