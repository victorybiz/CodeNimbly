<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
 /**
 * SMS (Class) API Library  for Infobip.com API
 * 
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @since       Version 1.0
 */
class SMS_API {	
    public $api = 'infobip';
    
    public $sms_config;
    
	public $api_url = 'http://api2.infobip.com/api/sendsms/plain'; // without the ? of querystring
    
    
    /**
	 * Class constructor
	 */
	public function __construct($sms_config, $api='')
	{	
        $this->sms_config =& $sms_config;     
        if (!empty($api)) {
            $this->api =  $api;
        }      
	}

    
    /**
     * Send a message. 
     * 
     * @param string $to_phone_no: The destination phone numbers in international format without + or space e.g: 2348057055555
     * @param string $message: Maximum of 160 characters for single message.
     * @param string $sender_id: Maximum of 11 characters or a phone number). This can be alphanumeric or just numbers
     * @return 
     */
	public function sendMessage($to_phone_no, $message, $sender_id)
	{
	   $sms_config = $this->sms_config;
       $api = $this->api;
       
       $to_phone_no = preg_replace("/\+/", '', $to_phone_no); // remove any leading + sign
       
	   if (!empty($sms_config['api']["$api"]['username']) && !empty($sms_config['api']["$api"]['password'])) {
            
            //GET request
            if ($sms_config['api']["$api"]['request_method'] == 'get') {                
                
                /** create the required GET URL */       
                //e.g http://api2.infobip.com/api/sendsms/plain?user=xxx&password=yyy&type=LongSMS&sender=@@sender@@&GSM=@@2348136450319@@&SMSText=@@message@@
                $url_for_get_method = $this->api_url . '?'
                                    . 'user=' . urlencode($sms_config['api']["$api"]['username'])
                                    . '&password=' . urlencode($sms_config['api']["$api"]['password'])
                                    . '&type=' . urlencode('LongSMS')
                                    . '&sender=' . urlencode($sender_id)
                                    . '&GSM=' . urlencode($to_phone_no)
                                    . '&SMSText=' . urlencode($message);
                //execute the url via GET
                $response = $this->_executeApiCommandsViaGet($url_for_get_method);
                
            //POST Request  
            } else {
    
                /** create data array to submit via POST method */                         
                $data_for_post_method =  array(
                                        'user' => $sms_config['api']["$api"]['username'],
                                        'password' => $sms_config['api']["$api"]['password'],
                                        'type' => 'LongSMS',
                                        'sender' => $sender_id,
                                        'GSM' => $to_phone_no,
                                        'message' => $message
                );               
                //submit the data via POST
                $response = $this->_executeApiCommandsViaPost($this->api_url, $data_for_post_method);
            }   
            return $this->_parseResponseMessage($response);            
                           
        } else {
            
            $data['api'] = $this->api;
            $data['status'] = 'ERR';
            $data['message'] = "API Credentials  not set.";
            return $data;
            
        }//end if (! empty($this->session_id)) {
    }//end of function sendMessage()
    
    /**
     * Format the returned api message and set custom message 
     * 
     * @param string Response message sting
     * @return array Formated response message
     */
    private function _parseResponseMessage($response)
	{   
	   $response = trim($response);
       
        if ($response > 0 ) {        
            $data['status'] = 'OK';
            $data['message'] = 'Message Sent.';
            
        } else {
            
           switch ($response){             
            	case '-1':
                    $data['status'] = 'ERR';
                    $data['message'] = 'SEND_ERROR - Currently not in use';
            	   break;                
            	case '-2':
                    $data['status'] = 'ERR';
                    $data['message'] = 'NOT_ENOUGHCREDITS';
                    break;                    
                case '-3':
                    $data['status'] = 'ERR';
                    $data['message'] = 'NETWORK_NOTCOVERED';
                    break;                        
                case '-4':
                    $data['status'] = 'ERR';
                    $data['message'] = 'SOCKET_EXCEPTION - Currently not in use';
                    break;                        
                case '-5':
                    $data['status'] = 'ERR';
                    $data['message'] = 'INVALID_USER_OR_PASS';
                    break;                        
                case '-6':
                    $data['status'] = 'ERR';
                    $data['message'] = 'MISSING_DESTINATION_ADDRESS';
                    break;                        
                case '-7':
                    $data['status'] = 'ERR';
                    $data['message'] = 'MISSING_SMSTEXT';
                    break;                        
                case '-8':
                    $data['status'] = 'ERR';
                    $data['message'] = 'MISSING_SENDERNAME';
                    break;                        
                case '-9':
                    $data['status'] = 'ERR';
                    $data['message'] = 'DESTADDR_INVALIDFORMAT';
                    break;                        
                case '-10':
                    $data['status'] = 'ERR';
                    $data['message'] = 'MISSING_USERNAME';
                    break;                        
                case '-11':
                    $data['status'] = 'ERR';
                    $data['message'] = 'MISSING_PASS';
                    break;  
                case '-13':
                    $data['status'] = 'ERR';
                    $data['message'] = 'INVALID_DESTINATION_ADDRESS';
                    break;                      
                default;
                    $data['status'] = 'ERR';
                    $data['message'] = $response;
            }        
        }
        $data['api'] = $this->api;
        return $data; 
    }
 
      
    
    /**
     * Do backend execution of the API commands via GET method with fopen or CURL
     * This should not be called directly but from a member function of this class
     * 
     * @param array The post data ... this can be form data e.g array('sender'=>'Alert', 'to'=> '23480564333')...
     * @return string Delivery status or Error Status
     */
    private function _executeApiCommandsViaPost($api_url, $post_data)
	{
	   $sms_config = $this->sms_config;
       $api = $this->api;
       
	   if (($sms_config['api']["$api"]['transfer_method'] == 'curl') &&  (function_exists('curl_init') == TRUE)){
	       
           // create a new cURL resource
            $resource_ch = curl_init();           
            // set appropriate options
            curl_setopt($resource_ch, CURLOPT_URL, $api_url);
            curl_setopt($resource_ch, CURLOPT_HEADER, FALSE);
            curl_setopt($resource_ch, CURLOPT_RETURNTRANSFER, TRUE); //allow it to return transfer response
            curl_setopt($resource_ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($resource_ch, CURLOPT_POST, TRUE); //send via HTTP POST Method
            curl_setopt($resource_ch, CURLOPT_POSTFIELDS, $post_data); //Array of data to send            
            // grab URL and pass it to the browser and get the return transfer
            $response = curl_exec($resource_ch);           
            // close cURL resource, and free up system resources
            curl_close($resource_ch);
            
            return $data = $response;
	       
	   } elseif (($sms_config['api']["$api"]['transfer_method'] == 'fopen') &&  (ini_get('allow_url_fopen') == TRUE)) {
           
            $data['api'] = $this->api;
            $data['status'] = 'ERR';
            $data['message'] = "You can only send GET method data via 'fopen', please use cURL (Client URL) Library to send POST method data.";
            return $data;
	       
	   } else {
	       
           $data['api'] = $this->api;
	       $data['status'] = 'ERR';
           $data['message'] = "Please enable 'allow_url_fopen' or cURL (Client URL) library";
	       return $data;
	   } 
    }//end of function _executeApiCommandsViaGet()

    
    /**
     * Do backend execution of the API commands via GET method with fopen or CURL
     * This should not be called directly but from a member function of this class
     * 
     * @param string URL
     * @return string Delivery status or Error Status
     */
    private function _executeApiCommandsViaGet($url)
	{
	   $sms_config = $this->sms_config;
       $api = $this->api;
       
	   if (($sms_config['api']["$api"]['transfer_method'] == 'curl') &&  (function_exists('curl_init') == TRUE)){
	       
           // create a new cURL resource
            $resource_ch = curl_init();           
            // set appropriate options
            curl_setopt($resource_ch, CURLOPT_URL, $url);
            curl_setopt($resource_ch, CURLOPT_HEADER, FALSE);
            curl_setopt($resource_ch, CURLOPT_RETURNTRANSFER, TRUE); //allow it to return transfer response
            curl_setopt($resource_ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            // grab URL and pass it to the browser and get the return transfer
            $response = curl_exec($resource_ch);           
            // close cURL resource, and free up system resources
            curl_close($resource_ch);
            
            return $data = $response;
	       
	   } elseif (($sms_config['api']["$api"]['transfer_method'] == 'fopen') &&  (ini_get('allow_url_fopen') == TRUE)) {
	       
           /* call the URL */
           if ($fopen_handle = @fopen($url, "r")) { 
                $response = fgets($fopen_handle, 255);
                return $data = $response;
                fclose($fopen_handle);
                
            } else {
                
                $data['api'] = $this->api;
                $data['status'] = 'ERR';
                $data['message'] = "Error: URL could not be opened.";
                return $data;
            }
	       
	   } else {
	       
           $data['api'] = $this->api;
	       $data['status'] = 'ERR';
           $data['message'] = "Please enable 'allow_url_fopen' or cURL (Client URL) library";
	       return $data;
	   } 
    }//end of function _executeApiCommandsViaGet()
    
} // end of class