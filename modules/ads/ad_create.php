<?php

use Form\AdsForm;
use Form\ListingForm;
load(['ListingForm','AdsForm'], FORMS);

build('content') ?>

<?php
_forAuthPageOnly();

$listingForm = new ListingForm();
$formAd = new AdsForm();

$listingService = new ListingService();
$adService = new AdService();

$req = request()->inputs();

if(isSubmitted()) {
	$post = request()->posts();
	$result = $adService->store($post);

	if($result) {
		$listing = $listingService->single([
			'where' => [
				'listing.listingkeys' => $post['listingcode'],
				'listing.usercode' => whoIs('usercode')
			]
		]);

		Flash::set(arr_to_str($adService->getMessages()));
		return redirect(_route('prop_show', [
			'recno' => seal($listing['recno'])
		]));
	} else {
		Flash::set('Unable to add new Ads, '.arr_to_str($adService->getErrors()) , 'danger');
	}
}

if(!empty($req['filter'])) {
	$validFilters = array_filter([
		'ads.listtypecode' => $req['listtypecode'],
		'listing.proptypecode' => $req['proptypecode'],
		'listing.propclasscode' => $req['propclasscode'],
		'listing.loccitycode' => $req['loccitycode'],
		'listing.usercode' => whoIs('usercode')
	]);

	$adLists = $adService->getAll([
		'where' => $validFilters
	]);
} else {
  $adLists = $adService->getAll([
	'where' => [
		'listing.usercode' => whoIs('usercode')
	]
  ]);
}

$isSubmitted = isSubmitted();
$isShowAccordition = !empty($_GET['create_ad']) || $isSubmitted;
?>
<!-- Create Listing -->
<div class="card">
	<div class="card-body">
		<div class="accordion" id="accordionExample">
			<div class="accordion-item">
				<h2 class="accordion-header" id="headingTwo">
					<button class="<?php echo $isShowAccordition ? 'accordion-button' : 'accordion-button collapsed'?>" type="button" 
						data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="<?php echo $isShowAccordition ? 'true' : 'false'?>" aria-controls="collapseTwo">
						<i class="me-3 fa fa-clipboard-list"></i>Create New Ads
					</button>
				</h2>
				<div id="collapseTwo" class="<?php echo $isShowAccordition ? 'accordion-collapse collapse show' : 'accordion-collapse collapse'?>" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
					<div class="accordion-body">
						<?php Flash::show()?>
						<?php echo $formAd->start()?>
							<?php echo $formAd->get('adscode')?>
							<div class="row px-4">
								<div class="col-sm-6">
									<div class="form-floating mb-3">
										<?php echo $formAd->getCol('listtypecode')?>
									</div>
									<div class="form-floating mb-3">
										<?php 
											if(!empty($req['listingcode'])) {
											echo $formAd->getCol('listingcode',[
												'attributes' => [
													'disabled' => true,
													'class' => 'form-control'
												]
											]);
											//re-write
											echo $formAd->addAndCall([
												'name' => 'listingcode',
												'type' => 'hidden',
												'value' => $req['listingcode']
											]);
											} else {
											echo $formAd->getCol('listingcode');
											}
										?>
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

									<input type="submit" name="btn_create_prop"
										class="mx-1 btn btn-info bg-col1 border-0 text-white"
										value="Save Entry">
								</div>
							</div>
						<?php echo $formAd->end()?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--- laoad ads -->
<div class="card">
	<div class="card-body">
		<?php echo $formAd->start(['method' => 'GET']);?>
			<div class="d-flex flex-lg-row flex-column justify-content-center">
				<div class="">
					<div class="form-floating m-1">
						<?php echo $formAd->getCol('listtypecode', [
							'required' => false
						])?>
					</div>
				</div>
				<div class="">
					<div class="form-floating m-1">
						<?php echo $listingForm->getCol('proptypecode', [
							'required' => false
						])?>
					</div>
				</div>
				<div class="">
					<div class="form-floating m-1">
						<?php echo $listingForm->getCol('propclasscode', [
							'required' => false
						])?>
					</div>
				</div>
				<div class="">
					<div class="form-floating m-1">
						<?php echo $listingForm->getCol('loccitycode', [
							'required' => false
						])?>
					</div>
				</div>
				<div class="m-1">
					<button type="input" role="button" name="filter" value="addfilter"
					class="btn btn-warning px-3" style="height: 58px;" ><i class="fa fa-search"></i>
				   </button>
				</div>
			</div>
		<?php echo $formAd->end()?>
	</div>
	<div class="card-body">
	    <?php html_breadcrumbs_basic($validFilters ?? '', _route('ads_create')); ?>
		<div class='d-flex justify-content-evenly flex-wrap'>
			<?php if(empty($adLists)) :?>
				<p>No ads found.</p>
			<?php else :?>
				<?php foreach($adLists as $key => $row):?>
					<?php
						$toggler = $row['status'];
						$togglerClass = $row['status'] == 'on' ? 'btn-success' : 'btn-danger';

						$images = $listingService->getImages($row['module_folder_name']);
						$imageA = "public/uploads/images/{$row['module_folder_name']}/{$images[0]}";	
					?>
					<div class='card max300W'>
						<div>
						<img src='<?php echo $imageA?>' class='card-img-top rounded imgbox' alt='' style='filter:brightness(50%);'>
						<div class='position-absolute bottom-0 start-50 translate-middle-x text-white text-center w-100'>
							<div class='p-4'>
								<?php html_ads_star_link($row['star_id'], $row['status'], '', seal($row['recno']));?>
								<div><?php echo amountHTML($row['price'])?></div>
								<div><?php echo crop_string($row['adsdesc'], 10)?></div>
								<small class='text-truncate'><?php echo $row['listtypecode']?></small>
							</p>
							<div class='mt-0'>
								<?php 
									echo wLinkDefault(_route('ads_edit', [
										'recno' => seal($row['recno'])
									]), '', ['icon' => 'fa fa-edit', 'class' => 'btn btn-sm btn-outline-light rounded-circle']);

									echo wLinkDefault(_route('prop_detail', [
										'adId' => seal($row['recno'])
									]), '', ['icon' => 'fa fa-eye', 'class' => 'btn btn-sm btn-outline-light rounded-circle']);

									echo wLinkDefault(_route('ads_actions', [
										'action' => 'toggle_ad',
										'recno'  => seal($row['recno'])
									]), '', ['icon' => 'fa fa-toggle-'.$toggler, 'class' => "btn btn-sm {$togglerClass} rounded-circle"]);
								?>
							</div>
							</div>
						</div>
						</div>
					</div>
				<?php endforeach?>
			<?php endif?>
		</div>
	</div>
</div>
<?php endbuild()?>

<?php build('scripts')?>
	<script src="<?php echo _path_public('js/ads.js')?>"></script>
<?php endbuild()?>
<?php loadTo()?>