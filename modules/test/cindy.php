<?php 
	use Form\ListingForm;
	load(['ListingForm'], FORMS);
	global $_formCommon;

	$listingForm = new ListingForm();
	$_formCommon->setOptionValues('sort',[
		'proptypecode' => 'Property Type',
		'propclasscode' => 'Property Class',
		'loccitycode' => 'Location'
	]);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>
	<link rel="shortcut icon" type="image/png" href="<?php echo _path_tmp('main/img/logo30x30.png')?>">

	<link rel="stylesheet" href="<?php echo _path_tmp('main/css/bootstrap.min.css')?>">

	<link rel="stylesheet" href="<?php echo _path_tmp('main/css/all.min.css')?>">
	<link rel="stylesheet" href="<?php echo _path_tmp('main/css/animations.css')?>">
	<link rel="stylesheet" href="<?php echo _path_tmp('main/dropzone/dropzone.min.css')?>">
	<link rel="stylesheet" href="<?php echo _path_tmp('main/mycss.css?ver=$curr')?>">

	<script src="<?php echo _path_tmp('js/jquery-3.6.0.min.js')?>"></script>
	<script src="<?php echo _path_tmp('js/bootstrap.bundle.min.js')?>"></script>
	<script src="<?php echo _path_tmp('js/popper.min.js')?>"></script>
	<script src="<?php echo _path_tmp('js/all.min.js')?>"></script>
	<script src="<?php echo _path_tmp('dropzone/dropzone.min.js')?>"></script>
	<script src="<?php echo _path_tmp('myjs.js?ver=$curr')?>"></script>
</head>
<body class="backcolor">
	<div class="offsettop"></div>
	<!-- Main -->
	<main class="maxw1080 m-auto">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-9">
					<div class="allpost p-0">

						<!-- Create Listing -->
						<div class="card">
							<div class="card-body">
								<div class="accordion accordion-flush" id="accordionFlush">
									<div class="accordion-item">
										<h2 class="accordion-header" id="flush-headingOne">
											<button class="accordion-button collapsed py-1" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
		  										<h5><i class="me-3 fa fa-clipboard-list"></i>Create New Listing</h5>
											</button>
										</h2>
										<div id="flush-collapseOne" class="accordion-collapse" 
										aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlush">
											<div class="accordion-body p-0">
												<p class="mt-2">Create your property list here...</p>
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
													</div>
													<div class="col-sm-6">
														<small>Upload Photo</small>
														<form id="dropzone1" action="upload.php" class="dropzone text-center mb-3">
														</form>


														<div class="d-flex flex-row-reverse">
															
															<button id="A_butt" type="button" class="mx-1 btn btn-info bg-col1 border-0 text-white">
																<i class="fa fa-star me-2"></i>Create
															</button>
															<button id="A_res_photo" type="button" class="mx-1 btn btn-info bg-col1 border-0 text-white">
																<i class="fa fa-star me-2"></i>Reset Photo
															</button>
														</div>
													</div>
												</div>
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
								<div class="d-flex flex-lg-row flex-column justify-content-center">
										<div class="">
											<div class="form-floating m-1">
												<?php echo $listingForm->getCol('proptypecode')?>
											</div>
										</div>
										<div class="">
											<div class="form-floating m-1">
												<?php echo $listingForm->getCol('propclasscode')?>
											</div>
										</div>
										<div class="">
											<div class="form-floating m-1">
												<?php echo $listingForm->getCol('loccitycode')?>
											</div>
										</div>
										<div class="" style="display: none;">
											<?php echo $_formCommon->getCol('sort')?>
										</div>
										<div class="m-1">
											<button id="B_find" type="button" class="btn btn-warning px-3" style="height: 58px;" ><i class="fa fa-search"></i></button>
										</div>
								</div>
							</div>

							<!-- Catalogue Result-->
							<div id="load_here" class="card-body p-4">

							</div>
						</div>	

					</div>
				</div>
				<?php echo sideadsfooter_tag();?>
			</div>
		</div>
	</main>


	<!-- Modal delete listing -->
	<div id="del_listing" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="del_listingLabel">Delete Listing</h5>
		        <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
		      </div>
		      <div id="load_item_del">
		      	<div class="modal-body">
		      		<div class="alert alert-info" role="alert">
					  You cannot delete a Listing item if it has an Ads...
					</div>
		      	</div>
		      </div>
		    </div>
		</div>
	</div>

	<!-- Modal edit listing -->
	<div id="edit_listing" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-lg">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="edit_listingLabel">Edit Listing</h5>
		        <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
		      </div>
		      <div class="modal-body">
		      		<div id="">
						<p class="mt-2">Edit your property list here...</p>
						<div class="row px-4">
							<div class="col-sm-6">
									<div class="form-floating mb-3" style="display: none;">
										<input type="number" class="form-control" id="C_id" readonly>
										<label for="C_id">ID</label>
									</div>
									<div class="form-floating mb-3" style="display: none;">
										<input type="text" class="form-control" id="C_listingkey" readonly>
										<label for="C_listingkey">ID</label>
									</div>
									<div class="form-floating mb-3">
										<select class="form-select" id="C_a" aria-label="">
											<?php echo opt_propertytype("","Choose");?>
										</select>
										<label for="C_a">Property Type Code</label>
									</div>
									<div class="form-floating mb-3">
										<select class="form-select" id="C_b" aria-label="" disabled="">
											<?php //echo opt_propertyclass("","Choose");?>
										</select>
										<label for="C_b">Property Class Code</label>
									</div>
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="C_c" placeholder="Listing Name">
										<label for="C_c">Listing Name</label>
									</div>
									<div class="form-floating mb-3">
										<textarea class="form-control" placeholder="Listing Description" id="C_d" style="height: 200px"></textarea>
										<label for="C_d">Listing Description</label>
									</div>
									<div class="form-floating mb-3">
										<select class="form-select" id="C_e" aria-label="">
											<?php echo opt_locationcity("","Choose");?>
										</select>
										<label for="C_e">Property Location Code</label>
									</div>
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="C_f" placeholder="Proprety Address">
										<label for="C_f">Proprety Address</label>
									</div>
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="C_g" placeholder="Building Name">
										<label for="C_g">Building Name (optional)</label>
									</div>
									<div class="d-flex flex-row-reverse">
										<button onclick="get_data_val_for_C();" id="C_butt" type="button" class="btn btn-info bg-col1 border-0 text-white">
											<i class="fa fa-star me-2"></i>Update Details
										</button>
									</div>
									<br><br>
								</div>
								<div class="col-sm-6">
									<small>Current Photo</small>
									<div id="load_curr_photo">
										
									</div>
									<br>
									<small>Upload Photo</small>
									<form id="dropzone2" action="upload.php" class="dropzone text-center mb-3">
									</form>

									<div class="d-flex flex-row-reverse">
										<button id="C_butt_photo" type="button" class="btn btn-info bg-col1 border-0 text-white">
											<i class="fa fa-star me-2"></i>Upload Photo
										</button>
									</div>
							</div>
						</div>
					</div>
		      </div>
		    </div>
		</div>
	</div>

	<script type="text/javascript">
		var listingkey;
		var htmltbname = "<?php echo $htmltbname;?>";
		var htmlpage = "<?php echo $htmlpage;?>";
		var id_to_del = 0;
		Dropzone.autoDiscover = false;
		loaderon();
		window.addEventListener("load", function(){
			check_value_and_disable("A_a","A_b");
			check_value_and_disable_onchange("A_a","A_b");
			check_value_and_disable("B_a","B_b");
			check_value_and_disable_onchange("B_a","B_b");
			check_value_and_disable("C_a","C_b");
			check_value_and_disable_onchange("C_a","C_b");
			getserchval2(htmltbname,"","",1,htmlpage);
			loaderoff();


		});


		function addads(id,code){
			loadMyURL("ads.php?key="+code);
		}
		

		function delme(id){
			loaderon();
			$("#err").load("router.php",{
				del_id: id
		    });
		}
		
		function del_dis_photo(path,id){
			loaderon();
			$("#err").load("router.php",{
				del_dis_photo: path
		    });
		    load_curr_photo(id);
		}

		function load_curr_photo(id){
			loaderon();
			$("#load_curr_photo").load("router.php",{
				load_curr_photo: id
		    });
		}


		//load_item_del
		function loaditemdel(id){
			id_to_del = id;
			loaderon();
			$("#load_item_del").load("router.php",{
				load_item_del_id: id
		    });
		}

		function getserchval2(htmltbname,searchdata,sort,page,htmlpage){
			loaderon();
			pagenumcurrent = page;
			$("#load_here").load("router.php",{
			      load_table2_htmltbname: htmltbname,
			      load_table2_searchdata: searchdata,
			      load_table2_sort: sort,
			      load_table2_page: page,
			      load_table2_htmlpage: htmlpage
			});
		}

		function getfind(){
			let a = $("#B_a").val();
			let b = $("#B_b").val();
			let c = $("#B_c").val();

			let tx_a = "";
			let tx_b = "";
			let tx_c = "";

			if(a != ""){
				tx_a = $("#B_a option:selected").text();
			}
			if(b != ""){
				tx_b = $("#B_b option:selected").text();
			}
			if(c != ""){
				tx_c = $("#B_c option:selected").text();
			}
			
			let data = '{"test":[{"nm_0":"'+a+'", "nm_1":"'+b+'","nm_2":"'+c+'"},{"tx_0":"'+tx_a+'", "tx_1":"'+tx_b+'","tx_2":"'+tx_c+'"}]}';
			let sort = $("#B_sort").val();
			getserchval2(htmltbname,data,sort,pagenumcurrent,htmlpage);
		}

		function editme_loadval(lk,id,a,b,c,d,e,f,g){
			$("#C_listingkey").val(lk);
			$("#C_id").val(id);
			$("#C_a").val(a).change();
			$("#C_c").val(filterSymbols(c,"yes"));
			$("#C_d").val(filterSymbols(d,"yes"));
			$("#C_e").val(e).change();
			$("#C_f").val(filterSymbols(f,"yes"));
			$("#C_g").val(filterSymbols(g,"yes"));
			setTimeout(function(){ $("#C_b").val(b).change(); }, 500);
			myDZ2.removeAllFiles(true);
			load_curr_photo(id);
		}

		function get_data_val_for_A(listingkey){
			let a = $("#A_a").val();
			let b = $("#A_b").val();
			let c = filterSymbols($("#A_c").val());
			let d = filterSymbols($("#A_d").val());
			let e = $("#A_e").val();
			let f = filterSymbols($("#A_f").val());
			let g = filterSymbols($("#A_g").val());
			if(a == "" || b == "" || c == "" || d == "" || e == "" || f == ""){
				$("#err").append(errmsg("info","Attention!","Missing entries...")); 
				loaderoff(); 
			}else{
				const data = '{ "a":"'+a+'" , "b":"'+b+'" , "c":"'+c+'", "d":"'+d+'", "e":"'+e+'", "f":"'+f+'", "g":"'+g+'" , "listingkey":"'+listingkey+'" }';
				$("#err").load("upload.php",{
					save_listing_data: data
			    });
			}
			
		}

		function get_data_val_for_C(){
			loaderon();
			let id = $("#C_id").val();
			let a = $("#C_a").val();
			let b = $("#C_b").val();
			let c = filterSymbols($("#C_c").val());
			let d = filterSymbols($("#C_d").val());
			let e = $("#C_e").val();
			let f = filterSymbols($("#C_f").val());
			let g = filterSymbols($("#C_g").val());
			if(a == "" || b == "" || c == "" || d == "" || e == "" || f == ""){
				$("#err").append(errmsg("info","Attention!","Missing entries...")); 
				loaderoff(); 
			}else{
				const data = '{ "id":"'+id+'" , "a":"'+a+'" , "b":"'+b+'" , "c":"'+c+'", "d":"'+d+'", "e":"'+e+'", "f":"'+f+'", "g":"'+g+'"}';
				$("#err").load("router.php",{
					save_listing_data_C: data
			    });
			}
		}






		

		$("#A_butt").on("click",function(){
			listingkey = Date.now();
			loaderon();
			let a = $("#A_a").val();
			let b = $("#A_b").val();
			let c = $("#A_c").val();
			let d = $("#A_d").val();
			let e = $("#A_e").val();
			let f = $("#A_f").val();
			let g = $("#A_g").val();


			myDZ1.on("sending", function(file, xhr, formData) {
				formData.append("myDZ1",listingkey);
				//formData.append("myDZ1_data", get_data_val_for_A());
			});

			myDZ1.on("complete", function(file) {
				//myDZ1.removeAllFiles(true);
			   //$("#err").append(errmsg("info","Good!","File uploaded..."));
			})
			
			myDZ1.on("success", function (file, response) {
				console.log(response);
				if(response !== ""){
					$("#err").append(errmsg("info","Ooops!",response));
				}
			});

			myDZ1.on("error", function (file, error, xhr) {
				//$("#err").append(errmsg("danger","Ooops!","File error..."));
			});


			myDZ1.on("queuecomplete", function (file) {
		    	//ads.php?key=1645871988385
		    	loadMyURL("ads.php?key="+listingkey);
		    });

			if(a == "" || b == "" || c == "" || d == "" || e == "" || f == ""){
				$("#err").append(errmsg("info","Attention!","Missing entries...")); 
				loaderoff(); 
			}else{
				if (myDZ1.files != "") {
					get_data_val_for_A(listingkey);
			        myDZ1.processQueue();
			        loaderoff();
			    } else {
					$("#err").append(errmsg("info","Attention!","No files need to upload. Pls select one file...")); 
					loaderoff(); 
			    }
			}
		});

		
		$("#A_res_photo").on("click",function(){
			myDZ1.removeAllFiles(true);
		});

		$("#B_find").on("click",function(){
			pagenumcurrent = 1;
			getfind();
		});

		$("#C_butt_photo").on("click",function(){
			loaderon();
			let lk = $("#C_listingkey").val();
			let id = $("#C_id").val();
			
			myDZ2.on("sending", function(file, xhr, formData) {
				formData.append("myDZ2",lk);
				//formData.append("myDZ1_data", get_data_val_for_A());
			});

			myDZ2.on("complete", function(file) {
				//myDZ2.removeAllFiles(true);
				load_curr_photo(id);
			   //$("#err").append(errmsg("info","Good!","File uploaded..."));
			})
			
			myDZ2.on("success", function (file, response) {
				console.log(response);
				if(response !== ""){
					$("#err").append(errmsg("info","Ooops!",response));
				}
			});

			myDZ2.on("error", function (file, error, xhr) {
				//$("#err").append(errmsg("danger","Ooops!","File error..."));
			});

			if (myDZ2.files != "") {
		        myDZ2.processQueue();
		        loaderoff();
		    } else {
				$("#err").append(errmsg("info","Attention!","No files need to upload. Pls select one file...")); 
				loaderoff(); 
		    }
		});


		let myDZ1 = new Dropzone("#dropzone1", {
		    autoProcessQueue : false,
		    acceptedFiles: "image/jpeg",
		    maxFiles: 10,
		    maxFilesize: 3, // MB
		    parallelUploads: 15, 
		    createImageThumbnails: true,
		    addRemoveLinks: true,
		    dictFileTooBig: "File is to big ({{filesize}}mb). Max allowed file size is {{maxFilesize}}mb.",
		    dictInvalidFileType: "Invalid File Type.",
		    dictMaxFilesExceeded: "Only {{maxFiles}} files are allowed.",
		    dictDefaultMessage: "Drop 10 Photos with file size limited to 3mb..."
		});

		let myDZ2 = new Dropzone("#dropzone2", {
		    autoProcessQueue : false,
		    acceptedFiles: "image/jpeg",
		    maxFiles: 10,
		    maxFilesize: 3, // MB
		    parallelUploads: 15, 
		    createImageThumbnails: true,
		    addRemoveLinks: true,
		    dictFileTooBig: "File is to big ({{filesize}}mb). Max allowed file size is {{maxFilesize}}mb.",
		    dictInvalidFileType: "Invalid File Type.",
		    dictMaxFilesExceeded: "Only {{maxFiles}} files are allowed.",
		    dictDefaultMessage: "Drop 10 Photos with file size limited to 3mb..."
		});




	</script>
</body>
</html>

