<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Authentication Class
 * 
 * Dependency: Auth_Model, Session library
 *
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @since       Version 1.0
 */
class Auth {
    
    protected $registry;
	
	/**
	 * Class constructor
     * load the session library from the Registry
	 */
	public function __construct()
	{	
        global $Registry; // use core global variable $Registry
        $this->registry =& $Registry;
        
        // check if Auth_Model has been registered and loaded if not then register and load it
        if (!isset($this->registry->auth_model) || !is_object($this->registry->auth_model)) {
            $this->registry->registerModel('Auth_Model', 'auth_model', true);            
            $Registry =& $this->registry;
        }
        // check if Session library has been registered and loaded if not then register and load it
        if (!isset($this->registry->session) || !is_object($this->registry->session)) {
            $this->registry->registerLibrary('Session', 'session', true);            
            $Registry =& $this->registry;
        }
	}
    
    /**
     * Current Page Url
     */
    public function currentPageUrl()
    {
        $url = $this->registry->config->get('site_url');        
        if (substr($url, -1, 1) == '/')  {
            $url = substr($url, 0, strlen($url)-1);
        }
        return $url . $_SERVER["REQUEST_URI"];
    }
    
    /**
     * Check Authentication
     * If user is not, then he will be redirected to login page and the application is hard-stopped via exit().
     * 
     * @return void
     */
    public function checkAuth($restrict_to_user_account_type = array())
    {
        // self::checkSessionConcurrency();

        // if user is NOT logged in...
        if (!$this->isUserLoggedIn()) {
            // ... then treat user as "not logged in", destroy session, redirect to login page
            $this->registry->session->destroy();

            // send the user to the login form page, but also add the current page's URI (the part after the base URL)
            // as a parameter argument, making it possible to send the user back to where he/she came from after a successful login
            header('location: ' . $this->registry->config->get('base_url') . 'login?returnUrl=' . urlencode($this->currentPageUrl()));
            exit();
        }
        // if user is logged in, then check for logged in user account type  against user account type restriction if any is specified
        if (!empty($restrict_to_user_account_type) && !in_array($this->userAccountType, $restrict_to_user_account_type)) {
            
            header('location: ' . $this->registry->config->get('base_url') . 'restricted-access?returnUrl=' . urlencode($this->currentPageUrl()));
            exit();
        }
    }
    
	
    /**
     * Checks if the user is logged in or not
     *
     * @return bool user's login status
     */
    public function isUserLoggedIn()
    {
        $user_logged_in = $this->registry->session->get('user_logged_in');
        return (!empty($user_logged_in)) ? true : false;
    }
    
    /**
     * Detects if there is concurrent session(i.e. another user logged in with the same current user credentials),
     * If so, then logout.
     *
     */
    public function checkSessionConcurrency(){
        if($this->isUserLoggedIn()){
            if($this->registry->auth_model->isConcurrentSessionExists()){
                $this->registry->auth_model->logout();                                
                header('location: ' . $this->registry->config->get('base_url') . 'login?returnUrl=' . urlencode($this->currentPageUrl()));
                exit();
            }
        }
    }  
    
    /**
     * Get user ID
     *
     * @return mixed
     */
    public function userId()
    {
        return $this->registry->session->get('user_id');        
    }
    
    /**
     * Get user account type
     *
     * @return mixed
     */
    public function userAccountType()
    {
        return $this->registry->session->get('user_account_type');        
    }
    
    
    /**
     * Get session ID
     *
     * @return mixed
     */
    public function sessionId()
    {
        return $this->registry->session->sessionId();        
    }
   
    
 } // end of class