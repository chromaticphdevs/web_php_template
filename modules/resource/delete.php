<?php
    $req = request()->inputs();

    if(!empty($req['path'])) {
        $path = unseal($req['path']);
        unlink($path);
        Flash::set("Resource Deleted");
    }

    if(!empty($req['returnTo'])) {
        return redirect(_route(unseal($req['returnTo'])));
    } else {
        return request()->return();
    }
?>