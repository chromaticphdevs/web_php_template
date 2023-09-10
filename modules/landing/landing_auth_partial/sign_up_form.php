<?php extract($data) ?>
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
        <input type="password" class="form-control" id="S_repass" placeholder="Re Enter Password">
        <label for="S_repass">Re Enter Password</label>
    </div>
    <button id="S_butt" type="button" class="btn btn-info bg-col1 border-0 text-white w-100">Create an Account</button>

    <br><br>
    <p class="mb-1 pb-0">Already have an account?</p>
    <a href="?form=sign_in" class="formclick btn btn-info bg-col1 border-0 text-white w-100 mb-3">Log In</a>
    <button type="button" onclick="goBack();" class="btn btn-info bg-col1 border-0 text-white w-100" >Go Back</button>
</div>