<?php
    use Form\InquiryForm;
    use Form\AdsForm;

    load(['InquiryForm', 'AdsForm'],FORMS);

    $req = request()->inputs();
    $adDetailService = new ListingService();
    $accountService = new AccountService();
    $clientService = new ClientService();
    $inquiryForm = new InquiryForm();
    $adsForm = new AdsForm();
    $isInquiryFormSubmitted = false;
    $adService = new AdService();
    
    if(isSubmitted()) {
        $isInquiryFormSubmitted = true;
        $post = request()->posts();
        //append the following to the post
        $response = $clientService->saveNewInquiry($post);
    }
    $adId = unseal($req['adId']);

    $adDetail = $adService->single([
        'where' => [
            'ads.recno' => $adId
        ]
    ]);

    $imageFolder = $adDetail['module_folder_name'];

    if(!empty($imageFolder)) {
        $fileImages = filter_files_only(scandir("public/uploads/images/{$imageFolder}"));
    } else {
        $fileImages = [];
    }
    
    $account = $accountService->single([
        'where' => [
            'usercode' => $adDetail['usercode']
        ]
    ]);

    $inquiryForm->addAgentCode($adDetail['usercode']);
    $inquiryForm->addFKAdsKey($adDetail['recno']);

    $sharelink = request()->url();

    $sharebut = '
	    <div id="fb-root"></div>
		<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
		fjs.parentNode.insertBefore(js, fjs);
		}(document, "script", "facebook-jssdk"));</script>

		<div class="fb-share-button" 
			data-href="'.$sharelink.'" 
			data-layout="button_count"
			data-size="large">
		</div>
	';
    $sharebut2 = "
        <script async src='https://platform.twitter.com/widgets.js' charset='utf-8'></script>
        <a href='https://twitter.com/intent/tweet?text=https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."' class='twitter-follow-button btn btn-sm fb-bg-color-butt px-2' style='padding: 2px;' data-show-count='false' data-size='large' target='_blank'>
            <i class='fab fa-twitter'></i> Tweet
        </a>
    ";
?>
<?php build('content')?>
<?php echo topnav_tag()?>
<div class="offsettop"></div>
<div class="maxw1080 m-auto"></div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <div class="card mb-4" style="display:block">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="align-self-center">
                            <div class="d-flex">
                                <?php if(!empty($account['profile'])) :?>
                                    <img src="<?php echo $account['profile']?>" 
                                        class="align-self-center rounded-circle"
                                        alt="" width="50" height="50">
                                <?php else:?>
                                    <div class="d-flex justify-content-center bg-col2 rounded-circle text-white m-auto" 
                                    style="width: 50px; height: 50px;">
                                        <i class="align-self-center fa fa-2x fa-user-tie"></i>
                                    </div>
                                <?php endif?>

                                <div class="align-self-center p-2 flex-grow-1">
                                    <span class="fs-5">Posted by <?php echo ucwords("{$account['memberfname']} {$account['memberlname']}");?></span><br>
                                    <small class="text-muted lh-1"><?php echo time_since($adDetail['dateinserted']);?></small>
                                </div>
                            </div>
                        </div>
                        <div class="align-self-start" style="display: show;">
                            <div class="d-flex justify-content-end">
                                <div class="m-1"><?php echo $sharebut2;?></div>
                                <div class="m-1"><?php echo $sharebut;?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row px-4">
                    <div class="col-sm-6">
                        <div class="card-body">
                            <div class="">
                                <span class="h5"><?php echo ucwords($adDetail['adstitle']);?></span>
                            </div>
                            <p>
                                    <?php echo ucwords($adDetail['adsdesc']);?>
                            </p>
                            <div class="">
                                <span class="h5"><?php echo $adDetail['listtypedesc'];?></span><br>
                                <span class="fs-2 text-muted"><?php echo amountHTML($adDetail['price']);?></span>
                            </div>
                            <p>
                                <?php echo ucwords($adDetail['listingdescription']);?>
                            </p>
                            <ul class="list-group list-group-flush">
                                <?php
                                    $propInfo = [
                                        'loccitycode' => 'Location',
                                        'buildingname' => 'Building Name',
                                        'propaddress' => 'Address',
                                        'securitydeposit' => 'Deposit',
                                        'mincontract' => 'Minimun Contract',
                                        'paymentterm' => 'Payment Terms',
                                    ];

                                    foreach(array_keys($propInfo) as $propKey) {
                                        if(!empty($adDetail[$propKey])) {
                                            $textValue = $adDetail[$propKey];
                                            if(is_numeric($textValue)) {
                                                $textValue = amountHTML($textValue);
                                            }
                                            echo "<li class='list-group-item p-1'>{$propInfo[$propKey]} : {$textValue}</li>";
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                        <?php
                            if(whoIs())  {
                                echo '<div>';
                                echo wButton(_route('prop_show', [
                                    'recno' => seal($adDetail['listing_recno'])
                                ]), 'Overview', 'primary');
                                echo '</div>';
                            }
                        ?>
                    </div>
                    <div class="col-sm-6">
                            <div id="carouselWithIndicators" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <?php foreach($fileImages as $key => $file) :?>
                                        <div class='carousel-item <?php echo $key == 0 ? 'active' : ''?>'>
                                            <img src='public/uploads/images/<?php echo "{$imageFolder}/{$file}"?>' 
                                                class='d-block w-100' alt='$adstitle'
                                                style="width: 150px;">
                                        </div>
                                    <?php endforeach?>
                                </div>
                                <a class="carousel-control-prev" href="#carouselWithIndicators" role="button" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselWithIndicators" role="button" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </div>
                            
                            <?php if(!whoIs()) :?>
                            <div class="card mt-2">
                                <div class="card-body p-2">
                                    <div class="accordion accordion-flush" id="accordionFlush">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingOne">
                                                <button class="accordion-button collapsed py-1 pt-2" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                                    <h5><i class="me-3 fa fa-phone"></i>Contact Agent</h5>
                                                </button>
                                            </h2>
                                            <div id="flush-collapseOne" class="accordion-collapse collapse hide" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlush">
                                                
                                                <?php if(!$isInquiryFormSubmitted) :?>
                                                    <div class="accordion-body p-0">
                                                        <?php
                                                            echo $inquiryForm->start();
                                                            echo $inquiryForm->get('agentcode');
                                                            echo $inquiryForm->get('fk_ads_key');
                                                        ?>
                                                        <div id="agentform" style="display: ;">
                                                            <p class="mt-2">Fill in the form to view agent's contact number...</p>
                                                            <div>
                                                                <div class="form-floating mb-3">
                                                                    <?php echo $inquiryForm->getCol('clientfname')?>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <?php echo $inquiryForm->getCol('clientlname')?>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <?php echo $inquiryForm->getCol('clientemail')?>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <?php echo $inquiryForm->getCol('clientcellno')?>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <?php echo $inquiryForm->getCol('clientremarks')?>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <?php echo $inquiryForm->getCol('clientmsg')?>
                                                                </div>
                                                                <div class="d-flex flex-row-reverse">
                                                                    <button role="submit" id="A_butt" type="submit" class="btn btn-info bg-col1 border-0 text-white">
                                                                        <i class="fa fa-comment-alt me-2"></i>Send
                                                                    </button>
                                                                </div>
                                                                <br>
                                                            </div>
                                                        </div>
                                                        <?php echo $inquiryForm->end()?>
                                                    </div>
                                                <?php endif?>

                                                <?php if($isInquiryFormSubmitted) :?>
                                                <div id="agencontact" class="alert alert-warning m-2 mt-3" role="alert">
                                                    Please call the agent for fast transaction...
                                                    <br>
                                                    Agent: <strong><?php echo $account['memberfname'];?></strong><br>
                                                    Contact: <span class="lead"><?php echo $account['memberviberno'];?></span>
                                                </div>
                                                <?php endif?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif?>
                    </div>
                </div>
                <br><br>
                <?php if(!whoIs()) :?>
                    <div class="card-body d-flex justify-content-center">
                        <button onclick="location.href='<?php echo _route('landing_index')?>';" 
                        type="button" class="btn btn-info bg-col1 border-0 text-white">Look for more</button>
                    </div>
                <?php endif?>
                <br><br>
            </div>
        </div>
        <?php echo sideadsfooter_tag();?>
    </div>
</div>
<?php endbuild()?>


<?php loadTo('_tmp/layout_landing')?>