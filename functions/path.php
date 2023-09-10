<?php

    function _path_public($args)
    {
        return URL_PUBLIC.DS.$args;
    }
    
    function _path_asset($args = null)
    {
        if(is_null($args))
            return URL_PUBLIC.DS.'assets';
        return URL_PUBLIC.DS.'assets'.DS.$args;
    }


    function _path_tmp($args)
    {
        if(is_null($args))
            return URL_PUBLIC.'//templates/main';
        return URL_PUBLIC.'//templates/'.$args;
    }


    function _path_vendor($args)
    {
        if(is_null($args))
            return URL_PUBLIC.DS.'vendor';
        return URL_PUBLIC.DS.'vendor'.DS.$args;
    }

    function _path_base($args = null)
    {
        if(is_null($args))
            return URL_PUBLIC;
        return URL_PUBLIC.DS.$args;
    }


    function _path_third_party($args)
    {
        return URL_PUBLIC.DS.'thirdparty'.DS.$args;
    }

    function url_link($args)
    {
      return URL.'/'.$args;
    }