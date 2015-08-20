<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Date Helper
 *
 * @package		CodeNimbly
 * @subpackage  Helpers
 * @category	Helpers
 * @since       Version 1.0
 */

if (! function_exists('convert_to_since_ago')) {
	function convert_to_since_ago($timestamp, $timestamp_is_datetime = false)
    {   
        global $Registry;
		return $Registry->date->convertToSinceAgo($timestamp, $timestamp_is_datetime);
	}
}

