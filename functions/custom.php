<?php 

function head_tag($mainpath){
    $curr = time();
    $out = <<< EOFILE
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>
        <link rel="shortcut icon" type="image/png" href="{$mainpath}img/logo30x30.png">
        
        <link rel="stylesheet" href="{$mainpath}css/bootstrap.min.css">
  
        <link rel="stylesheet" href="{$mainpath}css/all.min.css">
        <link rel="stylesheet" href="{$mainpath}css/animations.css">
        <link rel="stylesheet" href="{$mainpath}dropzone/dropzone.min.css">
        <link rel="stylesheet" href="{$mainpath}mycss.css?ver=$curr">
  
        <script src="{$mainpath}js/jquery-3.6.0.min.js"></script>
        <script src="{$mainpath}js/bootstrap.bundle.min.js"></script>
        <script src="{$mainpath}js/popper.min.js"></script>
        <script src="{$mainpath}js/all.min.js"></script>
        <script src="{$mainpath}dropzone/dropzone.min.js"></script>
        <script src="{$mainpath}myjs.js?ver=$curr"></script>
    EOFILE;
    return $out;
  }