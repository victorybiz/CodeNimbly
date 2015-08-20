<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * CSRF Helper
 *
 * @package		CodeNimbly
 * @subpackage  Helpers
 * @category	Helpers
 * @since       Version 1.0
 */
 

if (! function_exists('csrf_token_id')) {
	function csrf_token_id()
    {   
        global $Registry;
		return $Registry->csrf->getTokenId();
	}
}

if (! function_exists('csrf_token')) {
	function csrf_token()
    {   
        global $Registry;
		return $Registry->csrf->getToken();
	}
}


if (! function_exists('csrf_token_form_input_field')) {
	function csrf_token_form_input_field()
    {   
        global $Registry;
		return $Registry->csrf->getTokenFormInputField();
	}
}

if (! function_exists('csrf_token_url_query_string')) {
	function csrf_token_url_query_string()
    {   
        global $Registry;
		return $Registry->csrf->getTokenUrlQueryString();
	}
}


