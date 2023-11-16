<?php
	/**
	 * hide to root folder*/
	$database = [];

	$database['local'] = [
		'vendor' => 'mysql',
		'hostname' => 'localhost',
		'db_user' => 'root',
		'db_password' => '',
		'db_name' => 'cindy_real_est'
	];

	$database['uat'] = [
		'vendor' => 'mysql',
		'hostname' => 'localhost',
		'db_user' => 'marcha140_dev',
		'db_password' => 'v937xQqPE_',
		'db_name' => 'marcha140_dev'
	];

	$database['prod'] = [
		'vendor' => 'mysql',
		'hostname' => 'localhost',
		'db_user' => 'marcha140_dev',
		'db_password' => 'v937xQqPE_',
		'db_name' => 'marcha140_dev'
	];
	
	return $database;
?>