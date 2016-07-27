<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Session Helper
 *
 * @package		CodeNimbly
 * @subpackage  Helpers
 * @category	Helpers
 * @since       Version 1.0
 */
 

if (! function_exists('set_session')) {
	function set_session($key, $value)
    {   
        global $Registry;
		return $Registry->session->set($key, $value);
	}
}

if (! function_exists('add_session')) {
	function add_session($key, $value)
    {   
        global $Registry;
		return $Registry->session->add($key, $value);
	}
}

if (! function_exists('add_session')) {
	function set_session_data($data_array)
    {   
        global $Registry;
		return $Registry->session->set_data($data_array) ;
	}
}


if (! function_exists('get_session')) {
	function get_session($key, $delete_after_flash = false) 
    {   
        global $Registry;
		return $Registry->session->get($key, $delete_after_flash) ;
	}
}