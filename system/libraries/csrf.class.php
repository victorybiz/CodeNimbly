<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
 /**
 * CSRF (Cross-Site Request Forgery) Class
 *
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @since       Version 1.0
 */
class CSRF {
    
    /**
	 * The namespace for the session variable and form inputs
	 * @var string
	 */
	private $namespace;
	
	/**
	 * Initializes the session variable name, starts the session if not already so,
	 * and initializes the token
	 * 
	 * @param string $namespace
	 */
	public function __construct($namespace = '_csrf')
	{
		$this->namespace = $namespace;		
		if (session_id() === '') {
			session_start();
		}
	}
    
    
    public function getTokenId() 
    {
        if(isset($_SESSION['token_id'])) { 
                return $_SESSION['token_id'];
        } else {
                $token_id = $this->random(10);
                $_SESSION['token_id'] = 'tkid'.$token_id;
                return $token_id;
        }
    }
    
    public function getToken() 
    {
        if(isset($_SESSION['token_value'])) {
                return $_SESSION['token_value']; 
        } else {
                $token = hash('sha256', $this->random(500));
                $_SESSION['token_value'] = $token;
                return $token;
        }
 
    }
    
    public function verify($method) 
    {
        if($method == 'post' || $method == 'get' || $method == 'request') {
                $post = $_POST;
                $get = $_GET;
                $request = $_REQUEST;
                if(isset(${$method}[$this->getTokenId()]) && (${$method}[$this->getTokenId()] == $this->getToken())) {
                        return true;
                } else {
                        return false;   
                }
        } else {
                return false;   
        }
    }
    
    public function formNames($names, $regenerate) 
    { 
        $values = array();
        foreach ($names as $n) {
                if($regenerate == true) {
                        unset($_SESSION[$n]);
                }
                $s = isset($_SESSION[$n]) ? $_SESSION[$n] : $this->random(10);
                $_SESSION[$n] = $s;
                $values[$n] = $s;       
        }
        return $values;
    }
    
    private function random($len) 
    {
        if (@is_readable('/dev/urandom')) {
                $f=fopen('/dev/urandom', 'r');
                $urandom=fread($f, $len);
                fclose($f);
        }
 
        $return='';
        for ($i=0;$i<$len;++$i) {
                if (!isset($urandom)) {
                        if ($i%2==0) mt_srand(time()%2147 * 1000000 + (double)microtime() * 1000000);
                        $rand=48+mt_rand()%64;
                } else $rand=48+ord($urandom[$i])%64;
 
                if ($rand>57)
                        $rand+=7;
                if ($rand>90)
                        $rand+=6;
 
                if ($rand==123) $rand=52;
                if ($rand==124) $rand=53;
                $return.=chr($rand);
        }
        return $return;
    }
    
    
    public function getTokenFormInputField()
    {
        $token_id = $this->getTokenId(); 
        $token = $this->getToken();//csrf_token
        return "<input type=\"hidden\" name=\"$token_id\" value=\"$token\" />";
    }
    
    public function getTokenUrlQueryString()
    {
        $token_id = $this->getTokenId(); 
        $token = $this->getToken();//csrf_token
        return "$token_id=$token";
    }
 } // end of class