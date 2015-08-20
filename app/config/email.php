<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/** 
 * Email config 
 * 
 * @package		CodeNimbly
 * @subpackage  CodeNimbly
 * @category    Config
 * @since       Version 1.0
 */
 
/**
 * App Registered Email
 */
$config['email_info_email']         = 'info@domain.com'; 
$config['email_noreply_email']      = 'noreply@domain.com'; 
$config['email_error_log_email']    = 'error@domain.com';
$config['email_copyright_email']    = 'copyright@domain.com'; 

/**
 * App Email Settings.
 * see system/libraries/email.class.php AND system/libraries/phpmailer/ for 
 * more email configuration of Open Source PHPMailer
 */
if (SITE_ENV == 'production' || SITE_ENV == 'testing') { // PRODUCTION and TESTING Environment
    // PRODUCTION Environment Email Settings
    $config['smtp_echo_error']    = false; // Turn off failure error for production/ mode
    $config['smtp_host']          = 'smtp.domain.com'; 
    $config['smtp_user']          = 'noreply@domain.com'; //
    $config['smtp_pass']          = 'smtppass'; 
    $config['smtp_secure']        = ''; 
    $config['smtp_port']          = 587; 
    $config['smtp_auth']          = true; 
    // Alternative smtp server, using GMAIL:
    $config['alt_smtp_host']      = 'smtp.gmail.com'; // for GMAIL smtp host
    $config['alt_smtp_user']      = 'username.mail@gmail.com'; //gmail email
    $config['alt_smtp_pass']      = 'gmailpass'; // gmail password
    $config['alt_smtp_secure']    = 'tls'; //'tls' for GMAIl.  Sets connection prefix. Options are "", "ssl" or "tls"
    $config['alt_smtp_port']      = 587; // 587 for GMAIl.  
    $config['alt_smtp_auth']      = true; // Authenticate SMTP

} else {
    // DEVELOPMENT Environment Email Settings
    $config['smtp_echo_error']    = true; // Turn on failre error for development mode
    $config['smtp_host']          = '127.0.0.1';  
    $config['smtp_user']          = '';  
    $config['smtp_pass']          = ''; 
    $config['smtp_secure']        = '';  
    $config['smtp_port']          = 25; 
    $config['smtp_auth']          = true; //
     // Alternative smtp server
    $config['alt_smtp_host']      = 'localhost';  
    $config['alt_smtp_user']      = '';  
    $config['alt_smtp_pass']      = ''; 
    $config['alt_smtp_secure']    = '';  
    $config['alt_smtp_port']      = 25; 
    $config['alt_smtp_auth']      = true; //
}

$config['email_from_email']         = $config['email_info_email'];
$config['email_from_name']          = $config['name'];  
$config['email_reply_to_email']     = $config['email_info_email'];
$config['email_reply_to_name']      = $config['name'];

