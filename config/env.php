<?php
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
	
	define('ENV_DATABASE', 'local');

	switch(ENV_DATABASE) {
		case 'prod':
			define('URL', 'http://pinoypropertybroker.com');
		break;

		case 'uat';
			define('URL', 'http://pinoypropertybroker.com');
		break;

		default:
			define('URL', 'http://localhost/website_archi');

	}
?>