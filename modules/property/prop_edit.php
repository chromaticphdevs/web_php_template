<?php build('content') ?>

<?php 
use Form\ListingForm;
load(['ListingForm'], FORMS);
global $_formCommon;

$req = request()->inputs();

$listingService = new ListingService();
$listingForm = new ListingForm();
	//post request
	if(isSubmitted()) {
		$post = request()->posts();
			$isOkay = $listingService->update($post, [
			'recno' => $post['recno']
		]);

		if($isOkay) {
			Flash::set("Listing Updated Successfully");
			return redirect(_route('prop_show', [
				'recno' => seal($post['recno'])
			]));
		} else {
			Flash::set("Unable to update property");
		}
		return redirect(_route('prop_edit', ['recno' => seal($post['recno'])]));
	}

	$propId = unseal($req['recno']);
	$listing = $listingService->single([
		'where' => [
			'listing.recno' => $propId
		]
	]);

	$fileImages = [];
	$imageFolder = $listing['module_folder_name'];
	$listingForm->setValueObject($listing);
	$listingForm->setValue('usercode', whoIs('usercode'));
	
	if(!is_null($imageFolder)) {
		$fileImages = filter_files_only(scandir("public/uploads/images/{$imageFolder}"));
	}
?>
<div class="card">
	<div class="card-header">
		<h4 class="card-title"><i class="me-3 fa fa-clipboard-list"></i>Edit Listing</h4>
		<?php 
			echo wLinkDefault(_route('prop_show', [
				'recno' => seal($propId)
			]), 'Cancel Edit', ['class' => 'btn btn-primary btn-sm'])
		?>
	</div>
	<div class="card-body">
	    <?php Flash::show()?>
		<?php echo $listingForm->start()?>
		<?php echo $listingForm->get('usercode')?>
		<?php echo $listingForm->get('listingkeys')?>
		<?php echo $listingForm->get('module_folder_name')?>
		<input type="hidden" value="<?php echo $listing['recno']?>" name="recno">
		<div class="row px-4">
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

				<input type="submit" name="btn_create_prop"
					class="mx-1 btn btn-info bg-col1 border-0 text-white"
					value="Save Changes">
			</div>

			<div class="col-md-6">
				<h3>Upload Listing Image Here.</h3>
				<div>
					<form action="#">
						<input type="file" class="my-pond" name="filepond"/>
					</form>
				</div>
				<?php foreach($fileImages as $key => $row) :?>
					<div style="display:inline-block;border:1px solid #eee; padding:12px">
						<img src="public/uploads/images/<?php echo "{$imageFolder}/{$row}"?>"
						style="width:200px">
						<div class="text-center"><?php echo wLinkDefault(_route('resource_delete', null, [
							'path' => seal("public/uploads/images/{$imageFolder}/{$row}")
						]), 'Remove Image')?></div>
					</div>
				<?php endforeach?>
			</div>
		</div>
		<?php echo $listingForm->end()?>
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
                    url : 'http://localhost/website_archi/api/',
                    process : {
						url : 'filepondupload.php?imageFolder=<?php echo $imageFolder?>',
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

<?php loadTo()?>