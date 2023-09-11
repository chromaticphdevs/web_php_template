<?php extract($data) ?>
<form action="" method="post">
    <div id="formSignUp">
        <p class="mb-1 pb-0">Create an account.</p>
        <div class="form-floating mb-2">
            <?php echo $formAccount->getCol('email')?>
        </div>
        <div class="form-floating mb-2">
            <?php echo $formAccount->getCol('memberlname')?>
        </div>
        <div class="form-floating mb-2">
        <?php echo $formAccount->getCol('memberfname')?>
        </div>
        <div class="form-floating mb-2">
            <?php echo $formAccount->getCol('password')?>
        </div>
        <div class="form-floating mb-2">
            <input name="confirm_password" type="password" class="form-control" id="S_repass" placeholder="Re Enter Password">
            <label for="S_repass">Re Enter Password</label>
        </div>
        <button name="btn_register"  type="submit" role="button" 
            class="btn btn-info bg-col1 border-0 text-white w-100" value="btn_register">Create an Account</button>

        <div class="d-flex justify-content-center mt-3">
            <?php echo wLinkDefault('#', 'Resend Email Verication code')?>
        </div>

        <br><br>
        <p class="mb-1 pb-0">Already have an account? <?php echo wLinkDefault(_route('landing_login'), 'Login Here')?></p>
    </div>
</form>