<?php build('content') ?>
<?php 
	_forAuthPageOnly();
	use Form\ListingForm;
	load(['ListingForm'], FORMS);
	load(['Form'], HELPERS);

	global $_formCommon;
	$req =  request()->inputs();
	$listingService = new ListingService();
	$listingForm = new ListingForm();

	if(!empty($req['filter'])) {
		$filter = array_filter([
			'listing.propclasscode' => $req['propclasscode'],
			'listing.loccitycode' => $req['loccitycode'],
			'listing.proptypecode' => $req['proptypecode'],
			'listing.usercode' => whoIs('usercode')
		]);

		$listings = $listingService->getAll([
			'order' => 'listingcode asc, '.$listingService->_primaryKey.' desc',
			'where' => $filter
		]);
	} else {
		$listings = $listingService->getAll([
			'where' => [
				'listing.usercode' => whoIs('usercode')
			],
			'order' => 'listingcode asc, '.$listingService->_primaryKey.' desc',
		]);
	}
	

	$_formCommon->setOptionValues('sort',[
		'proptypecode' => 'Property Type',
		'propclasscode' => 'Property Class',
		'loccitycode' => 'Location'
	]);

	$listingForm->setValue('usercode', whoIs('usercode'));

	/**
	 * list to submit event
	 */
	if(isSubmitted()) {
		$post = request()->posts();
		$isOkayImageCheck = $listingService->imageRequiredCheck($post['module_folder_name']);

		if(!$isOkayImageCheck) {
			Flash::set("Listing cannot be uploaded, All listing must have photos", 'danger');
			return request()->return();
		}
		$response = $listingService->store($post);
		/**
		 * checks if returns true or false
		 */
		if($response) {
			/**
			 * print notice on the screen for successfull entry
			 */
			Flash::set(arr_to_str($listingService->getMessages()));
			return redirect(_route('prop_show', [
				'recno' => seal($response)
			]));
		} else {
			/**
			 * print notice on the screen for unsuccessfull entry
			 */
			Flash::set('Unable to create listing', 'danger');
		}
	}

	$imageFolder = random_letter(10);
	$listingForm->setValue('module_folder_name', $imageFolder);
?>
<div class="card">
	<div class="card-body">
		<div class="accordion accordion-flush" id="accordionFlush">
			<div class="accordion-item">
				<h2 class="accordion-header" id="flush-headingOne">
					<button class="accordion-button py-1" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
						<h5><i class="me-3 fa fa-clipboard-list"></i>Create New Listing</h5>
					</button>
				</h2>
				<div id="flush-collapseOne" class="accordion-collapse" 
				aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlush">
					<div class="accordion-body p-0">
						<?php Flash::show()?>
						<?php echo $listingForm->start()?>
							<?php echo $listingForm->get('usercode')?>
							<?php echo $listingForm->get('listingkeys')?>
							<?php echo $listingForm->getCol('module_folder_name'); ?>
						<div class="row px-4 mt-3">
							<div class="col-sm-6">
								<div class="form-floating mb-3">
									<?php echo $listingForm->getCol('proptypecode')?>
								</div>
								<div class="form-floating mb-3">
									<?php echo $listingForm->getCol('propclasscode')?>
								</div>
								<div class="form-floating mb-3">
									<?php echo $listingForm->getCol('listingcode')?>
								</div>
								<div class="form-floating mb-3">
									<?php echo $listingForm->getCol('listingdescription')?>
								</div>
								<div class="form-floating mb-3">
									<?php echo $listingForm->getCol('loccitycode')?>
								</div>
								<div class="form-floating mb-3">
									<?php echo $listingForm->getCol('propaddress')?>
								</div>
								<div class="form-floating mb-3">
									<?php echo $listingForm->getCol('buildingname')?>
								</div>

								<input type="submit" name="btn_create_prop" id="btn_create_prop"
									class="mx-1 btn btn-info bg-col1 border-0 text-white"
									value="Save Entry">
							</div>

							<div class="col-sm-6">
								<h3>Upload Listing Image Here.</h3>
								<div>
									<form action="#">
										<input type="file" class="my-pond" name="filepond"/>
										</form>
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

<!-- Catalogue -->
<div class="card mt-4">
	<!-- Search Nav -->
	<div class="card-body">
		<?php
			Form::open([
				'method' => 'get'
			]);

			Form::hidden('filter', 'listing_filter');
		?>
		<div class="d-flex flex-lg-row flex-column justify-content-center">
				<div class="">
					<div class="form-floating m-1">
						<?php echo $listingForm->getCol('proptypecode',[
							'required' => false
						])?>
					</div>
				</div>
				<div class="">
					<div class="form-floating m-1">
						<?php echo $listingForm->getCol('propclasscode',[
							'required' => false
						])?>
					</div>
				</div>
				<div class="">
					<div class="form-floating m-1">
					<?php echo $listingForm->getCol('loccitycode',[
							'required' => false
						])?>
					</div>
				</div>
				<div class="" style="display: none;">
					<?php 
						$_formCommon->getCol('sort');
					?>
				</div>
				<div class="m-1">
					<button id="createProperty" type="submit" role="button"
					class="btn btn-warning px-3" 
					style="height: 58px;" ><i class="fa fa-search"></i></button>
				</div>
		</div>
		
		<?php Form::close()?>

		<?php if(!empty($req['filter'])) :?>
			<div class="mt-2">
				<p>Clear Filter : <?php echo implode(',', array_values($filter))?> </p>
			</div>
		<?php endif?>
	</div>

	<!-- Catalogue Result-->
	<div id="load_here" class="card-body p-4">
		<div class='d-flex justify-content-evenly flex-wrap'>
			<?php foreach($listings as $key => $listing) :?>
				<?php
					if(!empty($listing['module_folder_name'])) {
						$images = $listingService->getImages($listing['module_folder_name']);
						$imageA = "public/uploads/images/{$listing['module_folder_name']}/{$images[0]}";	
					} else {
						$imageA = DEFAULT_PROPERTY_IMAGE;
					}
				?>
				<div class='card max300W'>
					<div>
						<img src='<?php echo $imageA?>' class='card-img-top rounded imgbox' 
						alt='Property Listing Image'>
						<div class='position-absolute top-0 start-50 translate-middle-x text-white text-end w-100 p-2'>
							<?php echo $listing['listingcode']?>
						</div>
						<div class='position-absolute bottom-0 start-50 translate-middle-x text-white text-center w-100'>
						<div class='p-4' style='border: ;'>
							<p class='mb-2 text-truncate'><?php echo $listing['listingcode']?></p>
							<div class='mt-0'>
							<?php echo wLinkDefault(_route('prop_show', [
								'recno' => seal($listing['recno'])
							]), '', ['icon' => 'fa fa-eye', 'class' => 'btn btn-sm btn-outline-light rounded-circle'])?>

							<?php echo wLinkDefault(_route('prop_edit', [
								'recno' => seal($listing['recno'])
							]), '', ['icon' => 'fa fa-edit', 'class' => 'btn btn-sm btn-outline-light rounded-circle'])?>

							<?php echo wLinkDefault(_route('prop_delete', [
								'recno' => seal($listing['recno'])
							]), '', ['icon' => 'fa fa-trash', 'class' => 'btn btn-sm btn-outline-light rounded-circle'])?>
							
							<button onclick='addads($a,`$j`)' type='button' class='btn btn-sm btn-outline-light rounded-circle' 
							data-bs-toggle='tooltip' data-bs-placement='top' title='' data-bs-original-title='Add Ads for this Item'><i class='fa fa-plus'></i></button>
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

<?php build('headers')?>
	<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
	<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet"/>
<?php endbuild()?>
<?php build('scripts')?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
    <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>

	<script>
        $(function(){
            $.fn.filepond.registerPlugin(FilePondPluginImagePreview);
            $.fn.filepond.setDefaults({
                server: {
                    url : '<?php echo URL?>/',
                    process : {
						url : 'api/filepondupload.php?imageFolder=<?php echo $imageFolder?>',
                        method : 'post',
                        onload : function(response) {
							console.log([
								response
							]);
                            $("#btn_create_prop").show();
                        }
                    }
                },
                allowMultiple : true
            });
            // Turn input element into a pond with configuration options
            $('.my-pond').filepond();

			$('.my-pond').on('FilePond:addfile', function(e){
				$("#btn_create_prop").hide();
			});
        });
    </script>
<?php endbuild()?>
<!-- NOT PROP CREATE-->
<?php loadTo()?>