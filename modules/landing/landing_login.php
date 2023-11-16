<?php
    use Form\AccountForm;
    load(['AccountForm'],FORMS);
    _forGuestPageOnly();
    
    $formAccount = new AccountForm();
    $serviceAccount = new AccountService();
    $sideAdService = new SideAdService();

    if(isSubmitted()) {
        $post = request()->posts();
        if(!empty($post['btn_login'])) {
            $user = $serviceAccount->authenticate($post['email'], $post['password']);
            
            if(!$user) {
                Flash::set(arr_to_str($serviceAccount->getErrors()),'danger');
            } else {
                return redirect('user_dashboard');
            }
        }
    }
?>
<?php build('content')?>
<?php endbuild()?>
    <main>
        <div class="container-fluid">
            <div class="row h-100">
                <div class="col-md-6 p-0 bg-light" style="overflow: hidden;">
                    <div class="h-100 w-100">
                        <div id="car2" class="carousel slide h-100 w-100" data-bs-ride="carousel">
                            <?php
                                $data1 = '';
                                $data = '';
                                $sideAdService->databaseInstance->query(
                                    "SELECT *
                                        FROM c_side_ads
                                        WHERE NOT seenby = 'Members'
                                        AND status = 'on'
                                        ORDER BY RAND()
                                        LIMIT 5;"
                                );
                                foreach($sideAdService->databaseInstance->resultSet() as $key => $row) {
                                    $active = "";
                                    if($key == 0) {
                                        $active = 'active';
                                    }
                            
                                    $path = 'http://uptownritzcondo.com/items/0_side_ads/1637763985387.jpg';
                                    $data1 .= "<li data-bs-target='#car2' data-bs-slide-to='{$key}' class='{$active}'></li>";
                                    $data .= "
                                    <div class='carousel-item {$active} h-100 w-100'>
                                        <img src='$path' class='d-block h-100 w-100 mygrad' alt='Slide {$key}'>
                                        <div class='carousel-caption d-none d-sm-block'>
                                        <h5>{$row['title']}</h5>
                                        <p>{$row['description']}</p>
                                        </div>
                                    </div>
                                    ";
                                }
                            ?>
                            <ol class="carousel-indicators">
                                <?php echo $data1?>
                            </ol>
                            <div class="carousel-inner h-100 w-100">
                                <?php echo $data?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 p-0 bg-transparent">
                    <div class="d-flex align-items-center justify-content-center" style="height: 100vh;">
                        <div class="maxw400 align-self-center m-auto p-2">
                            <!-- login welcome -->
                            <div class='d-flex flex-row mb-3'>
                                <img src='<?php echo _path_tmp('main/img/logo100x100.png')?>' class='img-fluid' alt='img-fluid'>
                                <div class='align-self-end'>
                                    <a class='text-dark' href='<?php echo URL?>' style='text-decoration:none'>
                                        <h1 class='display-5 mb-0 pb-0'>Welcome!</h1>
                                        <p class='mb-1 pb-0'><?php echo URL?></p>
                                    </a>
                                </div>
                            </div>
                            <?php Flash::show()?>
                            <?php
                                grab('landing/landing_auth_partial/sign_in_form', [
                                    'formAccount' => $formAccount
                                ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php loadTo('_tmp/layout_landing')?>