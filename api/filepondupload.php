<?php 
    $uploadFolder = '../public/uploads/images';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $getData = $_GET;
        $filePond = $_FILES['filepond'];
        //folder where the images will be placed
        $imageFolder = $getData['imageFolder'];
        if(!file_exists("{$uploadFolder}/{$imageFolder}")) {
            mkdir("{$uploadFolder}/{$imageFolder}");
        }

        $fileExtension = pathinfo($filePond['name'], PATHINFO_EXTENSION);
        $newName = date('YmdHis',time()).mt_rand().'.'.$fileExtension;
        $isOkay = move_uploaded_file($filePond['tmp_name'],"{$uploadFolder}/{$imageFolder}/{$newName}");

        if(true) {
            echo json_encode([
                'imageFolder' => $imageFolder,
                'uploadFolder' => "{$uploadFolder}/{$imagefolder}"
            ]);
        }else{
            echo 'notokay';
        }
    }
?>