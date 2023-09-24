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