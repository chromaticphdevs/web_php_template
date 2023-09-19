<?php
   _forAuthPageOnly();
   $req = request()->inputs();

   $addId = unseal($req['recno']);

   $adService = new AdService();
   $listingService = new ListingService();
   
   $addInfo = $adService->single([
        'where' => [
            'ads.recno' => $addId
        ]
    ]);

    $listing = $listingService->single([
        'where' => [
            'listingkeys' => $addInfo['listingcode']
        ]
       ]);


    if($addInfo['star_id']) {
        Flash::set("Unable to delete ads with stars, please remove star from ads then delete again.", 'danger');
        return redirect(_route('prop_show', [
            'recno' => seal($listing['recno'])
        ]));
    }

   $adService->delete([
    'recno' => $addId
   ]);
   

   Flash::set("Ad has been removed");

   return redirect(_route('prop_show', [
    'recno' => seal($listing['recno'])
   ]));
?>