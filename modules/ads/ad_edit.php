<?php

use Form\AdsForm;
use Form\ListingForm;
load(['ListingForm','AdsForm'], FORMS);

build('content') ?>

<?php
_forAuthPageOnly();
$listingForm = new ListingForm();
$listingService = new ListingService();
$adService = new AdService();
$formAd = new AdsForm();

$req = request()->inputs();


if(isSubmitted()) {
	$post = request()->posts();
	$result = $adService->update($adService->_getFillablesOnly($post),[
        'recno' => $post['recno']
    ]);
	
	if($result) {

		Flash::set("Ads Updated successfully");
		return redirect(_route('ads_edit', [
			'recno' => seal($post['recno'])
		]));
	} else {
		Flash::set('Unable to add new Ads', 'danger');
	}
	// return redirect(_route('ads_edit?recno='.seal($post['recno'])));
}

$adId = unseal($req['recno']);

$adInfo = $adService->single([
    'where' => [
        'ads.recno' => $adId
    ]
]);
	
$listing = $listingService->single([
	'where' => [
		'listing.listingkeys' => $adInfo['listingcode']
	]
]);

$ads = $adService->getAll([
	'where' => [
		'ads.recno' => [
			'condition' => 'not equal',
			'value' => $adId
		],
		'listing.usercode' => whoIs('usercode')
	]
]);

$formAd->add([
    'type' => 'hidden',
    'value' => $adId,
    'name' => 'recno'
]);

$formAd->setValueObject($adInfo);
?>
<!-- Create Listing -->
<div class="card">
	<div class="card-body">
		<div class="accordion accordion-flush" id="accordionFlush">
			<div class="accordion-item">
				<h2 class="accordion-header" id="flush-headingOne">
					<button class="accordion-button collapsed py-1" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
						<h5><i class="me-3 fa fa-clipboard-list"></i>Edit Ads</h5>
					</button>
				</h2>
				<div id="flush-collapseOne" class="accordion-collapse" 
				aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlush">
					<div class="accordion-body p-0">
						<?php Flash::show()?>
						<?php echo $formAd->start([
							'method' => 'post'
						])?>
							<?php echo $formAd->get('adscode')?>
                            <?php echo $formAd->get('recno')?>
						<div class="row px-4 mt-3">
							<div class="col-sm-6">
								<div class="form-floating mb-3">
									<?php echo $formAd->getCol('listtypecode')?>
								</div>
								<div class="form-floating mb-3">
									<?php echo $formAd->getCol('listingcode')?>
									<?php echo wLinkDefault(_route('prop_show', [
										'recno' => seal($listing['recno'])
									]), 'Show Listing')?>
								</div>
								<div class="form-floating mb-3">
									<?php echo $formAd->getCol('adstitle')?>
								</div>
								<div class="form-floating mb-3">
									<?php echo $formAd->getCol('adsdesc')?>
								</div>
								<div class="form-floating mb-3">
									<?php echo $formAd->getCol('word_tags')?>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-floating mb-3">
									<?php echo $formAd->getCol('price')?>
								</div>
								<div class="form-floating mb-3">
									<?php echo $formAd->getCol('securitydeposit')?>
								</div>
								<div class="form-floating mb-3">
									<?php echo $formAd->getCol('mincontract')?>
								</div>
								<div class="form-floating mb-3">
									<?php echo $formAd->getCol('downpayment')?>
								</div>
								<div class="form-floating mb-3">
									<?php echo $formAd->getCol('paymentterm')?>
								</div>

								<div class="row">
									<div class="col">
										<input type="submit" name="btn_create_prop"
											class="mx-1 btn btn-info bg-col1 border-0 text-white"
											value="Save Changes">
									</div>

									<div class="col">
										<?php echo wLinkDefault(_route('ads_delete', [
											'recno' => seal($adInfo['recno'])
										]), 'Delete Ads', [
											'class' => 'btn btn-danger'
										])?>
									</div>
								</div>

								
							</div>
						</div>
						<?php echo $listingForm->end()?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--- laoad ads -->
<div class="card">
	<div class="card-body">
		<div class='d-flex justify-content-evenly flex-wrap'>
			<?php foreach($ads as $key => $row):?>
				<?php
					$images = $listingService->getImages($row['module_folder_name']);
					$imageA = "public/uploads/images/{$row['module_folder_name']}/{$images[0]}";	
				?>
				<div class='card max300W'>
					<div>
					<img src='<?php echo $imageA?>' class='card-img-top rounded imgbox' alt='' style='filter:brightness(50%);'>
					<div class='position-absolute bottom-0 start-50 translate-middle-x text-white text-center w-100'>
						<div class='p-4' style='border:""'>
						<?php html_ads_star_link($row['star_id'], $row['status'], _route('ads_actions', [
							'action' => 'toggle_star',
							'recno' => seal($row['recno']),
							'returnTo' => seal(_route('ads_create'))
						]));?>

						<p class='mb-2 text-truncate'><?php echo $row['listingcode']?><br>
						<small class='text-truncate'><?php echo $row['listtypecode']?></small>
						</p>
						<div class='mt-0'>
						   <?php echo wLinkDefault(_route('prop_detail', [
								'adId' => seal($row['recno'])
							]), '', ['icon' => 'fa fa-eye', 'class' => 'btn btn-sm btn-outline-light rounded-circle'])?>

							<?php echo wLinkDefault(_route('ads_edit', [
								'recno' => seal($row['recno'])
							]), '', ['icon' => 'fa fa-edit', 'class' => 'btn btn-sm btn-outline-light rounded-circle'])?>

							<?php echo wLinkDefault(_route('ads_delete', [
								'recno' => seal($row['recno'])
							]), '', ['icon' => 'fa fa-trash', 'class' => 'btn btn-sm btn-outline-light rounded-circle'])?>
						</div>
						</div>
					</div>
					</div>
				</div>
			<?php endforeach?>
		</div>
	</div>
</div>
<?php endbuild()?>
<?php loadTo()?>