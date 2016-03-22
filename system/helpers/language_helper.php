<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Security core library Helper
 *
 * @package		CodeNimbly
 * @subpackage  Helpers
 * @category	Helpers
 * @since       Version 1.0
 */
 
if (! function_exists('get_lang_text')) {
	function get_lang_text($text_index)
    {   
        global $Registry;
		return $Registry->language->getText($text_index);
	}
}

if (! function_exists('set_lang_text')) {
	function set_lang_text($text_index, $value)
    {   
        global $Registry;
		return $Registry->language->setText($text_index, $value);
	}
}

if (! function_exists('get_lang')) {
	function get_lang()
    {   
        global $Registry;
		return $Registry->language->getLang();
	}
}

if (! function_exists('set_lang')) {
	function set_lang($lang)
    {   
        global $Registry;
		return $Registry->language->setLang($lang);
	}
}

if (! function_exists('get_lang_meta')) {
	function get_lang_meta($index)
    {   
        global $Registry;
		return $Registry->language->getMeta($index);
	}
}

if (! function_exists('get_lang_charset')) {
	function get_lang_charset()
    {   
        global $Registry;
		return $Registry->language->getCharset();
	}
}

if (! function_exists('get_language')) {
	function get_language()
    {   
        global $Registry;
		return $Registry->language->getLanguage();
	}
}

if (! function_exists('get_lang_flag')) {
	function get_lang_flag()
    {   
        global $Registry;
		return $Registry->language->getFlag();
	}
}





