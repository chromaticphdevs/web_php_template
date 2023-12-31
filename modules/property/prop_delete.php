<?php

    use Form\AdsForm;
    use Form\ListingForm;
    load(['ListingForm','AdsForm'], FORMS);

    _forAuthPageOnly();
    $req = request()->inputs();
    $recno = unseal($req['recno']);
    $listingService = new ListingService();
    $adService = new AdService();

    $listing = $listingService->single([
        'where' => [
            'listing.recno' => $recno
        ]
    ]);

    $ads = $adService->getAll([
        'where' => [
            'listing.recno' => $recno
        ]
    ]);

    if(!empty($ads)){
        Flash::set("Properties with ads are not allowed to be removed, delete the ads first then remove this property", 'warning');
        return request()->return();
    }

    $listingForm = new ListingForm();
    $adsForm = new AdsForm();
    $listingForm->setValueObject($listing);
    
    if(!empty($req['delete_approve']) && !empty($req['action_token'])) {
        if(!isEqual($req['action_token'], csrfGet())) {
            Flash::set('You Clicking Multiple times, Please redo your action');
        }

        //delete ads
        $adDelete = $adService->delete([
            'listingcode' => $listing['listingkeys']
        ]);

        if(!empty($listing['module_folder_name'])) {
            delete_directory(PATH_PUBLIC.DS.'uploads/images/'.$listing['module_folder_name']);
        }

        $listingDelete = $listingService->delete([
            'recno' => $recno
        ]);

        Flash::set("Property {$listing['listingcode']} has been removed", 'PROP_DELETE_MESS');
        return redirect(_route('prop_create'));
    }

    $imageFolder = $listing['module_folder_name'];
    $listingImages = $listingService->getImages($imageFolder);

    csrfReload();
    $csrfToken = csrfGet();
?>

<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Are you sure you want to delete this Listing?</h4>
        </div>

        <div class="card-body">
            <?php
                echo wLinkDefault(_route('prop_delete', [
                    'action_token' => $csrfToken,
                    'delete_approve' => 'yes',
                    'recno' => seal($recno)
                ]), 'Yes Delete This Property', [
                    'class' => 'btn btn-danger form-verify-action'
                ]);

                echo wLinkDefault(_route('prop_show', [
                    'recno' => seal($recno)
                ]), 'Cancel Action', [
                    'class' => 'btn btn-primary'
                ]);
            ?>

            <?php echo wDivider(20)?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td style="width: 30%;"><?php echo $listingForm->getLabel('proptypecode')?></td>
                        <td><?php echo $listing['proptypecode']?></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;"><?php echo $listingForm->getLabel('propclasscode')?></td>
                        <td><?php echo $listing['propclasscode']?></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;"><?php echo $listingForm->getLabel('listingcode')?></td>
                        <td><?php echo $listing['listingcode']?></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;"><?php echo $listingForm->getLabel('listingdescription')?></td>
                        <td><?php echo $listing['listingdescription']?></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;"><?php echo $listingForm->getLabel('loccitycode')?></td>
                        <td><?php echo $listing['loccitycode']?></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;"><?php echo $listingForm->getLabel('propaddress')?></td>
                        <td><?php echo $listing['propaddress']?></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;"><?php echo $listingForm->getLabel('propaddress')?></td>
                        <td><?php echo $listing['propaddress']?></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;"><?php echo $listingForm->getLabel('buildingname')?></td>
                        <td><?php echo $listing['buildingname']?></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php echo wDivider(20)?>
        <div class="card-body">
            <h4 class="card-title">Images</h4>
            <div class="row">
                <?php foreach($listingImages as $key => $row) :?>
                    <div class="col-md-4">
                        <img src="<?php echo URL_PUBLIC.'/uploads/images/'.$imageFolder.'/'.$row?>" 
                        alt="Property Image" style="width:100%">
                    </div>
                <?php endforeach?>
            </div>
        </div>

        <?php echo wDivider()?>

        <div class="card-body">
            <h4 class="card-title">Ads for this listings</h4>
            <?php if(empty($ads)) :?>
                <p>No ads for this Listing</p>
            <?php else:?>
                <p>This ads will be deleted as well.</p>
                <div class="row">
                <?php foreach($ads as $key => $ad) :?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <ul>
                                    <li><?php echo $adsForm->getLabel('adstitle')?> : <?php echo $ad['adstitle']?></li>
                                    <li><?php echo $adsForm->getLabel('price')?> : <?php echo $ad['price']?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach?>
                </div>
            <?php endif?>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>
