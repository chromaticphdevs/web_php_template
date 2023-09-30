<?php
    $req = request()->inputs();
    $post = request()->posts();
    
    $intentService = new IntentService();

    //post request
    if(isSubmitted()) {

    } else {
        //get request
        switch($req['action']) {
            case IntentService::REQUEST_EMAIL_CHANGE:
                $intentId = unseal($req['intentID']);
                $intent = $intentService->single([
                    'where' => [
                        'id' => $intentId
                    ]
                ]);

                if(isEqual($intent['category'], IntentService::REQUEST_EMAIL_CHANGE)) {
                    if(!isEqual($intent['intent_status'], 'pending')) {
                        Flash::set("Invalid Intent Status Unable to approve email change request", 'danger', 'account_email_message');
                    } else {
                        $intentValue = json_decode($intent['intent_value'], true);
                        $accountService = new AccountService();
                        $isOkay = $accountService->updateEmail($intentValue['email'], $intentValue['user_id']);

                        Flash::set("Email has been updated to {$intentValue['email']}", 'success', 'account_email_message');

                        $intentService->update([
                            'intent_status' => 'completed'
                        ], [
                            'id' => $intentId
                        ]);

                        return redirect(_route('landing_login'));
                    }
                }
            break;
            //reset password
            case IntentService::REQUEST_PASSWORD_CHANGE:
                $intentId = unseal($req['intentID']);
                
                $intent = $intentService->single([
                    'where' => [
                        'id' => $intentId
                    ]
                ]);


                if(isEqual($intent['category'], IntentService::REQUEST_PASSWORD_CHANGE)) {
                    $accountService = new AccountService();
                    
                    if(!isEqual($intent['intent_status'], 'pending')){
                        //error
                        Flash::set("Request has been expired", 'danger');
                        return redirect('landing_login');
                    }
                    //change password
                    $intentValue = json_decode($intent['intent_value'],true);

                    $isOkay = $accountService->update([
                        'password' => $accountService->_createPassword($intentValue['newPassword'])
                    ], [
                        'recno' => $intentValue['user_id']
                    ]);

                    if($isOkay) {
                        $intentService->update([
                            'intent_status' => 'completed'
                        ], [
                            'id' => $intentId
                        ]);
                        
                        Flash::set("Reset password success");
                        return redirect('landing_login');
                    }
                } else {
                    Flash::set("Invalid Request", 'danger');
                    return redirect('landing_login');
                }
            break;
        }
    }
?>