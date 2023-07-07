<?php
    function loadTo($viewPath = '_tmp/layout')
    {
        $data = $GLOBALS['data'] ?? null;

        if( !is_null($data) )
            extract($data);

        $viewPath = convertDotToDS($viewPath);

        require_once MODULES.DS.$viewPath.'.php';
    }

    function pull_view($viewPath , $data = null)
    {
        $viewPath = convertDotToDS($viewPath);

        if(isset($_GLOBALS['data']))
        {
            $globalData = $GLOBALS['data'];
            extract($globalData);
        }


        $viewPath = convertDotToDS($viewPath);

        return require_once VIEWS.DS.$viewPath.'.php';
    }
    /*BUILD
    *This will build a html content
    * and will be stored on render builds
    */
    function build($buildName)
    {
        Material::$buildInstance++;
        Material::addBuild($buildName);

        ob_start();
    }

    /*ENDBUILD
    *This will get all html inside in between build and this function
    *
    */
    function endbuild()
    {
        Material::$buildInstance;
        Material::build(ob_get_contents());

        ob_end_clean();
    }

    /*ENDBUILD
    *This will produce a render build
    */

    function produce($varName)
    {
        echo Material::show($varName);
    }