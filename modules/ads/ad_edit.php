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

	$result = $adService->updateDetails($adService->_getFillablesOnly($post),[
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
				<div class="row">
					<div class="col-md-6">
						<h2 class="accordion-header" id="flush-headingOne">
							<button class="accordion-button collapsed py-1" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
								<h5><i class="me-3 fa fa-clipboard-list"></i>Edit Ads</h5>
							</button>
						</h2>
					</div>

					<div class="col-md-6">
						<div style="text-align: right;"><?php echo wLinkDefault(_route('prop_show', [
							'recno' => seal($listing['recno'])
							]), 'Back to Property')?></div>
					</div>
				</div>

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
										<?php echo wLinkDefault(_route('ads_edit', [
											'recno' => seal($adId)
										]), 'Remove Changes', [
											'class' => 'mx-1 btn btn-info bg-col1 border-0 text-white'
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
<?php endbuild()?>
<?php loadTo()?>