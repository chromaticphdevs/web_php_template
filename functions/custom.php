<?php 
use Core\Database;

function _forGuestPageOnly($href = '') {
    if(!empty(whoIs())) {
        if(empty($href)) {
            $href = _route('user_dashboard');
        }
        Flash::set("Invalid Page", 'warning');
        return redirect($href);
    }
}

function _forAuthPageOnly($href = '', $userType = []) {
    if(empty(whoIs())) {
        if(empty($href)) {
            $href = _route('landing_login');
        }
        Flash::set("Invalid Page", 'warning');
        return redirect($href);
    }
}


function html_ads_star_link($star, $status, $href = 'javascript:void(0)', $moduleID = '') {
    $btnType = '';
    $txtColor = '';

    if($star) {
        if(isEqual($status, 'off')) {
            $btnType = 'btn-danger';
        } else {
            $btnType = 'btn-success';
        }
    } else {
        $btnType = 'btn-outline-secondary';
    }
    echo wLinkDefault('javascript:void(0)', '',[
        'type' => 'button',
        'class' => "btn btn-sm {$btnType} rounded-circle mb-3 ad-start-action",
        'data-bs-toggle' => 'tooltup',
        'data-bs-placement' => 'top',
        'data-action' => 'toggle_star',
        'data-moduleid' => $moduleID,
        'title' => '',
        'data-bs-original-title' => 'Assign stars to this ads',
        'icon' => 'fa fa-star',
    ]);
}
/**
 * clearlink route should be the route function
 */
function html_breadcrumbs_basic($filterArray = [], $clearLinkRoute) {
    if(!empty($filterArray)) {
        echo "<div> Filter : ";
        echo implode(', ', array_values($filterArray));
        echo wDivider(5);
        echo wLinkDefault($clearLinkRoute, 'Clear Filter');
        echo "</div>";
    }
}

function filter_files_only($directoryItems) {
    $retVal = [];
    foreach($directoryItems as $key => $row) {
        if(isEqual($row, ['.', '..'])) {
            continue;
        }
        $retVal[] = $row;
    }
    return $retVal;
}

function head_tag($mainpath){
    $curr = time();
    
    $out = <<< EOFILE
        

        <script src="{$mainpath}js/jquery-3.6.0.min.js"></script>
        <script src="{$mainpath}js/bootstrap.bundle.min.js"></script>
        <script src="{$mainpath}js/popper.min.js"></script>
        <script src="{$mainpath}js/all.min.js"></script>
        <script src="{$mainpath}dropzone/dropzone.min.js"></script>
        <script src="{$mainpath}myjs.js?ver=$curr"></script>
    EOFILE;
    return $out;
}

function loader_tag(){
    $out = '';
    //remove after js are all working well
    if(false) {
        $out = <<< EOFILE
            <div id="loader" class="full_out">
                <div class="full_in">
                <div id="spin" class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                </div>
            </div>
            <div id="cover" class="full_out"></div>
        EOFILE;   
    }
    return $out;
}


function sideadsindex_tag2($divid){
    load(['Database'], CORE);
    $retVal = '';

    $database = Database::getInstance();

    $database->query(
        "SELECT *
        FROM c_side_ads
        WHERE NOT seenby = 'Members'
        AND status = 'on'
        ORDER BY RAND()
        LIMIT 1"
    );
    $row = $database->single();

    if($row) {
        $timeSince = time_since($row['dateinserted']);
        $path = 'http://uptownritzcondo.com/items/0_side_ads/1637322152182.jpg';
        $retVal .= <<<EOF
            <a href= '{$row['ads_url']}' style='text-decoration:none;color:#000'>
                <div class='card my-2'>
                    <div class='row g-0'>
                        <div class='col-sm-4'>
                            <img src='$path' class='img-fluid w-100' alt='card-horizontal-image' alt='Slide' style=''>
                        </div>
                        <div class='col-sm-8'>
                        <div class='card-body'>
                            <h5 class='card-title'>{$row['title']}</h5>
                            <p class='card-text'>{$row['description']}</p>
                            <p class='card-text'><small class='text-muted'>Last updated {$timeSince}</small></p>
                        </div>
                        </div>
                    </div>
                </div>
            </a>
          EOF;
          
          $out = <<< EOFILE
                <div id="$divid" class="">
                    $retVal
                </div>
          EOFILE;
          return $out;   
    }
    }
    
function sideadsfooter_tag(){
    $serviceSideAd = new SideAdService();
    $data = "";
    $data1 = "";

    $serviceSideAd->databaseInstance->query(
        "SELECT *
        FROM c_side_ads
        WHERE NOT seenby = 'Non-Members'
        AND status = 'on'
        ORDER BY RAND()
        LIMIT 5;"
    );

    $rows = $serviceSideAd->databaseInstance->resultSet();
    $weblinks = uiWebLinks();
    $portalowner = uiPortalOwner();
    $conatctus = uiContact();
    $copyright = uiCopyRight();

    if(is_array($rows)){
        foreach($rows as $key => $row) {
            $active = "";
            if($key == 0){
                $active = "active";
            }
            
            $timeSince = $row['dateinserted'];
            $path = _path_public("/uploads/images/SIDE_ADS/{$row['filename']}.jpg");
            
            $data1 .= "<li data-bs-target='#car3' data-bs-slide-to='$' class='bg-secondary $active'></li>";
            
            $url = "class='card border-0'";
            if(!empty($row['ads_url'])){
              $url = "onclick='loadMyURL(`{$row['ads_url']}`,`blank`)' class='card border-0 mypointer'";
            }
      
            $data .= "
              <div class='carousel-item $active'>
                  <div $url>
                    <img src='$path' class='card-img-top' alt='card-img-top'>
                    <div class='card-body'>
                      <h5 class='card-title'>{$row['title']}</h5>
                      <p class='card-text'>
                        {$row['description']}
                      </p>
                      <small class='text-muted'>
                        <i class='fa fa-comment me-2'></i>Posted {$timeSince}
                      </small>
                    </div>
                  </div>
                  <br><br>
                </div>
            ";
        }

        $out = <<< EOFILE
        <!-- Ads -->
        <div class="col-lg-3">
            <div class="border bg-white p-2">
                <!-- Ads side carousel -->
            <div id="car3" class="carousel slide border mb-2" data-bs-ride="carousel">
                <ol class="carousel-indicators">
                $data1
                </ol>
                <div class="carousel-inner">
                $data
                </div>
            </div>
                <!-- Web Links -->
                $weblinks
                <!-- Portal Owner -->
                $portalowner
                <!-- Contact Us -->
                $conatctus
            </div>
        <!-- Copyright -->
            $copyright
            <br><br><br>
        </div>
    EOFILE;
    return $out;
    }
}


function topnav_tag(){
    $whoIs = whoIs();
    $data = "";
    if($whoIs){
      $memberlink = $whoIs['memberlink'];
      $dislink = URL."?".$memberlink;
      if($memberlink == ""){
        $buttonlink = "";
      }else{
        $buttonlink = " <button type='button' onclick='loadMyURL(`$dislink`,`blank`);' class='btn btn-outline-info border-0 mx-1 text-white'>
                          <i class='fa fa-globe'></i><span class='ms-2 d-none d-md-inline'>My WebLink</span>
                        </button>";
      }
    
    $data = <<< EOFILE
            <div class="p-0 align-self-center">
              <button onclick="location.href=`user_dashboard`;" type="button" class="btn btn-outline-info border-0 mx-1 text-white">
                <i class="fa fa-home"></i><span class="ms-2 d-none d-md-inline">Dashboard</span>
              </button>
            </div>
            <div class="p-0 align-self-center">
              $buttonlink
            </div>
            <div class="p-0 align-self-center">
                <div class="dropdown">
                  
                  <button class="btn btn-outline-info border-0 mx-1 dropdown-toggle text-white" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-user"></i><span class="ms-2 d-none d-md-inline">{$whoIs['memberfname']} {$whoIs['memberlname']}</span>
                  </button>
                  
                  <ul class="dropdown-menu shadow" aria-labelledby="dropdownMenuButton">
                    
                    
                    <li><a class="dropdown-item" href="user_profile"><i class="miniiconsize me-2 fa fa-user"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="prop_create"><i class="miniiconsize me-2 fa fa-clipboard-list"></i>Listing</a></li>
                    <li><a class="dropdown-item" href="ads_create"><i class="miniiconsize me-2 fa fa-newspaper"></i>Ads</a></li>
                    <li><a class="dropdown-item" href="inq_create"><i class="miniiconsize me-2 fa fa-question-circle"></i>Inquiries</a></li>
                    <li><a class="dropdown-item" href="user_logout"><i class="miniiconsize me-2 fa fa-times"></i>Log Out</a></li>
                  </ul>
                </div>
            </div>
    EOFILE;
    }else{
    $data = <<< EOFILE
            <div class="p-1 align-self-center">
              <button onclick="location.href=`landing_index`;" type="button" class="btn btn-info bg-col1 border-0 text-white" style="height: 44px;">
                <i class="fa fa-sign-in-alt"></i><span class="ms-2 d-none d-md-inline">List your property</span>
              </button>
            </div>
    EOFILE;
    }
    
    $companynameHTML = COMPANY_NAME;
    $linknameHTML = URL;
    $logoIMG = _path_tmp('main/img/logo100x100.png');
    
    $out = <<< EOFILE
        <!-- Top Nav -->
        <nav class="fixed-top navbar-light bg-col1">
            <div class="container-fluid py-2">
                <div class="d-flex">
                    <div class="p-0 align-self-center">
              <a class="" href="index.php" style="text-decoration:none">
                         <img src="$logoIMG" class="img-fluid rounded-circle bg-white" alt="img-fluid" width="50" height="50">
              </a>
                    </div>
                    <div class="p-0 align-self-center flex-grow-1">
              <a class="" href="index.php" style="text-decoration:none">
                         <span class="ms-2 fs-5 text-truncate text-white">$companynameHTML</span>
              </a>
                    </div>
            $data
                </div>
            </div>
        </nav>
    EOFILE;
    return $out;
}

function privatemessage_tag(){
    $out = <<< EOFILE
        <!-- Modal Privatemessage -->
        <div class="modal fade" id="privatemsg" tabindex="-1" aria-labelledby="privatemsglbl" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="privatemsglbl">Private Message</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Message" id="floatingTextarea" style="height: 200px"></textarea>
                            <label for="floatingTextarea">Message</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info text-white" data-bs-dismiss="modal">
                            <i class="fa fa-times me-2"></i>Cancel
                        </button>
                        <button type="button" class="btn btn-info text-white">
                            <i class="fa fa-comment-alt me-2"></i>Send
                        </button>
                    </div>
                </div>
            </div>
        </div>
    EOFILE;
    return $out;
}

function uiWebLinks() {
    return <<<EOFILE
        <div class='card border-0'>
            <div class='card-body'>
                <p class='text-muted'>
                    <span class='lead fs-3 text-dark'><i class='fa fa-globe'></i> Links</span><br>
                    <span><a class='text-secondary linknoline' href='http://www.eastwoodcondo.com'>www.eastwoodcondo.com</a></span><br>
                    <span><a class='text-secondary linknoline' href='http://www.globalcitycondo.com'>www.globalcitycondo.com</a></span><br>
                    <span><a class='text-secondary linknoline' href='http://www.mckinleyhillcondo.com'>www.mckinleyhillcondo.com</a></span><br>
                    <span><a class='text-secondary linknoline' href='http://www.rockwellleasing.com'>www.rockwellleasing.com</a></span><br>
                    <span><a class='text-secondary linknoline' href='http://www.eastwoodcitycondo.com'>www.eastwoodcitycondo.com</a></span><br>
                </p>
            </div>
        </div>
    EOFILE;
}

function uiPortalOwner() {
    return <<<EOFILE
        <div class='card border-0'>
            <div class='card-body'>
                <p class='text-muted'>
                    <span class='lead fs-3 text-dark'><i class='fa fa-mobile'></i> Portal Owner</span><br>
                    Christine Chan<br>
                    Real Estate Broker PRC Lic No. 12044<br>
                    <br>
                </p>
            </div>
        </div>
    EOFILE;
}

function uiContact() {
    return <<<EOFILE
    <div class='card border-0'>
        <div class='card-body'>
                <p class='text-muted'>
                <span class='lead fs-3 text-dark'><i class='fa fa-phone'></i> 
                <span><a class='text-secondary linknoline' href='tel:0920-520-2222'>Contact Us</a></span></span><br>						            	
                <span><a class='text-secondary linknoline' href='tel:0920-520-2222'>0920-520-2222</a></span><br>
                    <span><a class='text-secondary linknoline' href='tel:0917-750-1004'>0917-750-1004</a></span><br>
                    <span><a class='text-secondary linknoline' href='tel:0920-880-9888'>0920-880-9888</a></span><br>
                <span><a class='text-secondary linknoline' href='tel:0909-900-2222'>0909-900-2222</a></span><br>
                <span><a class='text-secondary linknoline' href='tel:0906-543-0022'>0906-543-0022</a></span><br>
                <span>Viber: <a class='text-secondary linknoline' href='tel:0920-520-2222'>0920-520-2222</a></span><br>
                <span>Email: <a class='text-secondary linknoline' href='mailto:inquire@philippinerealestatemarket.com'>inquire@philippinerealestatemarket.com</a></span><br>
            </p>
        </div>
    </div>	
    EOFILE;
}

function uiCopyRight() {
    return <<<EOFILE
        <div class='mt-5 text-center'>
            <p class='text-muted fs-6'>
                All rights reserved 2022 ©<br>
                www.philippinerealestatemarket.com
            </p>
        </div>
    EOFILE;
}