<?php
    _forAuthPageOnly();
   /**
    * required for action 
    * actionToken, recno = id
    *
    */
   $req = request()->inputs();
   $post = request()->posts();

   $adService = new AdService();

   if(isSubmitted()) {
    /**
     * all post requests
     */
   } else {
    //all get requests
    $action = $req['action'];
    $recno  = unseal($req['recno']);
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
            return moduleActionRedirectCheck($req['returnTo'] ?? '');
        break;

        case 'toggle_star':
            //check for user
            $accountService = new AccountService();

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
                /**
                 * check if star has an add
                 * only then you update
                 */
                if($ads['star_id']) {
                    $accountService->update([
                        'stars' => $user['stars'] += 1
                    ], [
                        'recno' => $user['recno']
                    ]);
    
                    $adService->update([
                        'star_id' => 0
                    ], [
                        'recno' => $recno
                    ]);

                    Flash::set("Stars removed from an ad");
                }
            } else {
                /**
                 * check if no star
                 */
                if(!$ads['star_id']) {
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

                        Flash::set("Stars successfully placed to an ad");
                    } else {
                        Flash::set("User has no available stars", 'warning');
                    }
                }
            }

            $accountService->startAuth(whoIs('recno'));
            return moduleActionRedirectCheck($req['returnTo'] ?? '');
        break;
    }
   }
?>