<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Form Helper
 * - helper to Input librbary
 *
 * @package		CodeNimbly
 * @subpackage  Helpers
 * @category	Helpers
 * @since       Version 1.0
 */
 

if (! function_exists('set_form_value')) {
	function set_form_value($field, $default="")
    {   
        global $Registry;
		$value = $Registry->request->post($field);
        if (!is_null($value)) {
            return $value;
        } else {
            return $default;
        }
	}
}


