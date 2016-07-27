<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");

$lib_path =  dirname( __FILE__ ) . '/phpmailer/PHPMailerAutoload.php';
if (file_exists($lib_path)) {
    require_once ($lib_path);
} else {
    die ("PHPMailer library not found, please install PHPMailer to <strong>$lib_path</strong>");
}   

 /**
 * Email Class
 *
 * Email library extending PHPMailer
 * 
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @since       Version 1.0
 */
class Email extends PHPMailer {
    
    private $registry;
    
    public function __construct() {
        parent::__construct();        
        
        // initialise global variable
        global $Registry; //use core global variable $Registry
        
        $this->registry             = $Registry;
        
    }       
    
    /**
     * Process/Exexute Send Email
     * @return bool
     */
    private function executeSend($send_smtp_error_log=true)  
    {        
        $this->XMailer      = $this->registry->config->get('name') . ' Mail'; //Set the X-Mailer Header Content
        $this->IsSMTP();   // set mailer to use SMTP
        $this->SMTPDebug    = 0; 
        $this->SMTPAuth     = $this->registry->config->get('smtp_auth');  // turn on SMTP authentication
        $this->Host         = $this->registry->config->get('smtp_host');     
        $this->Username     = $this->registry->config->get('smtp_user'); 
        $this->Password     = $this->registry->config->get('smtp_pass');
        $this->Port         = $this->registry->config->get('smtp_port');  
        $this->SMTPSecure   = $this->registry->config->get('smtp_secure');
        
        //send
        if ($this->Send()) {
            return true;
        } else {
            
            $echo_error = $this->registry->config->get('smtp_echo_error');
    
            $first_failed_error = $this->ErrorInfo;
            
            if ($echo_error === true) {                
                echo 'Mail Error: '. $first_failed_error;
            }
                
            //if failed, try sending using alternative GMAIL smtp server            
            $this->SMTPAuth     = $this->registry->config->get('alt_smtp_auth');  // turn on SMTP authentication
            $this->Host         = $this->registry->config->get('alt_smtp_host');     
            $this->Username     = $this->registry->config->get('alt_smtp_user'); 
            $this->Password     = $this->registry->config->get('alt_smtp_pass');
            $this->Port         = $this->registry->config->get('alt_smtp_port');  
            $this->SMTPSecure   = $this->registry->config->get('alt_smtp_secure');       
            if ($this->Send()) {
                
                if ($send_smtp_error_log === true) {
                    //then send error log email to notify admin of first server failure
                    $this->sendErrorLogEmail('First SMTP Server Failed!', "First SMTP Server Failed:-: $first_failed_error");
                }                    
                return true;
            } else {
                
                if ($echo_error === true) {
                    echo 'Alt Mail Error: '. $this->ErrorInfo;
                }
                return false;
            }
        }
    } 
    
    public function sendEmail($to_email, $to_name='', $subject, $html_body, $text_body = '', $from = '', $from_name = '', $reply_to = '', $reply_to_name = '')
    {        
        $this->ClearAllRecipients();
        $this->ClearReplyTos();
        $this->From = $from;
        $this->FromName = $from_name;        
        $this->Subject = $subject;
        $this->AddAddress($to_email, $to_name);
        $this->AddReplyTo($reply_to, $reply_to_name);
        $this->Body = $html_body;
        if (!empty($text_body)) {
            $this->AltBody = $text_body; 
        }        
        // call to Send email  
        return $this->executeSend();
    } 
    
    public function sendErrorLogEmail($subject, $error_message, $append_action_location_signature=true) 
    {
        
        $to_email = $this->registry->config->get('email_error_log_email');
        $to_name = $this->registry->config->get('email_from_name');  
        
        $html_body = $error_message; 
        $text_body = $error_message; 
        
        $this->ClearAllRecipients();
        $this->ClearReplyTos();
        $this->From = $this->registry->config->get('email_from_email');
        $this->FromName = $this->registry->config->get('name') . " Server Error Handler"; 
        $this->Subject = $subject;
        $this->AddAddress($to_email, $to_name);
        $this->AddReplyTo($this->From, $this->FromName);
        $this->Body = $html_body;
        if (!empty($text_body)) {
            $this->AltBody = $text_body; 
        }        
        // call to Send email  
        return $this->executeSend(false);
    } 
    
}