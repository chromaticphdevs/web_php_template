<?php
	session_start();
	/*
	*require config files file
	*/
	require_once 'config/env.php';
	require_once 'config/assets.php';
	require_once 'config/database.php';
	require_once 'config/plugins.php';

	/*
	*require function file
	*/
	require_once 'functions/core.php';
	require_once 'functions/uncommon.php';
	require_once 'functions/template.php';
	require_once 'functions/path.php';

	spl_autoload_register(function($class_name)
  	{
		$file = null;
		$invalidChars = array(
        '.', '\\', '/', ':', '*', '?', '"', '<', '>', "'", '|'
		);

		$class_name = ucfirst(str_replace($invalidChars, '', $class_name));
		$extension_prefix = '.php';

		$basePath = getcwd();

		if(file_exists('services/'.$class_name.$extension_prefix)) {
			return require_once 'services/'.$class_name.$extension_prefix;
		} elseif(file_exists('core/'.$class_name.$extension_prefix)) {
			return require_once 'core/'.$class_name.$extension_prefix;
		} else {
			echo die("{$class_name} NOT FOUND");
		}
	});
?>


<?php
	//module engine
	$moduleRequest = $_GET['cmodule'] ?? 'modbase';

	switch ($moduleRequest) {
		case 'modbase':
			moduleLoader('modbase_index');
			break;
		
		default:
			moduleLoader($moduleRequest);
			break;
	}

	function moduleLoader($moduleString) {
		$moduleString = trim($moduleString);
		//get module
		$modulePosition = strpos($moduleString,'_');
		$module = substr($moduleString, 0, $modulePosition);
		$view   = substr($moduleString, $modulePosition + 1);

		return require_once("modules/{$module}/{$view}.php");
	}
?>