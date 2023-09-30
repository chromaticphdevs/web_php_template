<?php build('content') ?>
<?php
	_forAuthPageOnly();
    $serviceListing = new ListingService();
    $serviceAds = new AdService();
    $serviceClient = new ClientService();
	$serviceNews = new NewsService();
	$serviceAccount = new AccountService();

    $whoIsUserCode = whoIs('usercode');
    
    $listingcount = $serviceListing->count([
        'where' => [
            'usercode' => $whoIsUserCode
        ]
    ]);

    $adscount = $serviceAds->count([
        'where' => [
            'adscode' => $whoIsUserCode,
            'status' => 'on'
        ]
    ]);

    $adscountoff = $serviceAds->count([
        'where' => [
            'adscode' => $whoIsUserCode,
            'status' => 'off'
        ]
    ]);

	$starCount = $serviceAccount->single([
		'where' => [
			'recno' => whoIs('recno')
		]
	])->star_id ?? 0;

    $inquirymsg = $serviceClient->count([
        'where' => [
            'agentcode' => $whoIsUserCode,
            'showhide' => 'show'
        ]
    ]);

    $inquirymsgoff = $serviceClient->count([
        'where' => [
            'agentcode' => $whoIsUserCode,
            'showhide' => 'hide'
        ]
    ]);

	$news = $serviceNews->getAll();

	$ID_picture = !empty(whoIs('profile')) ? whoIs('profile') : DEFAULT_PROFILE_IMAGE;
?>
<body class="backcolor">
	<!-- Main -->
	<div class="card mb-4">
		<div id="" class="card-body">
			<?php Flash::show()?>
			<div class="row m-2">
				<div class="col-lg-4 mb-3 p-1">
					<!-- Profile -->
					<div onclick="loadMyURL('<?php echo _route('user_profile')?>')" class="bg-col1 text-white rounded h-100 mypointer">
						<div class="row g-0">
							<div class="col-4 text-center p-4">
							<img src="<?php echo $ID_picture;?>" class="img-fluid rounded-circle" alt="id" style="background-color: #ffff;"><br>
							<small class=""><b><i class="fa fa-gem"></i><?php echo whoIs('membertype');?></b></small>
							</div>
							<div class="col-8">
							<div class="py-4">
								<span class="text-truncate fs-3 p-0 m-0"><?php echo whoIs('memberfname');?></span><br>
								<small class="text-truncate p-0 m-0"><?php echo whoIs('email');?></small><br>
								<small class="text-truncate p-0 m-0">Phone: <?php whoIs('memberviber');?></small>
								<small class="text-truncate p-0 m-0">Viber: <?php whoIs('memberviber');?></small>
							</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-2 mb-3 p-1">
					<!-- Star -->
					<div class="bg-col1 text-white rounded h-100 text-center p-4 mypointer">
						<i class="fa fa-3x fa-star"></i><br>
						<small class="">Star</small><br>
						<span><b><?php echo $starCount;?></b></span>
					</div>
				</div>
				<div class="col-lg-2 mb-3 p-1">
					<!-- Listing -->
					<div onclick="loadMyURL('<?php echo _route('prop_create')?>')" class="bg-col1 text-white rounded h-100 text-center p-4 mypointer">
						<i class="fa fa-3x fa-clipboard-list"></i><br>
						<small class="">Listing</small><br>
						<span><b><?php echo $listingcount;?></b></span>
					</div>
				</div>
				<div class="col-lg-2 mb-3 p-1">
					<div onclick="loadMyURL('<?php echo _route('ads_create')?>')" class="bg-col1 text-white rounded h-100 text-center p-4 mypointer">
						<i class="fa fa-3x fa-newspaper"></i><br>
						<small class="">Ads</small><br>
						<span>On: <b><?php echo $adscount;?></b> Off: <b><?php echo $adscountoff;?></b></span>
					</div>
				</div>
				<div class="col-lg-2 mb-3 p-1">
					<div onclick="loadMyURL('<?php echo _route('inq_create')?>')" class="bg-col1 text-white rounded h-100 text-center p-4 mypointer">
						<i class="fa fa-3x question-circle"></i><br>
						<small class="">Inquiries</small><br>
						<span>New: <b><?php echo $inquirymsg;?></b> Hide: <b><?php echo $inquirymsgoff;?></b></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Feeds -->
	<div class="card mb-4">
		<div class="card-body p-4">
			<h4>Announcements</h4>
			<?php if(!empty($news)) :?>
				<?php foreach($news as $key => $row) :?>
					<div class="box">
						<h4><?php echo $row['title']?></h4>
						<div><?php echo $row['description']?></div>
						<div>Administrator posted <?php echo time_since($row['dateinserted'])?></div>
					</div>
				<?php endforeach?>
			<?php else :?>
				<p class="text-center"> nothing to show.. </p>
			<?php endif?>
		</div>
	</div>

	<?php echo privatemessage_tag();?>
</body>
<?php endbuild()?>
	<?php build('scripts')?>
	<script type="text/javascript">
		function loadMyURL(url) {
			window.location.href = url;
		}
	</script>
	<?php endbuild()?>
<?php loadTo()?>