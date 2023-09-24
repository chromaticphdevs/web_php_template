<?php 

    class IntentService extends _Service {
        public $_tableName = 'intents';
        const REQUEST_CHANGE_EMAIL = 'REQUEST_CHANGE_EMAIL';

        public $_columnFillables = [
            'category',
            'intent_value',
            'intent_status',
            'errors',
            'message',
            'created_at'
        ];

        public function addRecord($intentData) {
            $contentFillables = parent::_getFillablesOnly($intentData);
            return parent::store($contentFillables);
        }
    }