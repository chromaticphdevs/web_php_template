<?php
	function _redirect($url) {
		return header("Location:{$url}");
	}
	
	function _route() {

	}

	function _config($fileName) {
		$file = require "config/{$fileName}.php";
		return $file;
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

	function str_escape($value)
    {
        $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
        $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

        return str_replace($search, $replace, $value);
    }
?>