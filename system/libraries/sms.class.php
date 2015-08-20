<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
 /**
 * SMS (Class) API Library  for SMSLive247.com API
 *
 * Password hashing and encryption library
 * 
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @since       Version 1.0
 */
class SMS {
    
    /**
     * Owners account email    
     */
	private $owner_email = '';
    
    /**
     * SUB account name     
     */
	private $sub_account = '';
    
    /**
     * SUB account password       
     */
	public $sub_account_password = '';
    
    /**
     * API URL      
     * e.g 'http://www.smslive247.com/http/index.aspx' without the ? of querystring
     */
	public $api_url = 'http://www.smslive247.com/http/index.aspx';
    
    /**
     * Authencation returned session ID      
     */
	private $session_id = '';
    
    /**
     * API Command execution via ('curl' or 'fopen') 
     */
    private $execute_api_commands_via = 'curl'; //'fopen' or 'curl'
    
    /**
     * Submit data API via ('post' or 'get') 
     */
    private $submit_api_data_via = 'post'; //'post' or 'get'
	
	
    
    /**
     * Login authentication; In order to deliver a message, the system needs to 
     * authenticate the request as coming from a valid source. 
     * On successful login, a sessionID value is set. This 
     * sessionID must be used with all future commands to the API, except the send_quick_message().
     * 
     * @param string Owner's email
     * @param string SUB account name
     * @param string SUB account password
     * @param string API Execute API commands via  [fopen / curl]
     * @param string Submit API data via ['post' / 'get'] 
     * @return mixed TRUE -or- ERROR DESCRIPTION
     */
	public function login($owner_email = '', $sub_account = '', $sub_account_password = '', $execute_api_commands_via = 'curl', $submit_api_data_via = 'post')
	{
	   
        if (! empty($owner_email)) {
            $this->owner_email = $owner_email; //sent new one or used default set in the class variable above
        }
        if (! empty($sub_account)) {
            $this->sub_account = $sub_account; //sent new one or used default set in the class variable above
        }
        if (! empty($sub_account_password)) {
            $this->sub_account_password = $sub_account_password; //sent new one or used default set in the class variable above
        }
        if (! empty($execute_api_commands_via)) {
            $this->execute_api_commands_via = $execute_api_commands_via; //sent new one or used default set in the class variable above
        }
        if (! empty($submit_api_data_via)) {
            $this->submit_api_data_via = $submit_api_data_via; //sent new one or used default set in the class variable above
        }
        
        if ($this->submit_api_data_via == 'get') {
            /** create the required GET URL */       
            //e.g http://www.smslive247.com/http/index.aspx?cmd=login&owneremail=xxx&subacct=xxx &subacctpwd=xxx
            $url_for_get_method = $this->api_url . '?'
                                        . 'cmd=login'
                                        . '&owneremail=' . urlencode($this->owner_email)
                                        . '&subacct=' . urlencode($this->sub_account)
                                        . '&subacctpwd=' . urlencode($this->sub_account_password);
            //execute the url via GET
            $response = $this->_execute_api_commands_via_get($url_for_get_method);
            
        } else {

            /** create data array to submit via POST method */                         
            $data_for_post_method =  array(
                                    'cmd' => 'login',
                                    'owneremail' => $this->owner_email,
                                    'subacct' => $this->sub_account,
                                    'subacctpwd' => $this->sub_account_password
            );
            //submit the data via POST
            $response = $this->_execute_api_commands_via_post($data_for_post_method);
        }       
        
        if ($response['status'] == 'OK') {
            
            $this->session_id = $response['message']; //set the session id
            return TRUE;
            
        } else {
    
            return $response['message'];
        }       
	}//end of function login()
    
    
	/**
     * Send a message. 
     * 
     * This require authentication session_id i.e a call to login() function before this function
     * 
     * @param string Sender (Maximum of 11 characters or a phone number). The FROM part of the SMS can be alphanumeric or just numbers
     * @param string The destination phone numbers coma separated for multiple numbers. e.g: 2348057055555,4470989777,913245678
     * @param string The SMS Message you wish to send. Maximum of 160 characters.
     * @param integer The type of the message either TEXT = 0 or FLASH = 1.
     * @param integer Unix Timestamp message should be sent. If you ignore this parameter or put in a date that is in the past, the message would be sent immediately.
     * @return array MESSAGEID -or- ERROR DESCRIPTION 
     */
	public function send_message($sender, $send_to, $message, $message_type = 0, $send_time = '')
	{
       
	   if (! empty($this->session_id)) {	       
            
            if ($this->submit_api_data_via == 'get') {
                /** create the required GET URL */       
                //e.g //http://www.smslive247.com/http/index.aspx?cmd=sendmsg&sessionid=xxx&message=xxx&sender=xxx&sendto=xxx&msgtype=0
                $url_for_get_method = $this->api_url . '?'
                                    . 'cmd=sendmsg'
                                    . '&sessionid=' . urlencode($this->session_id)
                                    . '&message=' . urlencode($message)
                                    . '&sender=' . urlencode($sender)
                                    . '&sendto=' . urlencode($send_to)
                                    . '&msgtype=' . urlencode($message_type);
                                    
                                    //schedule message if specified
                                    if (! empty($send_time)) {                                    
                                        //convert timestamp to UTC/GMT Date and Time format e.g “2 Jan 2008 6:30 PM” or “22 Dec 2009 22:31
                                        $send_time = date("j M Y g:i A", $send_time);
                                        //append to url
                                        $url .= '&sendtime=' . urlencode($send_time);
                                    }
                //execute the url via GET
                $response = $this->_execute_api_commands_via_get($url_for_get_method);
                
            } else {
    
                /** create data array to submit via POST method */                         
                $data_for_post_method =  array(
                                        'cmd' => 'sendmsg',
                                        'sessionid' => $this->session_id,
                                        'message' => $message,
                                        'sender' => $sender,
                                        'sendto' => $send_to,
                                        'msgtype' => $message_type
                );
                        //schedule message if specified
                        if (! empty($send_time)) {                                    
                            //convert timestamp to UTC/GMT Date and Time format e.g “2 Jan 2008 6:30 PM” or “22 Dec 2009 22:31
                            $send_time = date("j M Y g:i A", $send_time);
                            //add to data to array
                            $data_for_post_method['sendtime'] = $send_time;
                        }                
                //submit the data via POST
                $response = $this->_execute_api_commands_via_post($data_for_post_method);
            }       
                       
            return $response;            
                           
        } else {
            
            $data['status'] = 'ERR';
            $data['message'] = "Please call login method first to authenticate session.";
            return $data;
            
        }//end if (! empty($this->session_id)) {
    }//end of function send_message()
    
    
    /**
     * Send a quick message. 
     * 
     * This do not require call to login() for authentication session id
     * 
     * @param string Owner's email
     * @param string SUB account name
     * @param string SUB account password
     * @param string Execute API commands via  [fopen / curl]
     * @param string Submit API data via ['post' / 'get']
     * @param string Sender (Maximum of 11 characters or a phone number). The FROM part of the SMS can be alphanumeric or just numbers
     * @param string The destination phone numbers coma separated for multiple numbers. e.g: 2348057055555,4470989777,913245678
     * @param string The SMS Message you wish to send. Maximum of 160 characters.
     * @param integer The type of the message either TEXT = 0 or FLASH = 1.
     * @param string The UTC/GMT Date and Time when the message should be sent. A valid format is “2 Jan 2008 6:30 PM” or “22 Dec 2009 22:31”. If you ignore this parameter or put in a date that is in the past, the message would be sent immediately.
     * @return array MESSAGEID  -or- ERROR DESCRIPTION 
     */
    public function send_quick_message($owner_email = '', $sub_account = '', $sub_account_password = '', $execute_api_commands_via = 'curl', $submit_api_data_via = 'post', $sender, $send_to, $message, $message_type = 0, $send_time = '')
	{
	   if (! empty($execute_api_commands_via)) {
            $this->execute_api_commands_via = $execute_api_commands_via; //sent new one or used default set in the class variable above
        }  
        
        if ($this->submit_api_data_via == 'get') {
            /** create the required GET URL */       
            //e.g //http://www.smslive247.com/http/index.aspx?cmd=sendquickmsg&owneremail=xxx&subacct=xxx&subacctpwd=xxx&message=xxx&sender=xxx&sendto=xxx&msgtype=0
            $url_for_get_method = $this->api_url . '?'
                                . 'cmd=sendquickmsg'
                                . '&owneremail=' . urlencode($owner_email)
                                . '&subacct=' . urlencode($sub_account)
                                . '&subacctpwd=' . urlencode($sub_account_password)
                                . '&message=' . urlencode($message)
                                . '&sender=' . urlencode($sender)
                                . '&sendto=' . urlencode($send_to)
                                . '&msgtype=' . urlencode($message_type);
                                
                                //schedule message if specified
                                if (! empty($send_time)) {                                    
                                    //convert timestamp to UTC/GMT Date and Time format e.g “2 Jan 2008 6:30 PM” or “22 Dec 2009 22:31
                                    $send_time = date("j M Y g:i A", $send_time);
                                    //append to url
                                    $url .= '&sendtime=' . urlencode($send_time);
                                }
            //execute the url via GET
            $response = $this->_execute_api_commands_via_get($url_for_get_method);
            
        } else {

            /** create data array to submit via POST method */                         
            $data_for_post_method =  array(
                                    'cmd' => 'sendquickmsg',
                                    'owneremail' => $owner_email,
                                    'subacct' => $sub_account,
                                    'subacctpwd' => $sub_account_password,
                                    'message' => $message,
                                    'sender' => $sender,
                                    'sendto' => $send_to,
                                    'msgtype' => $message_type
            );
                    //schedule message if specified
                    if (! empty($send_time)) {                                    
                        //convert timestamp to UTC/GMT Date and Time format e.g “2 Jan 2008 6:30 PM” or “22 Dec 2009 22:31
                        $send_time = date("j M Y g:i A", $send_time);
                        //add to data to array
                        $data_for_post_method['sendtime'] = $send_time;
                    }                
            //submit the data via POST
            $response = $this->_execute_api_commands_via_post($data_for_post_method);
        }               
        return $response;            

    }//end of function send_quick_message()
    
    
    
    /**
     * Check number of sms credits available on this particular account.
     * 
     * This require authentication session_id i.e a call to login() function before this function
     * 
     * @return array CREDIT BALANCE -or- ERROR DESCRIPTION
     */
    public function get_account_balance()
	{	   
       if (! empty($this->session_id)) {
            
            if ($this->submit_api_data_via == 'get') {
                /** create the required GET URL */       
                //e.g http://www.smslive247.com/http/index.aspx?cmd=querybalance&sessionid=xxx
                $url_for_get_method = $this->api_url . '?'
                                            . 'cmd=querybalance'
                                            . '&sessionid=' . urlencode($this->session_id);
                //execute the url via GET
                $response = $this->_execute_api_commands_via_get($url_for_get_method);
                
            } else {
    
                /** create data array to submit via POST method */                         
                $data_for_post_method =  array(
                                        'cmd' => 'querybalance',
                                        'sessionid' => $this->session_id
                );
                //submit the data via POST
                $response = $this->_execute_api_commands_via_post($data_for_post_method);
            } 
                                               
            return $response;            
                           
        } else {
            
            $data['status'] = 'ERR';
            $data['message'] = "Please call login method first to authenticate session.";
            return $data;
            
        }//end if (! empty($this->session_id)) {
    }//end of function get_account_balance()
    
    /**
     * Check total sms credits charged for a delivered message.
     * 
     * This require authentication session_id i.e a call to login() function before this function
     * 
     * @param integer The MessageID returned by the send_message() or send_quick_message() methods when the message was sent
     * @return array CHARGE -or- ERROR DESCRIPTION
     */
    public function get_message_charge($message_id)
	{	   
       if (! empty($this->session_id)) {
	       
            if ($this->submit_api_data_via == 'get') {
                /** create the required GET URL */       
                //e.g http://www.smslive247.com/http/index.aspx?cmd=querymsgcharge&sessionid=xxx &messageid=xxx
                $url_for_get_method = $this->api_url . '?'
                                            . 'cmd=querymsgcharge'
                                            . '&sessionid=' . urlencode($this->session_id)
                                            . '&messageid=' . urlencode($message_id);
                //execute the url via GET
                $response = $this->_execute_api_commands_via_get($url_for_get_method);
                
            } else {
    
                /** create data array to submit via POST method */                         
                $data_for_post_method =  array(
                                        'cmd' => 'querymsgcharge',
                                        'sessionid' => $this->session_id,
                                        'messageid' => $message_id
                );
                //submit the data via POST
                $response = $this->_execute_api_commands_via_post($data_for_post_method);
            } 
                                               
            return $response;    
                           
        } else {
            
            $data['status'] = 'ERR';
            $data['message'] = "Please call login method first to authenticate session.";
            return $data;
            
        }//end if (! empty($this->session_id)) {
    }//end of function get_message_charge()
    
    /**
     * Check status of a message if delivered or failed or pending or rejected.
     * 
     * This require authentication session_id i.e a call to login() function before this function
     * 
     * @param integer The MessageID returned by the send_message() or send_quick_message() methods when the message was sent
     * @return array MESSAGE STATUS -or- ERROR DESCRIPTION
     */
    public function get_message_status($message_id)
	{       
       if (! empty($this->session_id)) {
	       
            if ($this->submit_api_data_via == 'get') {
                /** create the required GET URL */       
                //e.g http://www.smslive247.com/http/index.aspx?cmd=querymsgstaus&sessionid=xxx &messageid=xxx
                $url_for_get_method = $this->api_url . '?'
                                            . 'cmd=querymsgstaus'
                                            . '&sessionid=' . urlencode($this->session_id)
                                            . '&messageid=' . urlencode($message_id);
                //execute the url via GET
                $response = $this->_execute_api_commands_via_get($url_for_get_method);
                
            } else {
    
                /** create data array to submit via POST method */                         
                $data_for_post_method =  array(
                                        'cmd' => 'querymsgstaus',
                                        'sessionid' => $this->session_id,
                                        'messageid' => $message_id
                );
                //submit the data via POST
                $response = $this->_execute_api_commands_via_post($data_for_post_method);
            } 
                                               
            return $response;    
                           
        } else {
            
            $data['status'] = 'ERR';
            $data['message'] = "Please call login method first to authenticate session.";
            return $data;
            
        }//end if (! empty($this->session_id)) {
    }//end of function get_message_status()
    
    
    /**
     * Check our coverage of a network or mobile number, without sending a message to that number
     * This call should NOT be used before sending each message.
     * 
     * This require authentication session_id i.e a call to login() function before this function
     * 
     * @param integer Phone Number
     * @return array TRUE/FALSE -or- ERROR DESCRIPTION
     */
    public function check_coverage($phone_number)
	{       
       if (! empty($this->session_id)) {
            
            if ($this->submit_api_data_via == 'get') {
                /** create the required GET URL */       
                //e.g http://www.smslive247.com/http/index.aspx?cmd=querycoverage&sessionid=xxx&msisdn=xxx
                $url_for_get_method = $this->api_url . '?'
                                            . 'cmd=querycoverage'
                                            . '&sessionid=' . urlencode($this->session_id)
                                            . '&msisdn=' . urlencode($phone_number);
                //execute the url via GET
                $response = $this->_execute_api_commands_via_get($url_for_get_method);
                
            } else {
    
                /** create data array to submit via POST method */                         
                $data_for_post_method =  array(
                                        'cmd' => 'querycoverage',
                                        'sessionid' => $this->session_id,
                                        'msisdn' => $phone_number
                );
                //submit the data via POST
                $response = $this->_execute_api_commands_via_post($data_for_post_method);
            } 
                                               
            return $response;        
                           
        } else {
            
            $data['status'] = 'ERR';
            $data['message'] = "Please call login method first to authenticate session.";
            return $data;
            
        }//end if (! empty($this->session_id)) {
    }//end of function check_coverage()
    
    /**
     * Top-up the SMSLive247 sub-account using a purchased recharge voucher, and returns the new balance. 
     * The recharge voucher code is currently a 16 digit number.
     * 
     * This require authentication session_id i.e a call to login() function before this function
     * 
     * @param integer The 16 Digit recharge code
     * @return array NEW BALANCE] -or- ERROR DESCRIPTION
     */
    public function recharge_account($recharge_code)
	{
       if (! empty($this->session_id)) {

            if ($this->submit_api_data_via == 'get') {
                /** create the required GET URL */       
                //e.g http://www.smslive247.com/http/index.aspx?cmd=recharge&sessionid=xxx&rcode=xxx
                $url_for_get_method = $this->api_url . '?'
                                            . 'cmd=recharge'
                                            . '&sessionid=' . urlencode($this->session_id)
                                            . '&rcode=' . urlencode($recharge_code);
                //execute the url via GET
                $response = $this->_execute_api_commands_via_get($url_for_get_method);
                
            } else {
    
                /** create data array to submit via POST method */                         
                $data_for_post_method =  array(
                                        'cmd' => 'recharge',
                                        'sessionid' => $this->session_id,
                                        'rcode' => $recharge_code
                );
                //submit the data via POST
                $response = $this->_execute_api_commands_via_post($data_for_post_method);
            } 
                                               
            return $response;        
                           
        } else {
            
            $data['status'] = 'ERR';
            $data['message'] = "Please call login method first to authenticate session.";
            return $data;
            
        }//end if (! empty($this->session_id)) {
    }//end of function recharge_account()
    
    
    
    /**
     * Stop the delivery of a scheduled message. 
     * This command can only stop messages which maybe queued within our router, and not messages which have already been delivered to a SMSC. 
     * This command is therefore only really useful for messages with deferred delivery times.
     * 
     * This require authentication session_id i.e a call to login() function before this function
     * 
     * @param integer The MessageID returned by the send_message() or send_quick_message() methods when the message was sent
     * @return array TRUE/FALSE -or- ERROR DESCRIPTION
     */
    public function stop_message($message_id)
	{       
       if (! empty($this->session_id)) {

            if ($this->submit_api_data_via == 'get') {
                /** create the required GET URL */       
                //e.g http://www.smslive247.com/http/index.aspx?cmd=stopmsg&sessionid=xxx&messageid=xxx
                $url_for_get_method = $this->api_url . '?'
                                            . 'cmd=stopmsg'
                                            . '&sessionid=' . urlencode($this->session_id)
                                            . '&messageid=' . urlencode($message_id);
                //execute the url via GET
                $response = $this->_execute_api_commands_via_get($url_for_get_method);
                
            } else {
    
                /** create data array to submit via POST method */                         
                $data_for_post_method =  array(
                                        'cmd' => 'stopmsg',
                                        'sessionid' => $this->session_id,
                                        'messageid' => $message_id
                );
                //submit the data via POST
                $response = $this->_execute_api_commands_via_post($data_for_post_method);
            } 
                                               
            return $response;   
                           
        } else {
            
            $data['status'] = 'ERR';
            $data['message'] = "Please call login method first to authenticate session.";
            return $data;
            
        }//end if (! empty($this->session_id)) {
    }//end of function stop_message()
    
    /**
     * Get sent messages. 
     * Paging is used so that you return messages in batches instead of all at once. 
     * This is very useful when the messages returned are much and may slow down processing and consume bandwidth
     * 
     * This require authentication session_id i.e a call to login() function before this function
     * 
     * @param integer PageSize is the number of rows to return at once. Minimum value is 5 and maximum is 300, default is 5.Messages are returned in “pages”. 
     * @param integer PageNumber is the current “page” of rows to return. Default value is 1. If PageSize is 100 and PageNumber is 3 then rows 201 to 300 is returned
     * @param integer Unix Timestamp - BeginDate is the first date. Search Messages that fall between a date range.  This parameter is required.
     * @param integer Unix Timestamp - EndDate is the second date. Search Messages that fall between a date range.  This parameter is required.
     * @param string SenderID, You may choose to return Messages that have a specific SenderID. Leave blank if you don’t care about specific SenderID.
     * @param string Message content, You may choose to return Messages that have a specific content. Leave blank if you don’t care about specific text.
     * @return [TOTALROWS] [RAW DATA RETURNED AS XML/XLS/CSV] -or- [ERROR DESCRIPTION]
     */
    public function get_sent_message($page_size = 5, $page_number = 1, $begin_date = '', $end_date = '', $sender_id = '', $contains_content = '')
	{
        if (! empty($this->session_id)) {                
            
            if ($this->submit_api_data_via == 'get') {
                /** create the required GET URL */       
                //e.g http://www.smslive247.com/http/index.aspx?cmd=getsentmsgs&sessionid=xxx&pagesize=xxx&pagenumber=xxx&begindate=xxx&enddate=xxx&sender=xxx&contains=xxx
                $url_for_get_method = $this->api_url . '?'
                                            . 'cmd=getsentmsgs'
                                            . '&sessionid=' . urlencode($this->session_id)
                                            . '&pagesize=' . urlencode($page_size)
                                            . '&pagenumber=' . urlencode($page_number)
                                            . '&begindate=' . urlencode(date("j M Y g:i A", $begin_date)) //convert timestamp to UTC/GMT Date and Time format e.g “2 Jan 2008 6:30 PM” or “22 Dec 2009 22:31
                                            . '&enddate=' . urlencode(date("j M Y g:i A", $end_date)); //convert timestamp to UTC/GMT Date and Time format e.g “2 Jan 2008 6:30 PM” or “22 Dec 2009 22:31
                                        
                                        if (! empty($sender_id)) {                
                                            //append to url if any
                                            $url .= '&sender=' . urlencode($sender);
                                        }
                                        if (! empty($contains_content)) {
                                            //append to url if any
                                            $url .= '&contains=' . urlencode($contains_content);
                                        }
                //execute the url via GET
                $response = $this->_execute_api_commands_via_get($url_for_get_method);
                
            } else {
    
                /** create data array to submit via POST method */                         
                $data_for_post_method =  array(
                                        'cmd' => 'getsentmsgs',
                                        'sessionid' => $this->session_id,
                                        'pagesize' => $page_size,
                                        'pagenumber' => $page_number,
                                        'begindate' => urlencode(date("j M Y g:i A", $begin_date)), //convert timestamp to UTC/GMT Date and Time format e.g “2 Jan 2008 6:30 PM” or “22 Dec 2009 22:31
                                        'enddate' => urlencode(date("j M Y g:i A", $end_date))   //convert timestamp to UTC/GMT Date and Time format e.g “2 Jan 2008 6:30 PM” or “22 Dec 2009 22:31
                );
                                if (! empty($sender_id)) {                                    
                                    //add to data to array if any
                                    $data_for_post_method['sender'] = $sender;
                                } 
                                if (! empty($contains_content)) {                                    
                                    //add to data to array if any
                                    $data_for_post_method['contains'] = $contains_content;
                                }                                         
                //submit the data via POST
                $response = $this->_execute_api_commands_via_post($data_for_post_method);
            } 
                                               
            return $response;           
                           
        } else {
            
            $data['status'] = 'ERR';
            $data['message'] = "Please call login method first to authenticate session.";
            return $data;
            
        }//end if (! empty($this->session_id)) {
       
       //OK: [TOTALROWS] [RAW DATA RETURNED AS XML/XLS/CSV] -or- ERR: [ERROR NUMBER]: [ERROR DESCRIPTION]
       
       //Example
        //OK: 588
        //Interpreted as rows 101 to 200 returned out of 588 rows. Here the page size used is 100 and page number is 2.
    }//end of function get_sent_message()
    

    /**
     * Do backend execution of the API commands via GET method with fopen or CURL
     * This should not be called directly but from a member function of this class
     * 
     * @param array The post data ... this can be form data e.g array('sender'=>'Alert', 'sendto'=> '080567898,23480564333')...
     * @return string Delivery status or Error Status
     */
    private function _execute_api_commands_via_post($post_data)
	{
	   if (($this->execute_api_commands_via == 'curl') &&  (function_exists('curl_init') == TRUE)){
	       
           // create a new cURL resource
            $resource_ch = curl_init();           
            // set appropriate options
            curl_setopt($resource_ch, CURLOPT_URL, $this->api_url);
            curl_setopt($resource_ch, CURLOPT_HEADER, FALSE);
            curl_setopt($resource_ch, CURLOPT_RETURNTRANSFER, TRUE); //allow it to return transfer response
            curl_setopt($resource_ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($resource_ch, CURLOPT_POST, TRUE); //send via HTTP POST Method
            curl_setopt($resource_ch, CURLOPT_POSTFIELDS, $post_data); //Array of data to send            
            // grab URL and pass it to the browser and get the return transfer
            $response = curl_exec($resource_ch);           
            // close cURL resource, and free up system resources
            curl_close($resource_ch);
            
            $data = $this->_get_response_message($response); //format response message
            return $data;
	       
	   } elseif (($this->execute_api_commands_via == 'fopen') &&  (ini_get('allow_url_fopen') == TRUE)) {
           
            $data['status'] = 'ERR';
            $data['message'] = "You can only send GET method data via 'fopen', please use cURL (Client URL) Library to send POST method data.";
            return $data;
	       
	   } else {
	       
	       $data['status'] = 'ERR';
           $data['message'] = "Please enable 'allow_url_fopen' or cURL (Client URL) library";
	       return $data;
	   } 
    }//end of function _execute_api_commands_via_get()

    
    /**
     * Do backend execution of the API commands via GET method with fopen or CURL
     * This should not be called directly but from a member function of this class
     * 
     * @param string URL
     * @return string Delivery status or Error Status
     */
    private function _execute_api_commands_via_get($url)
	{
	   if (($this->execute_api_commands_via == 'curl') &&  (function_exists('curl_init') == TRUE)){
	       
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
            
            $data = $this->_get_response_message($response); //format response message
            return $data;
	       
	   } elseif (($this->execute_api_commands_via == 'fopen') &&  (ini_get('allow_url_fopen') == TRUE)) {
	       
           /* call the URL */
           if ($fopen_handle = @fopen($url, "r")) { 
                $response = fgets($fopen_handle, 255);
                if (substr($response, 0, 1) == "+") {
                    
                    $data = $this->_get_response_message($response); //format response message
                    return $data;
                                    
                } else {
                    
                    $data = $this->_get_response_message($response); //format response message
                    return $data;
                }
                fclose($fopen_handle);
                
            } else {
                
                $data['status'] = 'ERR';
                $data['message'] = "Error: URL could not be opened.";
                return $data;
            }
	       
	   } else {
	       
	       $data['status'] = 'ERR';
           $data['message'] = "Please enable 'allow_url_fopen' or cURL (Client URL) library";
	       return $data;
	   } 
    }//end of function _execute_api_commands_via_get()
    
    
    /**
     * Format the returned api message and set custom message 
     * This should not be called directly but from a member function of this class
     * 
     * @param string Response message sting
     * @return string Formated response message
     */
    private function _get_response_message($response_message)
	{ 
	   
       $status = trim(substr($response_message, 0 , 2)); // get error status
       
	   //split the response message
       $break_response_array  = explode(':', $response_message); //ERR: 402: Invalid password.Invalid Username or Password.
        
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
                        $data['message'] = 'Successful.';
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
        return $data;  
    }//end of function _get_response_message()
    
    
    /**
     * Has not been implemeted
     */    
    public function retrieve_callback()
	{
	   /**
        MessageID
        The Message identifier for the batch message. Numeric value.
        23456
        
        TotalSent
        Total messages delivered to mobile subscribers.
        100
        
        TotalFailed
        Total messages failed.
        3
        
        TotalCharged
        Total credits charged for the bulk messages sent. This value may be greater than TotalSent since some networks charge more than others.
        126
        
        Balance
        The remaining credit balance on the Sub-Account.
        550
        
        Status
        The batch message status.
        COMPLETED, PAUSED, ABORTED
        
        */
    }
    
    
} // end of class