<?php build('content') ?>
<?php
    use Form\AccountForm;
    _forGuestPageOnly();
    
    load(['AccountForm'],FORMS);
    $formAccount = new AccountForm();
    $serviceAccount = new AccountService();
    $sideAdService = new SideAdService();
    //default login form
    $formOnDisplay = request()->input('form') ?? 'sign_in';

    //php acttion section
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

        if(!empty($post['btn_register'])) {
            dump($post);
        }
    }
?>
<main class="">
    <div class="container-fluid" style="height: 100vh;">
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
                            <br>
                            <!-- login welcome -->
                            <div class='d-flex flex-row mb-3'>
                                <img src='<?php echo _path_tmp('main/img/logo100x100.png')?>' class='img-fluid' alt='img-fluid'>
                                <div class='align-self-end'>
                                    <a class='text-dark' href='index.php' style='text-decoration:none'>
                                        <h1 class='display-5 mb-0 pb-0'>Welcome!</h1>
                                        <p class='mb-1 pb-0'>www.philippinerealestatemarket.com</p>
                                    </a>
                                </div>
                            </div>
                            <?php Flash::show()?>
                            <!-- Forgot password -->
                            <div class="showhideform" id="form_forgot" style="display: none;">
                                <p class="mb-1 pb-0">Forgot your password?</p>
                                <div class="form-floating mb-2">
                                    <?php echo $formAccount->getCol('email')?>
                                </div>
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="F_lname" placeholder="Last name">
                                    <label for="F_lname">Last name</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="F_fname" placeholder="First name">
                                    <label for="F_fname">First name</label>
                                </div>
                                <button id="F_butt" type="button" class="btn btn-info bg-col1 border-0 text-white w-100">Reset Password</button>
                                
                                <br><br>
                                <p class="mb-1 pb-0">Already have an account?</p>
                                <button type="button" class="formclick btn btn-info bg-col1 border-0 text-white w-100" data="L">Log In</button>
                                
                                <br><br>
                                <p class="mb-1 pb-0">Not yet registered?</p>
                                <button type="button" class="formclick btn btn-info bg-col1 border-0 text-white w-100 mb-3" data="S">Create an Account</button>
                                <button type="button" onclick="goBack();" class="btn btn-info bg-col1 border-0 text-white w-100" >Go Back</button>
                            </div>

                            <?php
                                switch($formOnDisplay) {
                                    //login
                                    case 'sign_in':
                                        grab('landing/landing_auth_partial/sign_in_form', [
                                            'formAccount' => $formAccount
                                        ]);
                                    break;
                                    //register
                                    case 'sign_up':
                                        grab('landing/landing_auth_partial/sign_up_form', [
                                            'formAccount' => $formAccount
                                        ]);
                                    break;
                                } 
                            ?>
                            <!-- Log In Admin -->
                            <div class="showhideform" id="form_login_admin" style="display: none;">
                                <p class="mb-1 pb-0">Administrators's Login.</p>
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control" id="A_un" placeholder="Username">
                                    <label for="A_un">Username</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <input type="password" class="form-control" id="A_ps" placeholder="Password">
                                    <label for="A_ps">Password</label>
                                </div>
                                <button id="A_butt" type="button" class="btn btn-danger text-white w-100">Admin Log In</button>
                            </div>
                            <br>
                            <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php endbuild()?>

<?php build('scripts') ?>
    <script>
        $(document).ready(function() {
            $('#signInBtn').click(function(){
                $("#formLogin").show();
                $("#formSignIn").hide();
            });

            $('#signUpBtn').click(function(){
                $("#formSignIn").show();
                $("#formLogin").hide();
            });
        });
    </script>
<?php endbuild() ?>
<?php loadTo('_tmp/layout_landing')?>