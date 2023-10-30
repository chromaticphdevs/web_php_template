<?php
    require 'autoloader.php';
    $req = request()->inputs();
    $post = request()->posts();

    $adService = new AdService();

   if(isSubmitted()) {
    //all get requests
    $action = $post['action'];
    $recno  = unseal($post['recno']);
    switch(strtolower($action)) {
        case 'toggle_ad':
            //on or off ads
            $adInfo = $adService->single([
                'where' => [
                    'ads.recno' => $recno
                ]
            ]);
            $toggleStatus = $adInfo['status'] == 'on' ? 'off' : 'on';
            
            $adService->update([
                'status' => $toggleStatus
            ], [
                'recno' => $adInfo['recno']
            ]);
            return moduleActionRedirectCheck($post['returnTo'] ?? '');
        break;

        case 'toggle_star':
            //check for user
            $accountService = new AccountService();
            $message = '';

            $ads = $adService->single([
                'where' => [
                    'ads.recno' => $recno
                ]
            ]);

            $user = $accountService->single([
                'where' => [
                    'recno' => whoIs('recno')
                ]
            ]);
            /**
             * REMOVE STAR FROM AD
             */
            if($ads['star_id']) {
                $accountService->update([
                    'stars' => $user['stars'] += 1
                ], [
                    'recno' => $user['recno']
                ]);

                $isOkay = $adService->update([
                    'star_id' => 0
                ], [
                    'recno' => $recno
                ]);
                
                $ads = $adService->single([
                    'where' => [
                        'ads.recno' => $recno
                    ]
                ]);

                if($isOkay) {
                    echo json_encode([
                        'message' => 'Stars removed from Ad',
                        'data' => $ads
                    ]);
                } else{
                    echo json_encode([
                        'message' => 'Something went wrong'
                    ]);
                }
            } else {
                /**
                 * check if no star
                 */
                //check if user has star
                if($user['stars'] >= 1) {
                    $accountService->update([
                        'stars' => $user['stars'] -= 1
                    ], [
                        'recno' => $user['recno']
                    ]);
    
                    $adService->update([
                        'star_id' => 1
                    ], [
                        'recno' => $recno
                    ]);

                    $ads = $adService->single([
                        'where' => [
                            'ads.recno' => $recno
                        ]
                    ]);
                    echo json_encode([
                        'message' => 'Stars successfully placed to an ad',
                        'data' => $ads
                    ]);
                } else {
                    echo json_encode([
                        'message' => 'User has no available stars',
                        'response' => 'failed',
                    ]);
                }
            }
            
            $accountService->startAuth(whoIs('recno'));
        break;
    }
   } else {
    
   }
?>