<?php
	/**
	 * hide to root folder*/
	$database = [];

	$database['uat'] = [
		'vendor' => 'mysql',
		'hostname' => 'localhost',
		'db_user' => 'root',
		'db_password' => '',
		'db_name' => 'cindy_db'
	];

	$database['dev'] = [
		'vendor' => 'mysql',
		'hostname' => 'localhost',
		'db_user' => 'root',
		'db_password' => '',
		'db_name' => 'cindy_db'
	];

	$database['prod'] = [
		'vendor' => 'mysql',
		'hostname' => 'localhost',
		'db_user' => 'root',
		'db_password' => '',
		'db_name' => 'cindy_db'
	];
	
	return $database;
?>