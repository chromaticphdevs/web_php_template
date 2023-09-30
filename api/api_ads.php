<?php
    require 'autoloader.php';

    $req = request()->inputs();
    $post = request()->posts();

    $adsService = new AdService();

    //posts requests
    if(isSubmitted()) {

    } else {
        //get requests
    }
?>