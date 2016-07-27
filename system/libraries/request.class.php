<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Request Class
 * Dependency: Security library
 *
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @since       Version 1.0
 */
class Request {
    
    protected $registry;
    public $get = array();
	public $post = array();
	public $cookie = array();
	public $files = array();
	public $server = array();

    /**
	 * Class constructor
     * load the Security library from the Registry
	 */
	public function __construct() {
	   
       global $Registry; // use core global variable $Registry
        $this->registry =& $Registry;
        // check if Security library has been registered and loaded if not then register and load it
        if (!isset($this->registry->security) || !is_object($this->registry->security)) {
            $this->registry->registerLibrary('Security', 'security', true);            
            $Registry =& $this->registry;
        }
        //Get the global variables
        $this->get        = $_GET;
		$this->post       = $_POST;
		$this->request    = $_REQUEST;
		$this->cookie     = $_COOKIE;
		$this->files      = $_FILES;
		$this->server     = $_SERVER;
	}
    
    /**
     *  Fetch GET data.
     * 
     * @param mixed $index GET parameter name
     * @param bool $xss_filter Filter the value against XSS Attack
     * @return mixed GET value if found or null if not
     */
    public function get($index, $xss_filter = false) 
    {
        if (isset($this->get[$index])) {
            $value = $this->get[$index];
            $value = ($xss_filter === true) ? $this->registry->security->xssFilter($value) : $value;
        } else {
            $value = null;
        }
        return $value;
    }
    
    /**
     *  Fetch POST data.
     * 
     * @param mixed $index POST parameter name
     * @param bool $xss_filter Filter the value against XSS Attack
     * @return mixed POST value if found or null if not
     */
    public function post($index, $xss_filter = false) 
    {
        if (isset($this->post[$index])) {
            $value = $this->post[$index];
            $value = ($xss_filter === true) ? $this->registry->security->xssFilter($value) : $value;
        } else {
            $value = null;
        }
        return $value;
    }
    
    /**
     * This method works pretty much the same way as post() and get(), only combined. 
     * It will search through both POST and GET streams for data, looking in POST first, and then in GET:
     * 
     * @param mixed $index POST/GET parameter name
     * @param bool $xss_filter Filter the value against XSS Attack
     * @return mixed POST/GET value if found or null if not
     */
    public function post_get($index, $xss_filter = false) 
    {
        if (isset($this->post[$index])) {
            $value = $this->post[$index];
        } elseif (isset($this->get[$index])) {
            $value = $this->get[$index];
        } else {
            $value =  null;
        }
        if (!is_null($value)) {
            $value = ($xss_filter === true) ? $this->registry->security->xssFilter($value) : $value;
        }
        return $value;
    }
    
    /**
     *  Fetch FILES data.
     * 
     * @param mixed $index FILES parameter name
     * @param bool $xss_filter Filter the value against XSS Attack
     * @return mixed FILES value if found or null if not
     */
    public function files($index, $xss_filter = false) 
    {
        if (isset($this->files[$index])) {
            $value = $this->files[$index];
            $value = ($trim === true) ? trim($value) : $value;
            $value = ($strip_tags === true) ? strip_tags($value) : $value;
        } else {
            $value = null;
        }
        return $value;
    }
    
    /**
     *  Fetch COOKIE data.
     * 
     * @param mixed $index COOKIE parameter name
     * @param bool $xss_filter Filter the value against XSS Attack
     * @return mixed COOKIE value if found or null if not
     */
    public function cookie($index, $xss_filter = false) 
    {
        if (isset($this->cookie[$index])) {
            $value = $this->cookie[$index];
            $value = ($xss_filter === true) ? $this->registry->security->xssFilter($value) : $value;
        } else {
            $value = null;
        }
        return $value;
    }
    
    /**
     *  Fetch SERVER data.
     * 
     * @param mixed $index SERVER parameter name
     * @param bool $xss_filter Filter the value against XSS Attack
     * @return mixed SERVER value if found or null if not
     */
    public function server($index, $xss_filter = false) 
    {
        if (isset($this->server[$index])) {
            $value = $this->server[$index];
            $value = ($xss_filter === true) ? $this->registry->security->xssFilter($value) : $value;
        } else {
            $value = null;
        }
        return $value;
    }
    
    /**
     *  POST Call to RESTful API
     * 
     * @param string $api_url : The URL to call
     * @param array $post_data : Array of data to send  
     * @return mixed
     */
    public function restfulPost($api_url, $post_data) 
    {
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
        
        return $response;
    }
    
    
    /**
     *  POST Call to RESTful API
     * 
     * @param string $api_url : The URL to call
     * @param array $post_data : Array of data to send  
     * @return mixed
     */
    public function restfulGet($api_url) 
    {
        // create a new cURL resource
        $resource_ch = curl_init();           
        // set appropriate options
        curl_setopt($resource_ch, CURLOPT_URL, $api_url);
        curl_setopt($resource_ch, CURLOPT_HEADER, FALSE);
        curl_setopt($resource_ch, CURLOPT_RETURNTRANSFER, TRUE); //allow it to return transfer response
        curl_setopt($resource_ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        // grab URL and pass it to the browser and get the return transfer
        $response = curl_exec($resource_ch);           
        // close cURL resource, and free up system resources
        curl_close($resource_ch);
        
        return $response;
    }    
    
    
 } // end of class