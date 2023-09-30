<?php
	use Form\CommonForm;

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

	load(['CommonForm'],FORMS);

	$_formCommon = new CommonForm();

	spl_autoload_register(function($class_name)
  	{
		$file = null;
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


<?php

	$moduleRequest = $_GET['cmodule'] ?? '';

	if(empty($moduleRequest)) {
		$moduleRequest = $settings['modules']['base_module'] . "_" . $settings['modules']['base_module_view'];
	}

	switch ($moduleRequest) {

		default:
			moduleLoader($moduleRequest);
			break;
	}

	function moduleLoader($moduleString) {
		global $settings;
		$prefix = null;
		$moduleString = trim($moduleString);
		//get module

		$modulePosition = strpos($moduleString,'_');

		if($modulePosition === FALSE)  {
			//default action
		} else {
			$module = substr($moduleString, 0, $modulePosition);
			$view   = substr($moduleString, $modulePosition + 1);

			$moduleBase = explode('/', $module);
			$moduleBase = current($moduleBase);

			if($moduleBase != 'public') {
				if(!empty($settings['modules']['fileSettings']['alias'][$module])) {
					$module = $settings['modules']['fileSettings']['alias'][$module];
				}
	
				if(!empty($settings['modules']['fileSettings']['prefix'][$module])) {
					$prefix = $settings['modules']['fileSettings']['prefix'][$module];
				}
				//check module if has alias
				return require_once("modules/{$module}/{$prefix}{$view}.php");
			}
			
		}
	}
?>