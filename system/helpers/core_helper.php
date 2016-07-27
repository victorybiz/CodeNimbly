<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Core Helper
 *
 * @package		CodeNimbly
 * @subpackage  Helpers
 * @category	Helpers
 * @since       Version 1.0
 */
 

if (! function_exists('get_registry')) {
	function get_registry()
    {   
        global $Registry;
        return $Registry;
	}
}


