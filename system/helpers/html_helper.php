<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * HTML Helper
 *
 * @package		CodeNimbly
 * @subpackage  Helpers
 * @category	Helpers
 * @since       Version 1.0
 */
 
if (! function_exists('html_entities')) {
	function html_entities($data)
    {
        global $vs_app_init;
        
        return htmlentities($data, ENT_COMPAT, $vs_app_init['charset']);
    }
}
if (! function_exists('html_entities_decode')) {
    function html_entities_decode($data)
    {
        global $vs_app_init;
        
        return html_entity_decode($data, ENT_COMPAT, $vs_app_init['charset']);
    }
}