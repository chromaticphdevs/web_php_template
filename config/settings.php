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
		'base' => 'home_index'
	];

	/**
	 *set your module alias here*/
	$settings['modules']['fileSettings']['alias'] = [
		'x' => 'xsample',
		'prop' => 'property',
	];

	/**
	 *set your module alias here*/
	$settings['modules']['fileSettings']['prefix'] = [
		'property' => 'prop.',
		'ads' => 'ads.'
	];


	return $settings;