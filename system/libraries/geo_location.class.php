<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
 /**
 * Geo Location Class
 * 
 * This class uses the PHP Webservice of http://www.geoplugin.com/ to geolocate IP addresses
 * Geographical location of the IP address (visitor) and locate currency (symbol, code and exchange rate) are returned.
 * See http://www.geoplugin.com/webservices/php for more specific details of this free service
 * 
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @since       Version 1.0
 */
class Geo_Location {
    
   	//the geoPlugin server
	var $host = 'http://www.geoplugin.net/php.gp?ip={IP}&base_currency={CURRENCY}';
		
	//the default base currency
	var $currency = 'USD';
	
	//initiate the geoPlugin vars
	var $ip = null;
	var $city = null;
	var $region = null;
	var $areaCode = null;
	var $dmaCode = null;
	var $countryCode = null;
	var $countryName = null;
	var $continentCode = null;
	var $continentName = null;
	var $latitude = null;
	var $longitude = null;
	var $currencyCode = null;
	var $currencySymbol = null;
	var $currencyConverter = null;
      
	/**
     * Get user's IP
     * 
     * @param bool $deep_detect : Set TRUE to detect using both the user's client information. Default is 
     * @param string $set_localhost_ip_as : To avoid invalid local IP, Set an IP to display when running this class locally on your PC because localhost IP is ::1" OR  "127.0.0.1"
     * @return string
     */
	public function ip($deep_detect = true, $set_localhost_ip_as = '105.112.26.142') 
    {
        global $_SERVER;
        
        $ip = $_SERVER["REMOTE_ADDR"];
        $deep_detect = true;
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }        
        if ($ip == "::1" || $ip == "127.0.0.1") {
            $ip = $set_localhost_ip_as; 
        }
        $ip = trim($ip);
        return $ip;
	}
	
    
    /**
     * Set default base currency
     * 
     *@param string $currency_code
     *@return void
     */
    public function setDefaultBaseCurrency($currency_code = 'USD')
    {
        $this->currency = $currency_code;
    }
    
    
    /**
     * Locate user
     * 
     * @param string $ip : pass an IP address to locate. Default is NULL which detect and locate IP automatically
     * @param bool $deep_detect : Set TRUE to detect using both the user's client information. Default is TRUE
     * @return string
     */
    public function locate($ip = null, $deep_detect = true) {
		
		if ($ip === null || filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $this->ip($deep_detect);
        }
		
		$host = str_replace( '{IP}', $ip, $this->host );
		$host = str_replace( '{CURRENCY}', $this->currency, $host );
		
		$data = array();
		
		$response = $this->fetch($host);
		
		$data = unserialize($response);
		
		//set the geoPlugin vars
		$this->ip = $ip;
		$this->city = $data['geoplugin_city'];
		$this->region = $data['geoplugin_region'];
		$this->areaCode = $data['geoplugin_areaCode'];
		$this->dmaCode = $data['geoplugin_dmaCode'];
		$this->countryCode = $data['geoplugin_countryCode'];
		$this->countryName = $data['geoplugin_countryName'];
		$this->continentCode = $data['geoplugin_continentCode'];
		$this->latitude = $data['geoplugin_latitude'];
		$this->longitude = $data['geoplugin_longitude'];
		$this->currencyCode = $data['geoplugin_currencyCode'];
		$this->currencySymbol = $data['geoplugin_currencySymbol'];
		$this->currencyConverter = $data['geoplugin_currencyConverter'];
        
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        ;
        $this->continentName = @$continents[strtoupper($data['geoplugin_continentCode'])];
		
	}
	
    /**
     * Fetch URL data
     * 
     * @param string $host : The host URL
     * @return string
     */
	private function fetch($host) {

		if ( function_exists('curl_init') ) {
						
			//use cURL to fetch data
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $host);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, 'geoPlugin PHP Class v1.0');
			$response = curl_exec($ch);
			curl_close ($ch);
			
		} else if ( ini_get('allow_url_fopen') ) {
			
			//fall back to fopen()
			$response = file_get_contents($host, 'r');
			
		} else {

			trigger_error ('Geo Location class Error: Cannot retrieve data. Either compile PHP with cURL support or enable allow_url_fopen in php.ini ', E_USER_ERROR);
			return;
		
		}		
		return $response;
	}
	
    
    /** 
     * Convert currency
     * 
     * @param int $amount
     * @param int $float : decimal place precision
     * @param int $symbol : Set TRUE to return calculated amount currency smbol
     * @return mixed
     */
	public function convertCurrency($amount, $float=2, $symbol=true) {
		
		//easily convert amounts to geolocated currency.
		if ( !is_numeric($this->currencyConverter) || $this->currencyConverter == 0 ) {
			trigger_error('Geo Location class Notice: currencyConverter has no value.', E_USER_NOTICE);
			return $amount;
		}
		if ( !is_numeric($amount) ) {
			trigger_error ('Geo Location class Warning: The amount passed to Geo Location::convertCurrency is not numeric.', E_USER_WARNING);
			return $amount;
		}
		if ( $symbol === true ) {
			return $this->currencySymbol . round( ($amount * $this->currencyConverter), $float );
		} else {
			return round( ($amount * $this->currencyConverter), $float );
		}
	}
	
    /**
     * Find an array of nearby location information
     * @param int $radius
     * @param int $limit
     * @return array
     */
	public function nearby($radius=10, $limit=null) {

		if ( !is_numeric($this->latitude) || !is_numeric($this->longitude) ) {
			trigger_error ('Geo Location class Warning: Incorrect latitude or longitude values.', E_USER_NOTICE);
			return array( array() );
		}
		
		$host = "http://www.geoplugin.net/extras/nearby.gp?lat=" . $this->latitude . "&long=" . $this->longitude . "&radius={$radius}";
		
		if ( is_numeric($limit) )
			$host .= "&limit={$limit}";
			
		return unserialize( $this->fetch($host) );

	}  
    
}