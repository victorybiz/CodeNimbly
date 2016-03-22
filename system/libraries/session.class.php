<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Session Class
 *
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @since       Version 1.0
 */
class Session {
    
    private $registry;
    
    /**
     * Class Constructor
     * 
     * @return void
     */
    public function __construct()
    {
        global $Registry; //use core global variable $Registry
        
        $this->registry             = $Registry;
        $session_cookie_name        = $Registry->config->get('session_cookie_name');
        $session_save_path          = $Registry->config->get('session_save_path');
        $session_cookie_lifetime    = $Registry->config->get('session_cookie_lifetime');
        $session_cookie_path        = $Registry->config->get('session_cookie_path');
        $session_cookie_domain      = $Registry->config->get('session_cookie_domain');
        $session_cookie_secure      = $Registry->config->get('session_cookie_secure');
        $session_cookie_httponly    = $Registry->config->get('session_cookie_httponly');
        
        
        //set custom php session cookie name
        if (!empty($session_cookie_name)) {
            ini_set('session.name', $session_cookie_name); 
        } else {
            ini_set('session.name', 'CodeNimblySSID'); 
        }
        
        //set custom session_save_path
        if (!empty($session_save_path)) {
            // create the session_save_path dir if not already exit
            if (!file_exists($session_save_path)) {
                mkdir($session_save_path, 0777, true);
            }
            ini_set('session.save_path', $session_save_path); 
        } 
        //Do additional session cookie settings
        
        //if cookie lifetime is not set 0 (end on close of browser) then add the lifetime
        if ($session_cookie_lifetime > 0) {
            $session_cookie_lifetime = time() + $session_cookie_lifetime;
            
            if (!empty($session_cookie_lifetime)) {
                ini_set('session.cookie_lifetime', $session_cookie_lifetime); 
            }
        }
    
        if (!empty($session_cookie_path)) {
            ini_set('session.cookie_path', $session_cookie_path); 
        }
        if (!empty($session_cookie_domain)) {
            ini_set('session.cookie_domain', $session_cookie_domain); 
        }
        if (!empty($session_cookie_secure)) {
            ini_set('session.cookie_secure', $session_cookie_secure); 
        }
        if (!empty($session_cookie_httponly)) {
            ini_set('session.cookie_httponly', $session_cookie_httponly); 
        }        
        // specify the hash algorithm used to generate the session IDs. '0' means MD5 (128 bits) and '1' means SHA-1 (160 bits). 
        ini_set('session.hash_function', 'sha512'); // 0, 1, sha1512                   
        // Do session Garbage Collection (GC) settings
        ini_set('session.gc_probability', 1); 
        ini_set('session.gc_divisor', 100);
        ini_set('session.gc_maxlifetime', 3600);
        
        // if no session exist, start the session
        if(session_id() == '') {
            session_start();
        }
    }
	
    
    /**
     *  Set SESSION data.
     * 
     * @param mixed $key SESSION parameter name
     * @param mixed $value SESSION value data
     * @return void
     */
    public function set($key, $value) 
    {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Adds a value as a new array element to the SESSION data key.
     * useful for collecting error messages etc
     * 
     * @param mixed $key SESSION parameter name
     * @param mixed $value
     * @return void
     */
    public function add($key, $value) 
    {
        //if (!is_array($_SESSION[$key])) {
         //   $_SESSION[$key] =  array();
        //}
        $_SESSION[$key][] = $value;
    }
    
    /**
     *  Set SESSION array of data.
     * 
     * @param array $data SESSION parameter name as array index with data as value
     * @return void
     */
    public function set_data($data_array) 
    {
        if (is_array($data_array)) {
            foreach($data_array as $key=>$value) {
                $_SESSION[$key] = $value;
            }
        }
    }
    
    /**
     *  Fetch SESSION data.
     * 
     * @param mixed $key SESSION parameter name
     * @return mixed SESSION value if found or null if not
     */
    public function get($key, $delete_after_flash = false) 
    {
        if (isset($_SESSION[$key])) { 
            $value = $_SESSION[$key];
        } else { 
            $value = null;
        }
        if ($delete_after_flash === true) {
            $this->delete($key);
        }
        return $value;
    }
    
    /**
     *  Delete/unset a SESSION data.
     * 
     * @param mixed $key SESSION parameter name
     * @return void
     */
    public function delete($key) 
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }        
    }
    
    /**
     * Get session id
     * 
     * @return string
     */
    public function sessionId()
    {
       return session_id();
    }
    
    /**
     * Get session name
     * 
     * @return string
     */
    public function sessionName()
    {
       return session_name();
    }
    
    
    /** Set Cookie 
     * 
     * @param string $cookie_name : Cookie name
     * @param string $value : Cookie value
     * @param int $lifetime : Lifetime of the cookie in seconds which is sent to the browser. The value 0 means "until the browser is closed." Defaults to 0.
     * @param string $path : Path on the domain where the cookie will work, leave NULL to use default settings from config/app.php
     * @param string $domain : Cookie domain, leave NULL to use default settings from config/app.php
     * @param bool $secure : If TRUE cookie will only be sent over secure connections, leave NULL to use default settings from config/app.php
     * @param bool $httponly : If set to TRUE then PHP will attempt to send the httponly flag when setting the session cookie, leave NULL to use default settings from config/app.php
     * @return void
     */
    public function setCookie($cookie_name, $value, $lifetime = 0, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        $lifetime = ($lifetime > 0) ? time() + $lifetime : 0;
        $path = ($path === null) ? $this->registry->config->get('session_cookie_path') : $path;
        $domain = ($domain === null) ? $this->registry->config->get('session_cookie_domain') : $domain;
        $secure = ($secure === null) ? $this->registry->config->get('session_cookie_secure') : $secure;
        $httponly = ($httponly === null) ? $this->registry->config->get('session_cookie_httponly') : $httponly;            
        //set the cookie
        setcookie($cookie_name, $value, $lifetime, $path, $domain, $secure, $httponly);
    }
    
    /**
     *  Fetch Cookie data.
     * 
     * @param string $cookie_name
     * @return mixed Cookie value if found or null if not
     */
    public function getCookie($cookie_name, $delete_after_flash = false) 
    {
        if (isset($_COOKIE[$cookie_name])) { 
            $value = $_COOKIE[$cookie_name];
        } else { 
            $value = null;
        }
        if ($delete_after_flash === true) {
            $this->deleteCookie($cookie_name);
        }
        return $value;
    }
    

    /**
     * Delete cookie
     * 
     * @param string $cookie_name
     * @return void
     */
    public function deleteCookie($cookie_name)
    {        
        // delete cookie in browser
        setcookie($cookie_name, '', time() - (3600 * 24 * 3650), $this->registry->config->get('session_cookie_path'),
            $this->registry->config->get('session_cookie_domain'), $this->registry->config->get('session_cookie_secure'), $this->registry->config->get('session_cookie_httponly'));
    }       
    


    /**
     * deletes the session (= logs the user out)
     * 
     * @return void
     */
    public function destroy()
    {
        session_destroy();
    }
    
 } // end of class