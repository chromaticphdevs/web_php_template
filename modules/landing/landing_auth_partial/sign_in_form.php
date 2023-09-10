<?php extract($data)?>
<form action="" method="post">
    <div id="formSignIn">
        <p class="mb-1 pb-0">Login to your account.</p>
        <div class="form-floating mb-2">
            <?php echo $formAccount->getCol('email')?>
        </div>
        <div class="form-floating mb-2">
            <?php echo $formAccount->getCol('password')?>
        </div>
        <div class="d-flex justify-content-between mb-0">
            <p class="small"><a href="#" class="formclick link-secondary" data="F" >Forgot Password?</a></p>
        </div>
        <input type="submit" class="btn btn-info bg-col1 border-0 text-white w-100"
            value="Log In" name="btn_login">
        <?php echo wDivider()?>
        <div>
            <p class="mb-1 pb-0">Not yet registered?</p>
            <a href="?form=sign_up" class="" >Create an Account Here.</a>
        </div>
        <?php echo wDivider()?>
        <button type="button" onclick="goBack();" 
            class="btn btn-info bg-col1 border-0 text-white w-100" >Go Back</button>
        <br>
        <div class="d-flex justify-content-center">
            <button onclick="resendverification();" class="btn btn-link text-secondary" style="display: <?php echo $display;?>;">Resend Email verificaion code</button>
        </div>
    </div>
</form>
