<?php
    require 'autoloader.php';
    
    $propertyClassService = new PropertyClassService();
    $req = request()->inputs();
    $condition = [];

    if(!empty($req['proptypecode'])) {
        $condition['proptypecode'] = $req['proptypecode'];
    }
    
    echo json_encode([
        'data' => $propertyClassService->getAll([
            'where' => $condition
        ])
    ]);
?>