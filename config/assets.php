<?php
	###############################
	####### DO NOT CHANGE #########
	###############################
    define('DS', DIRECTORY_SEPARATOR);
    //application root
	define('APPROOT' , dirname(dirname(__FILE__)));
	//core root
    define('CORE' , APPROOT.DS.'core');
    //
	define('FORMS' , APPROOT.DS.'form');
	//models
	define('MODELS' , APPROOT.DS.'models');
	//controllers
    define('CNTLRS' , APPROOT.DS.'controllers');

	define('CONFIG' , APPROOT.DS.'config');
	//controllers
	define('API' , APPROOT.DS.'api');
	//helpers root
	define('HELPERS', APPROOT.DS.'helpers');
	//library
	define('LIBS' , APPROOT.DS.'libraries');
    define('FNCTNS' ,  APPROOT.DS.'functions');
	define('MODULES' , APPROOT.DS.'modules');
	define('SERVICES' , APPROOT.DS.'services');
	define('PATH_PUBLIC' , URL.'/public');

	###############################
	define('DOMAIN_NAME', '');
	###############################
	define('SLUG_KEYWORD', 'condo');
	##################################
	define('COMPANY_NAME', 'ChromaticSoftwares');

?>