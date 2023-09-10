<?php
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
    }
   }
?>