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
	
	function _route($pageName, $parameterId = '', $parameter = []) {

        $parameterString = '';

        if(!empty($parameterId))
        {
            if(is_array($parameterId))
            {
                $parameterString .= "?";

                $counter = 0;
                foreach($parameterId as $key => $row) 
                {
                    if( $counter > 0)
                        $parameterString .= "&";

                    $parameterString .= "{$key}={$row}";
                    $counter++;
                }
            }else{
                //parameter is id
                $parameterString = '/'.$parameterId.'?';
            }
        }

        if(is_array($parameter) && !empty($parameter))
        {
            if( empty($parameterString) )
                $parameterString .= '?';
            $counter = 0;
            foreach($parameter as $key => $row) 
            {
                if( $counter > 0)
                    $parameterString .='&';
                $parameterString .= "{$key}={$row}";
                $counter++;
            }
        }

        return $pageName.$parameterString;
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

	function pre($data)
    {
        echo '<pre>';
            var_dump($data);
        echo '</pre>';
    }
	
    function dd($data)
    {
        $data = json_encode($data);
        die($data);
    }

    function _error($message = ERR_INVALID_REQUEST) {
        echo die($message);
    }

    function _mail_base($subject, $content, $recipients = [],  $header = []) 
    {
        $plugins = require APPROOT.DS.'config/plugins.php';

        if(empty($header['from'])){
            $header['from'] = $plugins['mailAccount']['master']['key'];
        }

        if(empty($header['replyTo'])) {
            $header['replyTo'] = EMAIL_B;
        }

        $headerItem = "From: <{$header['from']}> \r\n";
        $headerItem .= "Reply-To: ".$header['replyTo']." \r\n";
        $headerItem .= "Content-type: text/html \r\n";

        if(is_array($recipients)) {
            $recipients = implode(',', $recipients);
        } 

        mail($recipients, $subject, $content, $headerItem);
    }
?>