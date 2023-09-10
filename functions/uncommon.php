<?php
    function moduleActionRedirectCheck($redirectVar) {
        if(!empty($redirectVar)) {
            return redirect(unseal($redirectVar));
        } else {
            //return to last page
            return request()->return();
        }
    }

    function isSubmitted()
    {
        $request = $_SERVER['REQUEST_METHOD'];

        if( strtolower($request) === 'post')
            return true;

        return false;
    }  

    function whoIs($prop = null)
	{
        $user = Session::get('auth');

        if(!is_null($prop)){
            if(is_array($prop)) 
            {
                $str = '';
                foreach($prop as $key => $row) {
                    if($key >= 0)
                        $str .= " "; 
                    if(is_array($user)) {
                        $str.= $user[$row];
                    } else {
                        $str .= $user->$row;
                    }
                }
                return trim($str);
            } else {
                if(is_array($user))
                    return $user[$prop];
                if(is_object($user))
                    return $user->$prop;  
            }
        } 
        return $user ?? '';
	}

    function getApi($url)
    {
        $apiDatas = file_get_contents($url);

        if(is_null($apiDatas))
            return false;

        return json_decode($apiDatas);
    }
    /*MOVE TO CORE FUNCTIONS*/

    function view($view , $data = [])
    {
        $GLOBALS['data'] = $data;

        $view = convertDotToDS($view);

        extract($data);

        if(file_exists(VIEWS.DS.$view.'.php'))
        {
            require_once VIEWS.DS.$view.'.php';
        }else{
            die("View {$view} does not exists");
        }
    }
    /*#####################*/

    function flash_err($message)
    {
      if(is_null($message))
        $message = "SNAP! something went wrong please send this to the webmasters";
        Flash::set($message , 'danger');
    }

    function writeLog($file , $log)
    {
        $path = BASE_DIR.DS.'public'.DS.'writeable';

        if(!is_dir($path)){
            mkdir($path);
        }

        $fileName = $path.DS.$file;

        $myfile = fopen($fileName, "a") or die("Unable to open file!");

        $log = stringWrap($log , 'p');

        fwrite($myfile, $log);

        fclose($myfile);
    }


    function readWrittenLog($file)
    {
      $path = BASE_DIR.DS.'public'.DS.'writeable';

      $fileName = $path.DS.$file;

      if(!is_dir($path)){
          mkdir($path);
      }

      if(!file_exists($fileName))
        return false;
        
      $myfile = file_get_contents($fileName);
      return $myfile;
    }

    function ee($data)
    {
        echo json_encode($data);
    }

    function api_response($data , $status = true)
    {
        return [
            'data' => $data,
            'status' => $status
        ];
    }

    function require_multiple($PATH , array $files)
    {
        foreach($files as $file) {
            require_once($PATH.DS.$file.'.php');
        }
    }

    function return_require($PATH , $file)
    {
        $source = $PATH.DS.$file.'.php';
        if(file_exists($source))
            return require_once $source;
    }


    function amountHTML($amount)
	{
		$amountHTML = number_format($amount , 2);

		if($amount < 0) {
            $amountHTML = number_format(abs($amount), 2);
			return "<span>( {$amountHTML} )</span>";
		}else{
			return "<span> {$amountHTML}</span>";
		}
    }

    function api_call($method, $url, $data = false)
    {
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }


        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        return $result;
        curl_close($curl);
    }
    function base_url($args = '')
    {
      return URL.DS.$args;
    }

    function load(array $pathOrClass , $path = null)
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


    function delete_directory($directory) {
        $it = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it,
                    RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($directory);
    }

    function model($model)
    {
        $model = ucfirst($model);

        if(file_exists(MODELS.DS.$model.'.php')){

            require_once MODELS.DS.$model.'.php';

            return new $model;
        }
        else{

            die($model . 'MODEL NOT FOUND');
        }
    }
    