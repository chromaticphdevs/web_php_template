<?php
    $req = request()->inputs();
    $post = request()->posts();
    
    $intentService = new IntentService();

    //post request
    if(isSubmitted()) {

    } else {
        //get request
        switch($req['action']) {
            case 'email_request_approve':
                $intentId = unseal($req['intent_id']);
                $intent = $intentService->single([
                    'where' => [
                        'id' => $intentId
                    ]
                ]);

                if(isEqual($intent['category'], IntentService::REQUEST_CHANGE_EMAIL)) {
                    if(!isEqual($intent['intent_status'], 'pending')) {
                        Flash::set("Invalid Intent Status Unable to approve email change request", 'danger', 'account_email_message');
                    } else {
                        $intentValue = json_decode($intent['intent_value'], true);
                        $accountService = new AccountService();
                        $isOkay = $accountService->updateEmail($intentValue['email'], $intentValue['user_id']);

                        Flash::set("Email has been updated to {$intentValue['email']}", 'success', 'account_email_message');

                        return redirect(_route('user_profile'));
                    }
                }
            break;
        }
    }
?>