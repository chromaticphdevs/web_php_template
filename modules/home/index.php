<!DOCTYPE html>
<html>
<head>
	<?php echo head_tag($mainpath);?>
	<?php $companynameHTML = 'SAMPLE'?>
</head>
<body class="backcolor">
	<?php echo loader_tag();?>
	<!-- Top Nav -->


	<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-white shadow">
		<div class="container-fluid">
			<a class="navbar-brand m-0 w-50" href="index.php">
				<img src="data/img/logo100x100.png" class="img-fluid p-0" alt="img-fluid" width="50" height="50">
				<span class="fonttitle"><?php echo $companynameHTML;?></span>
			</a>
			<button class="navbar-toggler border-0 me-0" type="button" data-bs-toggle="collapse" data-bs-target="#nav1" aria-controls="nav1" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse hide flex-md-row-reverse" id="nav1">
				<div class="navbar-nav">
						<div class="nav-link px-1">
							<div class="form-floating">
								<select class="form-select Wsmall" id="A_a" aria-label="Listing Type">
									<?php echo opt_listingtype("","Any");?>
								</select>
								<label for="A_a">Listing Type</label>
							</div>
						</div>
						<div class="nav-link px-1">
							<div class="form-floating">
								<select class="form-select Wsmall" id="A_b" aria-label="Property Type">
									<?php echo opt_propertytype("","Any");?>
								</select>
								<label for="A_b">Property Type</label>
							</div>
						</div>
						<div class="nav-link px-1">
							<div class="form-floating">
								<select class="form-select Wsmall" id="A_c" aria-label="Property Class">
									<?php echo opt_propertyclass("","Any");?>
								</select>
								<label for="A_c">Property Class</label>
							</div>
						</div>
						<div class="nav-link px-1">
							<div class="form-floating">
								<select class="form-select Wsmall" id="A_d" aria-label="Location">
									<?php echo opt_locationcity("","Any");?>
								</select>
								<label for="A_d">Location</label>
							</div>
						</div>
						<div class="nav-link px-1">
							<div class="form-floating">
								<input type="text" class="form-control Wsmall" id="A_e" placeholder="Search">
								<label for="A_e">Search Key</label>
							</div>
						</div>
						<div class="btn-group my-2" role="group" aria-label="Large button group" style="height: 58px;">
						  <button id="A_find" type="button" class="btn btn-warning" style="max-width: 100px;"><i class="fa fa-search"></i></button>
						  <button onclick="location.href=`login.php`;" type="button" class="btn btn-info bg-col1 border-0 text-white">List your property</button>
						</div>
						<!-- <div class="nav-link px-1">
							<button id="A_find" type="button" class="btn btn-warning px-3" style="height: 58px;" ><i class="fa fa-search"></i></button>
							<button id="A_find" type="button" class="btn btn-warning px-3" style="height: 58px;" ><i class="fa fa-search"></i></button>
						</div>
			        	<button type="button" class="btn btn-info bg-col1 px-4 mx-lg-2 border-0 text-white" style="margin: auto;"><i class="fa fa-sign-in-alt"></i> List your property</button><br> -->
			    </div>
			</div>
		</div>
	</nav>




	<br><br><br>
	<!-- Main -->
	<main class="mt-5">
		<div class="maxw1080 m-auto px-sm-5">
			<!-- Catalogue -->
			<div class="card" style="overflow: hidden;">
				<div id="load_here" class="card-body p-4">
				</div>
			</div>
		</div>
	</main>
	
	<!-- Side Ads -->
	<main class="mt-4">
		<div class="maxw1080 m-auto px-sm-5" style="overflow: hidden;">
			<div class="row g-0">
			  <div class="col-lg-6"><?php echo sideadsindex_tag2("cat4");?></div>
			  <div class="col-lg-6"><?php echo sideadsindex_tag2("cat5");?></div>
			</div>
		</div>
	</main>

	<!-- Featured Items -->
	<main class="mt-4">
		<div id="loadfeaturedunitA" class="maxw1080 m-auto px-sm-5">
			
		</div>
	</main>

	<!-- Featured Items -->
	<main class="mt-4">
		<div id="loadfeaturedunitB" class="maxw1080 m-auto px-sm-5">
			
		</div>
	</main>
	


	


	<!-- Index Footer -->
	<div class="bg-col1 mt-5" style="border-top: solid #FFB74A 10pt;">
		<?php echo cfg::get("indexfooterHTML");?>
	</div>
	
	<!-- Index Copyright -->
	<div class="" style="background-color: #003046;">
		<?php echo cfg::get("indexcopyrightHTML");?>
	</div>



	<?php echo error_tag();?>
	<script type="text/javascript">
		loaderon();
		var htmltbname = "<?php echo $htmltbname;?>";
		var htmlpage = "<?php echo $htmlpage;?>";
		
		window.addEventListener("load", function(){
			let search_a = sessionStorage.getItem('search_a');
			let search_b = sessionStorage.getItem('search_b');
			let search_c = sessionStorage.getItem('search_c');
			let search_d = sessionStorage.getItem('search_d');
			let search_e = sessionStorage.getItem('search_e');
			
			$("#A_a").val(search_a);
			$("#A_b").val(search_b);
			$("#A_c").val(search_c);
			$("#A_d").val(search_d);
			$("#A_e").val(search_e);

			
			getfind();
			check_value_and_disable("A_b","A_c");
			check_value_and_disable_onchange("A_b","A_c");
			loadfeaturedunitA("Gold","loadfeaturedunitA");
			loadfeaturedunitA("Silver","loadfeaturedunitB");

			change_select_val("A_c",search_c);
			setTimeout(setdis, 2000);
			//loaderoff();
		});


		function setdis(){
			let search_c = sessionStorage.getItem('search_c');
			change_select_val("A_c",search_c);
		}

		function loadfeaturedunitA(what,divid){
			let disloc = $("#A_d").val();
			$("#"+divid).load("router.php",{
				load_featured_unitA: what,
				load_featured_unitA_loc: disloc
			});
		}

		function getfind(){
			let a = $("#A_a").val();//listingtype
			let b = $("#A_b").val();//proptype
			let c = $("#A_c").val();//propclass
			let d = $("#A_d").val();//loc
			let e = $("#A_e").val();//key

			//memorise
			sessionStorage.setItem('search_a', a);
			sessionStorage.setItem('search_b', b);
			sessionStorage.setItem('search_c', c);
			sessionStorage.setItem('search_d', d);
			sessionStorage.setItem('search_e', e);
			

			let tx_a = "";
			let tx_b = "";
			let tx_c = "";
			let tx_d = "";
			let tx_e = e;

			if(a != ""){
				tx_a = $("#A_a option:selected").text();
			}
			if(b != ""){
				tx_b = $("#A_b option:selected").text();
			}
			if(c != ""){
				tx_c = $("#A_c option:selected").text();
			}
			if(d != ""){
				tx_d = $("#A_d option:selected").text();
			}
			let data = '{"test":[{"nm_0":"'+a+'","nm_1":"'+b+'","nm_2":"'+c+'","nm_3":"'+d+'","nm_4":"'+e+'"},{"tx_0":"'+tx_a+'","tx_1":"'+tx_b+'","tx_2":"'+tx_c+'","tx_3":"'+tx_d+'","tx_4":"'+tx_e+'"}]}';
			
			let sort = "";
			let sort2 = "";
			
			getserchval4(htmltbname,data,sort,pagenumcurrent,htmlpage,sort2);
			loadfeaturedunitA("Gold","loadfeaturedunitA");
			loadfeaturedunitA("Silver","loadfeaturedunitB");

		}



		function getserchval4(htmltbname,searchdata,sort,page,htmlpage,sort2=""){
			loaderon();
			pagenumcurrent = page;
			$("#load_here").load("router.php",{
			      load_table4_htmltbname: htmltbname,
			      load_table4_searchdata: searchdata,
			      load_table4_sort: sort,
			      load_table4_page: page,
			      load_table4_htmlpage: htmlpage,
			      load_table4_sort2: sort2,
			      load_table4_user: ""
			});
		}

		$("#A_find").on("click",function(){
			pagenumcurrent = 1;
			getfind();
		});
	</script>
</body>
</html>