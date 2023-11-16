<?php   
    function wReturnLink( $route )
    {
        print <<<EOF
            <a href="{$route}">
                <i class="feather icon-corner-up-left"></i> Return
            </a>
        EOF;
    }

    function wLinkDefault($link , $text = 'Edit' , $attributes = [])
	{	
        //for noble
		$icon = isset($attributes['icon']) ? "<i class='fa {$attributes['icon']}' style='width:15px'></i>" : '';
        if(isset($attributes['icon'])) {
            unset($attributes['icon']);
        }
		$attributes = is_null($attributes) ? $attributes : keypairtostr($attributes);
        
		return <<<EOF
			<a href="{$link}" style="text-decoration:underline" {$attributes}>{$icon} {$text}</a>
		EOF;
	}

    function wDivider($size = '30')
    {
        return <<<EOF
            <div style="margin-top:{$size}px"> </div>
        EOF;
    }

    function wButton($link, $text = 'Submit', $btn, $attributes =[]) {
        $icon = isset($attributes['icon']) ? "<i class='fa {$attributes['icon']}' style='width:15px'></i>" : '';
        if(isset($attributes['icon'])) {
            unset($attributes['icon']);
        }

        if(!empty($attributes['class'])) {
            $attributes['class'] = $attributes['class'] .' btn btn-'.$btn;
        }else{
            $attributes['class'] = 'btn btn-'.$btn;
        }
        
		$attributes = is_null($attributes) ? $attributes : keypairtostr($attributes);

        return <<<EOF
			<a href="{$link}" {$attributes}>{$icon} {$text}</a>
		EOF;
    }
    //100 / 4 
    function wPaginator($numberOfItems, $itemPerPage, $pageNo, $routeName, $queryParam, $attributes = []) {
        // if($numberOfItems < $itemPerPage) {
        //     //no pagination needed
        //     return false;
        // }
        
        $linkHTML = '';
        $linkCount = ceil($numberOfItems / $itemPerPage);
        for($i = 1; $i <= $linkCount; $i++) {
            $queryParam['page'] = $i;
            $href = _route($routeName, $queryParam);
            $linkHTML .= "
                <li class='page-item'>
                    <a href='{$href}' class ='page-link'>{$i}</a>
                </li>
            ";
        }

        $lastRoute = $queryParam;
        $lastRoute['page'] = $linkCount;
        $lastRoute = _route($routeName, $lastRoute);

        $firstRoute = $queryParam;
        $firstRoute['page'] = 1;
        $firstRoute = _route($routeName, $firstRoute);

        return "
            <ul class='pagination' style='margin:0px auto;'>
                <li class='page-item'>
                    <a href='{$firstRoute}' class ='page-link'>First</a>
                </li>
                {$linkHTML}
                <li class='page-item'>
                    <a href='{$lastRoute}' class ='page-link'>Last</a>
                </li>
            </ul>
        ";
    }