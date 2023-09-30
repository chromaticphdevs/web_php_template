<?php
    $uploadFolder = '../public/uploads/images';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $getData = $_GET;
        $filePond = $_FILES['filepond'];
        //folder where the images will be placed
        $imageFolder = $getData['imageFolder'];
        $userRecno = $getData['userRecno'];

        if(!file_exists("{$uploadFolder}/{$imageFolder}")) {
            mkdir("{$uploadFolder}/{$imageFolder}");
        }

        $fileExtension = pathinfo($filePond['name'], PATHINFO_EXTENSION);
        $newName = date('YmdHis',time()).mt_rand().'.'.$fileExtension;
        $isOkay = move_uploaded_file($filePond['tmp_name'],"{$uploadFolder}/{$imageFolder}/{$newName}");

        if(true) {
            require 'autoloader.php';
            $accountService = new AccountService();
            
            $user = $accountService->single([
                'where' => [
                    'recno' => $userRecno
                ]
            ]);

            /**
             * DELETE PREVIOUS PROFILE PICTURE
             * SAVES SPACE
             */
            if($user['profile']) {
                $profileName = explode('/', $user['profile']);
                $profileName = end($profileName);
                
                if(file_exists(APPROOT.DS."public/uploads/images/{$imageFolder}/{$profileName}")) {
                    unlink(APPROOT.DS."public/uploads/images/{$imageFolder}/{$profileName}");
                }
            }

            $accountService->update([
                'profile' => URL.DS."public/uploads/images/{$imageFolder}/{$newName}"
            ], [
                'recno' => $userRecno
            ]);

            $accountService->startAuth($userRecno);
            Flash::set("Profile has been updated","success", "account_profile_message");
            echo json_encode([
                'status' => 'success'
            ]);
        }else{
            echo 'notokay';
        }
    }
?>