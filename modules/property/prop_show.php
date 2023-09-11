<?php
    use Form\ListingForm;
    load(['ListingForm'], FORMS);

    $req = request()->inputs();
    $listingForm = new ListingForm();
    _forAuthPageOnly();
    if(!isset($req['recno'])) {
        _error();
    }

    $recno = unseal($req['recno']);
    
    $adService = new AdService();
    $listingService = new ListingService();

    $listing = $listingService->single([
        'where' => [
            'listing.recno' => $recno
        ]
    ]);

    if(!$listing) {
        _error("Listing not found, invalid request");
    }

    $ads = $adService->getAll([
        'where' => [
            'ads.listingcode' => $listing['listingkeys']
        ]
    ]);
    
    
    $listingForm->setValueObject($listing);
    $imageFolder = $listing['module_folder_name'];
    $listingImages = $listingService->getImages($imageFolder);
?>
<?php build('content') ?>
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Listing Preview</h4>
    </div>
	<div class="card-body">
        <?php 
            echo wLinkDefault(_route('prop_edit', [
                'recno' => seal($recno)
            ]), 'Edit Listing', [
                'class' => 'btn btn-primary btn-sm'
            ]);

            echo wLinkDefault(_route('prop_create'), 'Create New Listing', [
                'class' => 'btn btn-primary btn-sm'
            ]);

            echo wLinkDefault(_route('prop_delete', [
                'recno' => seal($recno)
            ]), 'Delete Listing', [
                'class' => 'btn btn-warning btn-sm'
            ]);

            Flash::show();
        ?>

        <?php echo wDivider('20')?>
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
        
        <section class="box mt-2">
            <h4>Images</h4>
            <div class="row">
            <?php foreach($listingImages as $key => $row) :?>
                <div class="col-md-4">
                    <img src="<?php echo URL_PUBLIC.'/uploads/images/'.$imageFolder.'/'.$row?>" 
                    alt="Property Image" style="width:100%">
                    <div class="text-center"><?php echo wLinkDefault(_route('resource_delete', null, [
                        'path' => seal("public/uploads/images/{$imageFolder}/{$row}")
                    ]), 'Remove Image')?></div>
                </div>
            <?php endforeach?>
            </div>
        </section>

        <section class="mt-4" style="border: 1px solid #eee; border-radius:5px; padding:15px">
            <h4>Ads</h4>
            <?php if(empty($ads)) :?>
                <p class="text-center">There are no ads for this listing. <?php echo wLinkDefault(_route('ads_create', [
                    'listingcode' => $listing['listingkeys']
                ]), 'Create now')?></p>
            <?php else:?>
                <p>There are (<?php echo count($ads)?>) total of ads for this listing : <?php echo wLinkDefault(_route('ads_create', [
                    'listingcode' => $listing['listingkeys']
                ]), 'Add More Ads')?></p>
                <div class="row">
                <?php foreach($ads as $key => $row):?>
                    <?php
                        if($listingImages) {
                          $imageA = "public/uploads/images/{$row['module_folder_name']}/{$listingImages[0]}";	
                        } else {
                          $imageA = DEFAULT_PROPERTY_IMAGE;
                        }
                    ?>
                    <div class='card max300W'>
                        <div>
                        <img src='<?php echo $imageA?>' class='card-img-top rounded imgbox' alt='' style='filter:brightness(50%);'>
                        <div class='position-absolute bottom-0 start-50 translate-middle-x text-white text-center w-100'>
                            <div class='p-4' style='border:""'>
                                    <?php html_ads_star_link($row['star_id'], $row['status'], _route('ads_actions', [
                                        'action' => 'toggle_star',
                                        'recno' => seal($row['recno']),
                                        'returnTo' => seal(_route('prop_show', [
                                            'recno' => seal($recno)
                                        ]))
                                    ]));?>
                                    <p class='mb-2 text-truncate'><?php echo $row['listingcode']?><br>
                                    <small class='text-truncate'><?php echo $row['listtypecode']?></small>
                                    </p>
                                    <div class='mt-0'>
                                        <?php 
                                            echo wLinkDefault(_route('prop_detail', [
                                                'adId' => seal($row['recno'])
                                            ]), '', ['icon' => 'fa fa-eye', 'class' => 'btn btn-sm btn-outline-light rounded-circle']);

                                            echo wLinkDefault(_route('ads_edit', [
                                                'recno' => seal($row['recno'])
                                            ]), '', ['icon' => 'fa fa-edit', 'class' => 'btn btn-sm btn-outline-light rounded-circle']);

                                            echo wLinkDefault(_route('ads_delete', [
                                                'recno' => seal($row['recno'])
                                            ]), '', ['icon' => 'fa fa-trash', 'class' => 'btn btn-sm btn-outline-light rounded-circle']);
                                        ?>
                                    </div>
                            </div>
                        </div>
                        </div>
                    </div>
                <?php endforeach?>
                </div>
            <?php endif?>
        </section>
	</div>
</div>
<?php endbuild()?>
<?php loadTo()?>