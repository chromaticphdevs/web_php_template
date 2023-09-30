<?php 

    class IntentService extends _Service {
        public $_tableName = 'intents';
        const REQUEST_PASSWORD_CHANGE = 'REQUEST_PASSWORD_CHANGE';
        const REQUEST_EMAIL_CHANGE = 'REQUEST_EMAIL_CHANGE';

        public $_columnFillables = [
            'category',
            'intent_value',
            'intent_status',
            'errors',
            'message',
            'created_at'
        ];

        public function addRecord($intentData) {
            $intentData['created_at'] = nowMilitary();
            $contentFillables = parent::_getFillablesOnly($intentData);
            return parent::store($contentFillables);
        }
    }