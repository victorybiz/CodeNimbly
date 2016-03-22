<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Date Helper
 *
 * @package		CodeNimbly
 * @subpackage  Helpers
 * @category	Helpers
 * @since       Version 1.0
 */

if (! function_exists('date_to_timestamp')) {
	function date_to_timestamp($date)
    {   
        global $Registry;
		return $Registry->date->toTimestamp($date);
	}
}

if (! function_exists('date_to_since_ago')) {
	function date_to_since_ago($date)
    {   
        global $Registry;
		return $Registry->date->toSinceAgo($date);
	}
}

if (! function_exists('date_to_future_time')) {
	function date_to_future_time($date)
    {   
        global $Registry;
		return $Registry->date->toFutureTime($date);
	}
}

if (! function_exists('get_date')) {
	function get_date($timestamp = null)
    {   
        global $Registry;
		return $Registry->date->getDate($timestamp);
	}
}

if (! function_exists('get_datetime')) {
	function get_datetime($timestamp = null)
    {   
        global $Registry;
		return $Registry->date->getDateTime($timestamp);
	}
}

if (! function_exists('get_formated_date')) {
	function get_formated_date($date = null, $short_date = false)
    {   
        global $Registry;
		return $Registry->date->getFormatedDate($date, $short_date);
	}
}

if (! function_exists('get_formated_datetime')) {
	function get_formated_datetime($date = null, $short_datetime = false)
    {   
        global $Registry;
		return $Registry->date->getFormatedDateTime($date, $short_datetime);
	}
}


if (! function_exists('format_date')) {
	function format_date($format, $date = null)
    {   
        global $Registry;
		return $Registry->date->format($format, $date);
	}
}

if (! function_exists('date_to_iso8601')) {
	function date_to_iso8601($date = null)
    {   
        global $Registry;
		return $Registry->date->toIso8601($date);
	}
}