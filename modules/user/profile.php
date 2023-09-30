<?php
    use Form\AccountForm;
    load(['AccountForm'], FORMS);
    _forAuthPageOnly();
    
    $formAccount = new AccountForm();
    $serviceAccount = new AccountService();
    $purhcaseHistoryService = new PurchaseHistoryService();

    $whoIs = whoIs();
    $user = $serviceAccount->single([
        'where' => [
            'recno' => $whoIs['recno']
        ]
    ]);

    $dateToday = nowMilitary();

    $purhcaseHistories = $purhcaseHistoryService->getAll([
        'where' => [
            'usercode' => $whoIs['usercode']
        ]
    ]);

    $formAccount->setValueObject($user);

    if(isSubmitted()) {
        $post = request()->posts();
        if(!empty($post['btn_account_detail'])) {

            $post['membercellno'] = str_to_mobile_number($post['membercellno']);
            $post['memberviberno'] = str_to_mobile_number($post['memberviberno']);

            $isOkay = $serviceAccount->updateAccountDetail($post, $post['recno']);

            if(!$isOkay) {
                Flash::set(implode(',', $serviceAccount->getErrors()), 'danger', 'account_detail_message');
                return request()->return();
            } else {
                Flash::set("Account updated", 'success', 'account_detail_message');
                return redirect(_route('user_profile'));
            }
        }

        if(!empty($post['btn_password'])) {
            $isOkay = $serviceAccount->updatePassword($post, $post['recno']);
            if(!$isOkay) {
                Flash::set(implode(',', $serviceAccount->getErrors()), 'danger', 'account_password_message');
            } else {
                Flash::set('Password updated', 'success', 'account_password_message');
            }
            return redirect(_route('user_profile'));
        }

        if(!empty($post['btn_email'])) {
            $isOkay = $serviceAccount->requestChangeEmail($post['recno'], $post['email']);

            if($isOkay) {
                session_destroy();
                Flash::set(implode(',', $serviceAccount->getMessages()), 'success','account_email_message');
                return redirect(_route('landing_auth'));
            } else {
                Flash::set(implode(',', $serviceAccount->getErrors()), 'danger','account_email_message');
            }

            return redirect(_route('user_profile'));
        }
    }

    $folderName = 'PROFILE_PICTURES';
    $userRecno = $user['recno'];
?>
<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Welcome to your profile</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-col1 text-white">
                            <h4 class="card-title">Account Details</h4>
                        </div>

                        <div class="card-body">
                            <?php Flash::show('account_detail_message')?>
                            <?php echo $formAccount->start()?>
                                <?php echo $formAccount->addAndCall([
                                    'type' => 'hidden',
                                    'name' => 'recno',
                                    'value' => $user['recno']
                                ])?>
                                <div class="form-floating mb-3">
                                    <?php echo $formAccount->getCol('memberfname')?>
                                </div>

                                <div class="form-floating mb-3">
                                    <?php echo $formAccount->getCol('memberlname')?>
                                </div>

                                <div class="form-floating mb-3">
                                    <?php echo $formAccount->getCol('membercellno', [
                                        'required' => false
                                    ])?>
                                </div>

                                <div class="form-floating mb-3">
                                    <?php echo $formAccount->getCol('memberviberno')?>
                                </div>

                                <div class="form-floating mb-3">
                                    <?php echo $formAccount->getCol('memberinfo', [
                                        'required' => false
                                    ])?>
                                </div>

                                <div class="form-floating mb-3">
                                    <?php echo $formAccount->getCol('memberlicense', [
                                        'attributes' => [
                                            'readonly' => true,
                                            'class' => 'form-control',
                                            'id' => 'memberlicense'
                                        ]
                                    ])?>
                                </div>

                                <div class="form-floating mb-3">
                                    <?php echo $formAccount->getCol('memberabout')?>
                                </div>

                            <?php echo wDivider()?>
                            <div class="d-flex flex-row-reverse">
                                <button name="btn_account_detail" type="submit" value="btn_account_detail"
                                    role="button"
                                    class="btn btn-info bg-col1 border-0 m-1 text-white">
                                    <i class="me-2 fa fa-save"></i>Update</button>
                            </div>
                            <?php echo $formAccount->end()?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-col1 text-white">
                            <h4 class="card-title">Profile Picture</h4>
                        </div>

                        <div class="card-body">
                            <?php Flash::show('account_profile_message')?>
                            <div style="text-align:center">
                                <img src="<?php echo $user['profile']?>" alt="" style="width:150px;">
                            </div>
                            <?php echo wDivider(15)?>
                            <form action="#">
                                <input type="file" class="my-pond" name="filepond"/>
                            </form>
                        </div>
                    </div>

                    <?php echo wDivider(20)?>
                    <div class="card">
                        <div class="card-header bg-col1 text-white">
                            <h4 class="card-title">Change Password</h4>
                        </div>

                        <div class="card-body">
                            <?php Flash::show('account_password_message')?>
                            <?php echo $formAccount->start()?>
                                <?php echo $formAccount->addAndCall([
                                    'type' => 'hidden',
                                    'name' => 'recno',
                                    'value' => $user['recno']
                                ])?>
                                <div class="form-floating mb-3">
                                    <input type="password" name="old_password" value="" class="form-control" required="1">
                                    <label class=" col-form-label col-form-label " for="#">
                                        Old Password<span style="color:red;">*</span>
                                    </label>
                                </div>

                                <div class="form-floating mb-3">
                                    <?php echo $formAccount->getCol('password')?>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" name="confirm_password" value="" class="form-control" required="1">
                                    <label class=" col-form-label col-form-label " for="#">
                                        Confirm Password<span style="color:red;">*</span>
                                    </label>
                                </div>
                            <?php echo wDivider()?>

                            <div class="d-flex flex-row-reverse">
                                <button name="btn_password" type="submit" value="btn_password"
                                    role="button"
                                    class="btn btn-info bg-col1 border-0 m-1 text-white">
                                <i class="me-2 fa fa-save"></i>Update</button>

                                <input type="checkbox" class="btn-check" id="C_showpass" autocomplete="">
                                <label class="btn btn-info bg-col1 border-0 m-1 text-white" for="C_showpass"><i class="fa fa-eye"></i></label>
                            </div>
                            <?php echo $formAccount->end()?>
                        </div>
                    </div>
                    <?php echo wDivider(20)?>
                    <div class="card">
                        <div class="card-header bg-col1 text-white">
                            <h4 class="card-title">Change Email Address</h4>
                        </div>

                        <div class="card-body">
                            <?php Flash::show('account_email_message')?>
                            <?php echo $formAccount->start()?>
                                <?php echo $formAccount->addAndCall([
                                    'type' => 'hidden',
                                    'name' => 'recno',
                                    'value' => $user['recno']
                                ])?>
                                <div class="form-floating mb-3">
                                    <?php  echo $formAccount->getCol('email', [
                                        'options' => [
                                            'label' => 'New Email'
                                        ],
                                        'value' => $user['email']
                                    ]);?>
                                </div>

                                <div class="form-floating mb-3">
                                    <?php echo $formAccount->getCol('password')?>
                                </div>
                            <?php echo wDivider()?>
                            <div class="d-flex flex-row-reverse">
                                <button name="btn_email" type="submit" value="btn_email"
                                    role="button"
                                    class="btn btn-info bg-col1 border-0 m-1 text-white">
                                    <i class="me-2 fa fa-save"></i>Update</button>
                            </div>
                            <?php echo $formAccount->end()?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php echo wDivider()?>
    <div class="card">
        <div class="card-header">
            <h4 class="ccard-title">Stars History</h4>
        </div>

        <div class="card-body">
            <?php if(empty($purhcaseHistories)) :?>
                <p class="text-center">No star history found</p>
            <?php else:?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <th>Purchased Items</th>
                        <th>Date Purchased</th>
                        <th>Date End</th>
                        <th>Days left</th>
                    </thead>

                    <tbody>
                        <?php foreach($purhcaseHistories as $key => $row) :?>
                            <?php
                                $dateDifference = date_difference_in_days($dateToday, $row['effective_date_end']);    
                            ?>
                            <tr>
                                <td><?php echo $row['memtype_or_star']?></td>
                                <td><?php echo $row['effective_date_start']?></td>
                                <td><?php echo $row['effective_date_end']?></td>
                                <td><?php echo $dateDifference?></td>
                            </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
            </div>
            <?php endif?>
        </div>
    </div>
<?php endbuild()?>

<?php build('headers')?>
	<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
	<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet"/>
<?php endbuild()?>

<?php build('scripts') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
    <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            var memberInfo = $('#memberinfo');
            toggleMemberLicense(memberInfo.val());

            $(memberInfo).change(function(){
                let value = $(this).val();
                toggleMemberLicense(value);
            });

            $("#C_showpass").on("click",function(){
                let dis = $(this).is(":checked");
                if(dis === true){
                    $("#password").attr("type","text");
                }else{
                    $("#password").attr("type","password");
                }
            });

            $.fn.filepond.registerPlugin(FilePondPluginImagePreview);
            $.fn.filepond.setDefaults({
                server: {
                    url : '<?php echo URL?>/',
                    process : {
                        url : 'api/profileupload.php?imageFolder=<?php echo $folderName?>&userRecno=<?php echo $userRecno?>',
                        method : 'post',
                        onload : function(response) {
                            let responseData = JSON.parse(response);
                            if(responseData) {
                                if(responseData.status == 'success') {
                                    location.reload();
                                }
                            }
                        }
                    }
                },
                allowMultiple : true
            });
            // Turn input element into a pond with configuration options
            $('.my-pond').filepond();

            $('.my-pond').on('FilePond:addfile', function(e){
                // $("#btn_create_prop").hide();
            });

            function toggleMemberLicense(memberInfoValue) {
                if(memberInfoValue == 'broker') {
                    $("#memberlicense").removeAttr('readonly');
                } else {
                    $("#memberlicense").prop('readonly', 1);
                }
            }
        });
    </script>
<?php endbuild ()?>
<?php loadTo()?>