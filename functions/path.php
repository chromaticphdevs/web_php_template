<?php
    function _path_public($args)
    {
        return PATH_PUBLIC.DS.$args;
    }


    function _path_asset($args = null)
    {
        if(is_null($args))
            return PATH_PUBLIC.DS.'assets';
        return PATH_PUBLIC.DS.'assets'.DS.$args;
    }


    function _path_tmp($args)
    {
        if(is_null($args))
            return PATH_PUBLIC.'/'.'templates/';
        return PATH_PUBLIC.'/'.'templates/'.$args;
    }


    function _path_vendor($args)
    {
        if(is_null($args))
            return PATH_PUBLIC.DS.'vendor';
        return PATH_PUBLIC.DS.'vendor'.DS.$args;
    }

    function _path_base($args = null)
    {
        if(is_null($args))
            return PATH_PUBLIC;
        return PATH_PUBLIC.DS.$args;
    }


    function _path_third_party($args)
    {
        return PATH_PUBLIC.DS.'thirdparty'.DS.$args;
    }
?>