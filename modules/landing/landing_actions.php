<?php
    $req = request()->inputs();
    $post = request()->posts();

    $accountService = new AccountService();
    $intentService = new IntentService();
    //posts requests
    if(isSubmitted()) {

    } else {
        //get requests
        $action = $req['action'];
        $userRecno = unseal($req['userRecno']);
        
        switch($action){
            case AccountService::ACTION_VERIFY_ACCOUNT_VIA_EMAIL:
                $user = $accountService->single([
                    'where' => [
                        'recno' => $userRecno
                    ]
                ]);

                if(isEqual($user['memberstatus'], AccountService::DEFAULT_MEMBER_STATUS)) {
                    $isOkay = $accountService->update([
                        'memberstatus' => AccountService::MEMBER_STATUS_VERIFIED
                    ],[
                        'recno' => $userRecno
                    ]);

                    if($isOkay) {
                        Flash::set("Account Verified");
                        $accountService->startAuth($userRecno);
                        return redirect(_route('user_dashboard'));
                    }
                }
            break;
        }
    }
?>