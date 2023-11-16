<?php 
    class PaidAdService extends _Service {
        public $adService;
        public $accountService;

        const ADS_GOLD = 'GOLD_ADS';
        const ADS_SILVER = 'SILVER_ADS';
        const ADS_PRIORITY = 'PRIORITY_ADS';
        
        public function __construct()
        {
            parent::__construct();
            $this->adService = new AdService();
            $this->accountService = new AccountService();
        }
        public function getAds($adsType, $params = []) {
            switch($adsType) {
                case self::ADS_GOLD:
                    return $this->goldMember($params);
                break;

                case self::ADS_SILVER:
                    return $this->silverMember($params);
                break;

                case self::ADS_PRIORITY:
                    return $this->priorityUnits($params);
                break;
            }
        }


        private function goldMember($params = []) {
            $this->databaseInstance->query(
                "SELECT * FROM a_accounts
                    WHERE membertype = 'Gold' 
                    ORDER BY RAND() LIMIT 1"
            );
            $randomGoldMemberUser = $this->databaseInstance->single();

            if($randomGoldMemberUser) {
                $defaultCondition = [
                    'a_account.usercode' => $randomGoldMemberUser['usercode'],
                    'ads.status' => 'on'
                ];
                return $this->adService->getAll([
                    'where' => $defaultCondition,
                    'order' => 'RAND()',
                    'limit' => 10
                ]);
            }
            return false;
        }

        private function silverMember($params = []) {
            $defaultCondition = [
                'a_account.membertype' => AccountService::MEMBER_TYPE_SILVER,
                'ads.status' => 'on'
            ];
            return $this->adService->getAll([
                'where' => $defaultCondition,
                'order' => 'RAND()',
                'limit' => 40
            ]);
        }

        private function priorityUnits($params = []) {
            $defaultCondition = [
                'a_account.priority' => 1,
                'ads.status' => 'on'
            ];
            if(!empty($params['where'])) {
                $defaultCondition = array_merge($defaultCondition, $params['where']);
            }
            return $this->adService->getAll([
                'where' => $defaultCondition,
                'order' => 'RAND()',
                'limit' => 10
            ]);
        }

        
        private function getNearby($params) {
            return $this->adService->getAll([
                'where' => [
                    'a_account.priority' => 1
                ],
                'order' => 'RAND()',
                'limit' => 10
            ]);
        }
    }