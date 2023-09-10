<?php
    session_destroy();
    return redirect(_route('landing_auth'));
?>