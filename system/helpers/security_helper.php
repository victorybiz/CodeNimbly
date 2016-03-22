<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Security core library Helper
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
		return $Registry->security->csrfTokenId();
	}
}

if (! function_exists('csrf_token')) {
	function csrf_token()
    {   
        global $Registry;
		return $Registry->security->csrfToken();
	}
}


if (! function_exists('csrf_token_form_input_tag')) {
	function csrf_token_form_input_tag()
    {   
        global $Registry;
		return $Registry->security->csrfTokenFormInputTag();
	}
}

if (! function_exists('csrf_token_url_query_string')) {
	function csrf_token_url_query_string()
    {   
        global $Registry;
		return $Registry->security->csrfTokenUrlQueryString();
	}
}

if (! function_exists('csrf_verify_request')) {
	function csrf_verify_request($method)
    {   
        global $Registry;
		return $Registry->security->csrfVerifyRequest($method);
	}
}

if (! function_exists('xss_filter')) {
	function xss_filter($data)
    {   
        global $Registry;
		return $Registry->security->xssFilter($data);
	}
}




