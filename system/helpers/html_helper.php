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
	function clean_html_ouput($data)
    {
        global $Registry;        
        return htmlspecialchars($data, ENT_QUOTES, $Registry->config->get('charset'));
    }
}
if (! function_exists('html_entities')) {
	function html_entities($data)
    {
        global $Registry;        
        return htmlentities($data, ENT_QUOTES, $Registry->config->get('charset'));
    }
}
if (! function_exists('html_entities_decode')) {
    function html_entities_decode($data)
    {
        global $Registry;        
        return html_entity_decode($data, ENT_QUOTES, $Registry->config->get('charset'));
    }
}


if (! function_exists('html_purify')) {
	function html_purify($data, $config=null)
    {
        global $Registry;        
        $Registry->registerLibrary('HTMLPurifier', 'htmlpurifier', true);
        return $Registry->htmlpurifier->purify($data, $config);
    }
}



