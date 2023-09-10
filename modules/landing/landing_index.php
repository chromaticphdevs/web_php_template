
<?php build('content') ?> 
<?php
	use Form\ListingForm;
	load(['ListingForm'], FORMS);
    load(['Form'], HELPERS);
	global $_formCommon;

    $req = request()->inputs();
    $adService = new AdService();

	$listingForm = new ListingForm();
    $listingService = new ListingService();

	$_formCommon->setOptionValues('sort',[
		'proptypecode' => 'Property Type',
		'propclasscode' => 'Property Class',
		'loccitycode' => 'Location'
	]);

    if(!empty($req['filter'])) {
        $filter = array_filter([
            'listing.listingtag' => $req['listingtag'],
            'listing.proptypecode' => $req['proptypecode'],
            'listing.propclasscode' => $req['propclasscode'],
            'listing.loccitycode' => $req['loccitycode']
        ]);
        
        $condition = [];

        if(!empty($filter)) {
            $condition['GROUP_CONDITION'] = $listingService->conditionConvert($filter);
        }

        if(!empty($req['search_key'])) {
            $keySearch = [
                'listing.buildingname' => [
                    'condition' => 'like',
                    'value' => "%{$req['search_key']}%",
                    'concatinator' => ' OR '
                ],
                'listing.listingdescription' => [
                    'condition' => 'like',
                    'value' => "%{$req['search_key']}%",
                    'concatinator' => ' OR '
                ],
                'listing.listingcode' => [
                    'condition' => 'like',
                    'value' => "%{$req['search_key']}%",
                    'concatinator' => ' AND '
                ],
                'ads.status' => 'on'
            ];
            $condition = array_merge($condition, $keySearch);
        }

        $listings = $adService->getAll([
            'where' => $condition
        ]);
    } else {
        $listings = $adService->getAll([
            'where' => [
                'ads.status' => 'on'
            ]
        ]);
    }
?>
<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-white shadow">
    <div class="container-fluid">
        <a class="navbar-brand m-0 w-50" href="index.php">
            <img src="<?php echo _path_tmp('main/img/logo100x100.png')?>" class="img-fluid p-0" alt="img-fluid" width="50" height="50">
            <span class="fonttitle"><?php echo COMPANY_NAME;?></span>
        </a>
        <button class="navbar-toggler border-0 me-0" type="button" data-bs-toggle="collapse" data-bs-target="#nav1" aria-controls="nav1" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse hide flex-md-row-reverse" id="nav1">
            <?php 
                Form::open([
                    'method' => 'get'
                ]);
                Form::hidden('filter', 'listing_filter');
            ?>
            <div class="navbar-nav">
                    <div class="nav-link px-1">
                        <div class="form-floating">
                            <?php echo $listingForm->getCol('listingtag', [
                                'required' => false
                            ]);?>
                        </div>
                    </div>
                    <div class="nav-link px-1">
                        <div class="form-floating">
                            <?php echo $listingForm->getCol('proptypecode', [
                                'required' => false
                            ]);?>
                        </div>
                    </div>
                    <div class="nav-link px-1">
                        <div class="form-floating">
                            <?php echo $listingForm->getCol('propclasscode', [
                                'required' => false
                            ]);?>
                        </div>
                    </div>
                    <div class="nav-link px-1">
                        <div class="form-floating">
                            <?php echo $listingForm->getCol('loccitycode', [
                                'required' => false
                            ]);?>
                        </div>
                    </div>
                    <div class="nav-link px-1">
                        <div class="form-floating">
                            <?php
                                Form::text('search_key','', [
                                    'class' => 'form-control Wsmall',
                                    'placeholder' => 'Search',
                                    'id' => 'searchKey'
                                ])
                            ?>
                            <label for="searchKey">Search Key</label>
                        </div>
                    </div>
                    <div class="btn-group my-2" role="group" aria-label="Large button group" style="height: 58px;">
                        <button id="A_find" type="submit" role="button" class="btn btn-warning" style="max-width: 100px;">
                            <i class="fa fa-search"></i>
                        </button>
                        <button onclick="location.href=`landing_auth`;" type="button" class="btn btn-info bg-col1 border-0 text-white">List your property</button>
                    </div>
            </div>
            <?php Form::close()?>
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
                <div>
                    <?php
                        if(!empty($filter)) {
                            echo implode(', ', array_values($filter));
                            echo wDivider(5);
                            echo wLinkDefault(_route('landing_index'), 'Clear Filter');
                        }
                    ?>
                </div>
                <?php if(empty($listings)) :?>
                    <p class="mt-5 text-center">There are no listings found</p>
                <?php else:?>
                <?php foreach($listings as $key => $listing) :?>
                    <?php
                        $imageFolder = $listing['module_folder_name']; 
                        if(!empty($imageFolder)) {
                            $listingImages = filter_files_only(scandir("public/uploads/images/{$imageFolder}"));
                        } else {
                            $listingImages = [];
                        }
                    ?>
                    <div data-href = '<?php echo _route('prop_detail', null, [
                        'adId' => seal($listing['recno'])
                    ])?>' class='card max300W dispoint property' style="display: inline-block;">
                        <img src='public/uploads/images/<?php echo $imageFolder?>/<?php echo $listingImages[0] ?? ''?>' class='card-img-top rounded imgbox2'>
                        <div class='position-absolute bottom-0 start-50 translate-middle-x text-white w-100 
                            bg-dark bg-opacity-50 rounded-bottom p-2'>
                            <div class='text-end'><?php echo $listing['listingcode']?></div>
                            <div class='text-end'><?php echo $listing['proptypecode']?><?php echo $listing['buildingname']?></div>
                            <div class='text-end h4 m-0 p-0 text-truncate fontprice'><?php echo  $listing['price']?></div>
                            <div class='text-end text-truncate my-0 py-0'>
                                <i class='fa fa-map-marker-alt me-2'></i>
                                <?php echo $listing['loccitycode']?>
                            </div>
                        </div>
                    </div>
                <?php endforeach?>
                <?php endif?>
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
<?php endbuild()?>

<?php build('scripts') ?>
    <script>
        $(function(){

            $('div.property').on('click', function(){
                let href = $(this).data('href');

                console.log(href);
                window.location.href = href;
            });
        });
    </script>
<?php endbuild()?>

<?php loadTo('_tmp/layout_landing')?>