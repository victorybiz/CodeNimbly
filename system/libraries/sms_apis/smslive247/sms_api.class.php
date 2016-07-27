<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
 /**
 * SMS (Class) API Library  for SMSLive247.com API
 * 
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @since       Version 1.0
 */
class SMS_API {	
    public $api = 'smslive247';
    
    public $sms_config;
    
	public $api_url = 'http://www.smslive247.com/http/index.aspx'; // without the ? of querystring

	private $session_id = ''; // Authencation returned session ID      
    
    
    /**
	 * Class constructor
	 */
	public function __construct($sms_config, $api='')
	{	
        $this->sms_config =& $sms_config;     
        if (!empty($api)) {
            $this->api =  $api;
        }
        $api = $this->api;
        
        if ($sms_config['api']["$api"]['request_method'] == 'get') {
            /** create the required GET URL */       
            //e.g http://www.smslive247.com/http/index.aspx?cmd=login&owneremail=xxx&subacct=xxx &subacctpwd=xxx
            $url_for_get_method = $this->api_url . '?'
                                        . 'cmd=login'
                                        . '&owneremail=' . urlencode($sms_config['api']["$api"]['owner_email'])
                                        . '&subacct=' . urlencode($sms_config['api']["$api"]['sub_account'])
                                        . '&subacctpwd=' . urlencode($sms_config['api']["$api"]['sub_account_password']);
            //execute the url via GET
            $response = $this->_executeApiCommandsViaGet($url_for_get_method);
            
        } else {
            /** create data array to submit via POST method */                         
            $data_for_post_method =  array(
                                    'cmd' => 'login',
                                    'owneremail' => $sms_config['api']["$api"]['owner_email'],
                                    'subacct' => $sms_config['api']["$api"]['sub_account'],
                                    'subacctpwd' => $sms_config['api']["$api"]['sub_account_password']
            );
            //submit the data via POST
            $response = $this->_executeApiCommandsViaPost($this->api_url, $data_for_post_method);
        }       
        
        $response =  $this->_parseResponseMessage($response); 
        
        if ($response['status'] == 'OK') {            
            $this->session_id = $response['message']; //set the session id
            return true;            
        } else {    
            return $response['message'];
        } 
	}
    
    
	/**
     * Send a message. 
     * 
     * @param string $to_phone_no: The destination phone numbers in international format without + or space e.g: 2348057055555
     * @param string $message: Maximum of 160 characters for single message.
     * @param string $sender_id: Maximum of 11 characters or a phone number). This can be alphanumeric or just numbers
     * @return array MESSAGE RESULTS -or- ERROR DESCRIPTION 
     */
	public function sendMessage($to_phone_no, $message, $sender_id)
	{
	   $sms_config = $this->sms_config;
       $api = $this->api;
       
       $to_phone_no = preg_replace("/\+/", '', $to_phone_no); // remove any leading + sign
       
	   if (!empty($this->session_id)) {
            
            //GET request
            if ($sms_config['api']["$api"]['request_method'] == 'get') {                
                
                /** create the required GET URL */       
                //e.g http://www.smslive247.com/http/index.aspx?cmd=sendmsg&sessionid=xxx&message=xxx&sender=xxx&sendto=xxx&msgtype=0              
                $url_for_get_method = $this->api_url . '?'
                                    . 'cmd=sendmsg'
                                    . '&sessionid=' . urlencode($this->session_id)
                                    . '&message=' . urlencode($message)
                                    . '&sender=' . urlencode($sender_id)
                                    . '&sendto=' . urlencode($to_phone_no)
                                    . '&msgtype=' . urlencode(0);
                //execute the url via GET
                $response = $this->_executeApiCommandsViaGet($url_for_get_method);
                
            //POST Request  
            } else {
    
                /** create data array to submit via POST method */                         
                $data_for_post_method =  array(
                                        'cmd' => 'sendmsg',
                                        'sessionid' => $this->session_id,
                                        'message' => $message,
                                        'sender' => $sender_id,
                                        'sendto' => $to_phone_no,
                                        'msgtype' => 0
                );               
                //submit the data via POST
                $response = $this->_executeApiCommandsViaPost($this->api_url, $data_for_post_method);
            }   
            return $this->_parseResponseMessage($response);            
                           
        } else {
            
            $data['api'] = $this->api;
            $data['status'] = 'ERR';
            $data['message'] = "API Credentials  not valid.";
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
	   $status = trim(substr($response, 0 , 2)); // get error status
	   //split the response 
       $break_response_array  = explode(':', $response); //ERR: 402: Invalid password.Invalid Username or Password.
        
       if (! isset($break_response_array[1])) {        
            $data['status'] = 'ERR';
            $data['message'] = 'Unable to retrieve response from SMS Server.';
            
       } else {
            //get error number 
           $error_number = trim($break_response_array[1]);
           
           if ($status == 'OK') {            
                $data['status'] = 'OK';
                $data['message'] = trim($break_response_array[1]); //take the real data part OK: 6d76d8f-d7d7d55dd-d9d8d7d-66                
           } else {                
               switch ($error_number){ 
                	case '0':
                        $data['status'] = 'OK';
                        $data['message'] = 'Message Sent.';
                        break;                
                	case '100':
                        $data['status'] = 'ERR';
                        $data['message'] = 'General Error: Errors occurred trying to perform the requested action.';
                	   break;                
                	case '401':
                        $data['status'] = 'ERR';
                        $data['message'] = 'Invalid Session ID.';
                        break;                    
                    case '402':
                        $data['status'] = 'ERR';
                        $data['message'] = 'Invalid Sub-Account or Password.';
                        break;                        
                    case '403':
                        $data['status'] = 'ERR';
                        $data['message'] = 'Invalid Recharge Voucher code.';
                        break;                        
                    case '404':
                        $data['status'] = 'ERR';
                        $data['message'] = 'Insufficient credit to complete request.';
                        break;                        
                    case '405':
                        $data['status'] = 'ERR';
                        $data['message'] = 'Recharge Voucher is disabled.';
                        break;                        
                    case '406':
                        $data['status'] = 'ERR';
                        $data['message'] = 'Recharge Voucher is already used.';
                        break;                        
                    case '407':
                        $data['status'] = 'ERR';
                        $data['message'] = 'Forbidden/Access Denied.';
                        break;                        
                    case '408':
                        $data['status'] = 'ERR';
                        $data['message'] = 'Invalid Range: Data supplied is not in expected format.';
                        break;                        
                    case '409':
                        $data['status'] = 'ERR';
                        $data['message'] = 'Gateway Error.';
                        break;                        
                    case '410':
                        $data['status'] = 'ERR';
                        $data['message'] = 'Account is disabled.';
                        break;                        
                    default;
                        $data['status'] = 'ERR';
                        $data['message'] = trim($break_response_array[1]);
                }        
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