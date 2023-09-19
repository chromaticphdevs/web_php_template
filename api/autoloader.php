<?php
	use Form\CommonForm;
    chdir(dirname(__DIR__));

	session_start();
	/*
	*require config files file
	*/
	require_once 'config/env.php';
	require_once 'config/assets.php';
	require_once 'config/database.php';
	require_once 'config/plugins.php';
	require_once 'config/settings.php';

	/*
	*require function file
	*/
	require_once 'functions/core.php';
	require_once 'functions/path.php';
	require_once 'functions/string.php';
	require_once 'functions/date.php';
	require_once 'functions/array.php';

	require_once 'functions/template.php';
	require_once 'functions/uncommon.php';
	require_once 'functions/request.php';
	require_once 'functions/app_widget.php';
	require_once 'functions/token.php';
	require_once 'functions/custom.php';

	spl_autoload_register(function($class_name)
  	{
		$invalidChars = array(
        '.', '\\', '/', ':', '*', '?', '"', '<', '>', "'", '|'
		);

		$class_name = ucfirst(str_replace($invalidChars, '', $class_name));
		$extension_prefix = '.php';

		if(file_exists('core/'.$class_name.$extension_prefix)) {
			return require_once 'core/'.$class_name.$extension_prefix;
		}elseif(file_exists('services/'.$class_name.$extension_prefix)){
			return require_once 'services/'.$class_name.$extension_prefix;
		}elseif(file_exists('helpers/'.$class_name.$extension_prefix)){
			return require_once 'helpers/'.$class_name.$extension_prefix;
		}else {
			echo die("{$class_name} NOT FOUND");
		}
	});
?>