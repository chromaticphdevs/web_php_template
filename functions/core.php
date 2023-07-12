<?php
	function _load(array $pathOrClass , $path = null)
    {
      if(is_null($path)) {
        foreach($pathOrClass as $key => $row) {
          require_once $row.'.php';
        }
      }else{
        foreach($pathOrClass as $key => $row) {
          require_once $path.DS.$row.'.php';
        }
      }
    }

	function _redirect($url) {
		return header("Location:{$url}");
	}
	
	function _route() {

	}

	function _config($fileName) {
		$file = require "config/{$fileName}.php";
		return $file;
	}

	function _setting($settings = null) {
		if(is_null($settings)) {
			$settings = _config('settings.php');
		}

		return $settings;
	}

	function dump($data) {
		echo '<pre>';
		print_r($data);
		die();
	}

	function convertDotToDS($path)
    {
        return str_replace('.' , DS , $path);
    }

?>