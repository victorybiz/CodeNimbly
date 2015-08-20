<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Config Helper
 *
 * Set and get Config data items
 *
 * @package		CodeNimbly
 * @subpackage  Helpers
 * @category	Helpers
 * @since       Version 1.0
 */
 

if (! function_exists('get_config')) {
	function get_config($index)
    {   
        global $Registry;
		return $Registry->config->get($index);
	}
}

if (! function_exists('set_config')) {
	function set_config($index, $value, $replace = false)
    {   
        global $Registry;
		return $Registry->config->set($index, $value, $replace);
	}
}