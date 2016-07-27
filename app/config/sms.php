<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");

/**
 * SMS API Configuration
 */
$config['sms']['sender_id']                                         = 'TheSender';
$config['sms']['global_api']                                        = 'infobip'; //API used for global sending

$config['sms']['country_api']['NG']                                 = 'infobip'; //specific country API


// Infobip.com API Credentials
$config['sms']['api']['infobip']['sender_id']                       = $config['sms']['sender_id'];
$config['sms']['api']['infobip']['username']                        = '';
$config['sms']['api']['infobip']['password']                        = '';
$config['sms']['api']['infobip']['transfer_method']                 = 'curl'; //'curl' or 'fopen'
$config['sms']['api']['infobip']['request_method']                  = 'get'; //'post' or 'get'

// SMSLive247.com API Credentials
$config['sms']['api']['smslive247']['sender_id']                    = $config['sms']['sender_id'];
$config['sms']['api']['smslive247']['owner_email']                  = '';
$config['sms']['api']['smslive247']['sub_account']                  = '';
$config['sms']['api']['smslive247']['sub_account_password']         = '';
$config['sms']['api']['smslive247']['transfer_method']              = 'curl'; //'curl' or 'fopen'
$config['sms']['api']['smslive247']['request_method']               = 'post'; //'post' or 'get'

// Twilio.com API Credentials (NOT YET IMPLEMENTED)
//$config['sms']['api']['twilio']['sender_id']                        = '+177288483229';
//$config['sms']['api']['twilio']['api_key']                          = '';
//$config['sms']['api']['twilio']['api_secret']                       = '';
//$config['sms']['api']['twilio']['transfer_method']                  = 'curl'; //'curl' or 'fopen'
//$config['sms']['api']['twilio']['request_method']                   = 'post'; //'post' or 'get'