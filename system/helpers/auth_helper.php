<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Auth Helper
 *
 * @package		CodeNimbly
 * @subpackage  Helpers
 * @category	Helpers
 * @since       Version 1.0
 */
 

if (! function_exists('auth_check_auth')) {
	function auth_check_auth()
    {   
        global $Registry;
		return $Registry->auth->checkAuth(); 
	}
}

if (! function_exists('auth_is_user_logged_in')) {
	function auth_is_user_logged_in()
    {   
        global $Registry;
		return $Registry->auth->isUserLoggedIn(); 
        
	}
}


if (! function_exists('auth_user_id')) {
	function auth_user_id()
    {   
        global $Registry;
		return $Registry->auth->userId(); 
        
	}
}

if (! function_exists('auth_session_id')) {
	function auth_session_id()
    {   
        global $Registry;
		return $Registry->auth->sessionId(); 
        
	}
}
