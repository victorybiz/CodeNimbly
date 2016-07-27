<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
 /**
 * SMS API wrapper class
 * 
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @since       Version 1.0
 */
class SMS {
    
    protected $registry;
    
    /**
	 * Class constructor
	 */
	public function __construct()
	{	
        global $Registry; // use core global variable $Registry
        $this->registry =& $Registry;
        
        
         //Load the config file 
        $this->registry->loadConfig('sms');
	}
	
    
	/**
     * Send a message. 
     */
    public function sendMessage($to_phone_no, $message, $country_iso_code='', $sender_id='')
    {
        $sms_config = $this->registry->config->get('sms');
        //select API to use
        if (isset($sms_config['country_api']["$country_iso_code"]) && !empty($sms_config['country_api']["$country_iso_code"])){
            $api = trim($sms_config['country_api']["$country_iso_code"]); //use country specific api
        } else {
            $api = trim($sms_config['global_api']);// use global api
        }
        
        if (!empty($sender_id)) {
            $sender_id = trim($sender_id);
        } elseif(isset($sms_config['api']["$api"]['sender_id']) && !empty($sms_config['api']["$api"]['sender_id'])) {
            $sender_id = trim($sms_config['api']["$api"]['sender_id']);
        } else {
            $sender_id = trim($sms_config['sender_id']);
        }
        
        $api_path =  dirname( __FILE__ ) . "/sms_apis/$api/sms_api.class.php";
        if (!file_exists($api_path)) {
             die ("<strong>$api</strong> SMS API class not found in <strong>$api_path</strong>");
        } else {
            require_once ($api_path);
            $SMS_API = new SMS_API($sms_config, $api);
            return $SMS_API->sendMessage($to_phone_no, $message, $sender_id);          
        }  
          
    }//end of function sendMessage()
    
} // End of class