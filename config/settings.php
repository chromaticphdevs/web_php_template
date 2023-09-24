<?php 
	
	/*
	*main settings
	*settings are editable
	*/
	$settings = [];

	/*
	*set your modules alias and file prefix
	*/
	$settings['modules'] = [
		'fileSettings' => [
			'alias' => [],
			'prefix' => []
		],

		'base_module' => 'landing',
		'base_module_view' => 'index'
	];

	/**
	 *set your module alias here*/
	$settings['modules']['fileSettings']['alias'] = [
		'prop' => 'property',
		'inq'  => 'inquiries'
	];

	/**
	 *set your module alias here*/
	$settings['modules']['fileSettings']['prefix'] = [
		'property' => 'prop_',
		'ads' => 'ad_',
		'landing' => 'landing_',
		'inq' => 'inq_',
		'intent' => 'intent_'
	];


	return $settings;