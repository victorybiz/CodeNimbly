<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Geo Location Helper
 *
 * @package		CodeNimbly
 * @subpackage  Helpers
 * @category	Helpers
 * @since       Version 1.0
 */
 
 
if (! function_exists('geo_location_get_ip')) {
	function geo_location_get_ip($deep_detect = true, $set_localhost_ip_as = '169.159.82.111')
    {   
        global $Registry;
		return $Registry->geo_location->ip($deep_detect, $set_localhost_ip_as);
	}
}


if (! function_exists('geo_location_locate')) {
	function geo_location_locate($ip = null, $deep_detect = true)
    {   
        global $Registry;
		return $Registry->geo_location->locate($ip, $deep_detect);
	}
}

if (! function_exists('geo_location_ip')) {
	function geo_location_ip()
    {   
        global $Registry;
		return $Registry->geo_location->ip;
	}
}




if (! function_exists('geo_location_city')) {
	function geo_location_city()
    {   
        global $Registry;
		return $Registry->geo_location->city;
	}
}

if (! function_exists('geo_location_region')) {
	function geo_location_region()
    {   
        global $Registry;
		return $Registry->geo_location->region;
	}
}


if (! function_exists('geo_location_area_code')) {
	function geo_location_area_code()
    {   
        global $Registry;
		return $Registry->geo_location->areaCode ;
	}
}

if (! function_exists('geo_location_dma_code')) {
	function geo_location_dma_code()
    {   
        global $Registry;
		return $Registry->geo_location->dmaCode;
	}
}


if (! function_exists('geo_location_country_code')) {
	function geo_location_country_code()
    {   
        global $Registry;
		return $Registry->geo_location->countryCode;
	}
}

if (! function_exists('geo_location_country_name')) {
	function geo_location_country_name()
    {   
        global $Registry;
		return $Registry->geo_location->countryName;
	}
}

if (! function_exists('geo_location_continent_code')) {
	function geo_location_continent_code()
    {   
        global $Registry;
		return $Registry->geo_location->continentCode;
	}
}

if (! function_exists('geo_location_continent_name')) {
	function geo_location_continent_name ()
    {   
        global $Registry;
		return $Registry->geo_location->continentName ;
	}
}

if (! function_exists('geo_location_location')) {
	function geo_location_location()
    {   
        global $Registry;
		return $Registry->geo_location->location ;
	}
}

if (! function_exists('geo_location_latitude')) {
	function geo_location_latitude()
    {   
        global $Registry;
		return $Registry->geo_location->latitude ;
	}
}

if (! function_exists('geo_location_longitude')) {
	function geo_location_longitude()
    {   
        global $Registry;
		return $Registry->geo_location->longitude ;
	}
}

if (! function_exists('geo_location_currency_code')) {
	function geo_location_currency_code()
    {   
        global $Registry;
		return $Registry->geo_location->currencyCode ;
	}
}

if (! function_exists('geo_location_currency_symbol')) {
	function geo_location_currency_symbol()
    {   
        global $Registry;
		return $Registry->geo_location->currencySymbol ;
	}
}

if (! function_exists('geo_location_currency_converter')) {
	function geo_location_currency_converter()
    {   
        global $Registry;
		return $Registry->geo_location->currencyConverter ;
	}
}


if (! function_exists('geo_location_convert_currency')) {
	function geo_location_convert_currency($amount, $float=2, $symbol=true)
    {   
        global $Registry;
		return $Registry->geo_location->convertCurrency($amount, $float, $symbol);
	}
}

if (! function_exists('geo_location_nearby')) {
	function geo_location_nearby($radius=10, $limit=null)
    {   
        global $Registry;
		return $Registry->geo_location->nearby($radius, $limit);
	}
}