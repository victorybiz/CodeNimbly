<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * URL Helper
 *
 * @package		CodeNimbly
 * @subpackage  Helpers
 * @category	Helpers
 * @since       Version 1.0
 */
 
if (! function_exists('base_url')) {
	function base_url($uri = '')
    {   
        global $Registry;
		$base_url = $Registry->config->get('base_url');
        if (substr($base_url, -1, 1) != '/')  {
            $base_url .= '/';
        }
        return $base_url . $uri;
	}
}


if (! function_exists('static_url')) {
	function static_url($uri = '')
    {   
        global $Registry;
		$static_url = $Registry->config->get('static_url');
        if (substr($static_url, -1, 1) != '/')  {
            $static_url .= '/';
        }
        return $static_url . $uri;
	}
}


if (! function_exists('current_page_url')) {
	function current_page_url()
    {   
        global $Registry;
		$base_url = $Registry->config->get('base_url');        
        if (substr($base_url, -1, 1) == '/')  {
            $base_url = substr($base_url, 0, strlen($base_url)-1);
        }
        return $base_url . $_SERVER["REQUEST_URI"];
	}
}



if (! function_exists('js_url')) {
	function js_url($uri = '')
    {   
        global $Registry;
		$js_url = $Registry->config->get('js_url');
        if (substr($js_url, -1, 1) != '/')  {
            $js_url .= '/';
        }
        return $js_url . $uri;
	}
}

if (! function_exists('css_url')) {
	function css_url($uri = '')
    {   
        global $Registry;
		$css_url = $Registry->config->get('css_url');
        if (substr($css_url, -1, 1) != '/')  {
            $css_url .= '/';
        }
        return $css_url . $uri;
	}
}


if (! function_exists('images_url')) {
	function images_url($uri = '')
    {   
        global $Registry;
		$images_url = $Registry->config->get('images_url');
        if (substr($images_url, -1, 1) != '/')  {
            $images_url .= '/';
        }
        return $images_url . $uri;
	}
}

if (! function_exists('third_party_url')) {
	function third_party_url($uri = '')
    {   
        global $Registry;
		$third_party_url = $Registry->config->get('third_party_url');
        if (substr($third_party_url, -1, 1) != '/')  {
            $third_party_url .= '/';
        }
        return $third_party_url . $uri;
	}
}

if (! function_exists('uploads_url')) {
	function uploads_url($uri = '')
    {   
        global $Registry;
		$uploads_url = $Registry->config->get('uploads_url');
        if (substr($uploads_url, -1, 1) != '/')  {
            $uploads_url .= '/';
        }
        return $uploads_url . $uri;
	}
}



/**
 * Header Redirect
 *
 * Header redirect in two flavors
 * For very fine grained control over headers, you could use the Output
 * Library's set_header() function.
 *
 * @access	public
 * @param	string $uri - the URI string
 * @param	string $method - Redirect method (�auto�, �location� or �refresh�)
 * @param	integer $http_response_code - HTTP Response code (usually 302 or 303)
 * @return	void
 */
if ( ! function_exists('redirect'))
{
	function redirect($uri = '', $method = 'location', $http_response_code = 302)
	{
	   $url = $uri;
		if ( ! preg_match('#^https?://#i', $uri)) {
			$url = base_url($uri);
		}
		switch($method)
		{
			case 'refresh'	: header("Refresh:0;url=".$url);
				break;
			default			: header("Location: ".$url, true, $http_response_code);
				break;
		}
		exit;
	}
}



if (! function_exists('create_slug')) {
    function create_slug($str, $separator = '-', $lowercase = true)
	{
		if ($separator == 'dash'){
		    $separator = '-';
		}
		else if ($separator == 'underscore'){
		    $separator = '_';
		}
		
		$q_separator = preg_quote($separator);

		$trans = array(
			'&.+?;'                 => '',
			'[^a-z0-9 _-]'          => '',
			'\s+'                   => $separator,
			'('.$q_separator.')+'   => $separator
		);

		$str = strip_tags($str);

		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		if ($lowercase === true){
			$str = mb_strtolower($str);
		}

		return trim($str, $separator);
	}
 }