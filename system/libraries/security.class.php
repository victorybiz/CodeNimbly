<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Security Class
 * - CSRF (Cross-Site Request Forgery)
 * - XSS (Cross-Site Scripting)
 * Dependency: Session library
 *
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @since       Version 1.0
 */
class Security {
    
    protected $registry;
	
	/**
	 * Class constructor
     * load the session library from the Registry
	 */
	public function __construct()
	{	
        global $Registry; // use core global variable $Registry
        $this->registry =& $Registry;
        // check if Session library has been registered and loaded if not then register and load it
        if (!isset($this->registry->session) || !is_object($this->registry->session)) {
            $this->registry->registerLibrary('Session', 'session', true);            
            $Registry =& $this->registry;
        }
	}
    
    
    public function csrfTokenId() 
    {
        $token_id = $this->registry->session->get('csrf_token_id');
        if (!empty($token_id)) {
            return $token_id;
        } else {
            $token_id = 'tkid' . $this->generateRandomValue(10);
            $this->registry->session->set('csrf_token_id', $token_id);
            return $token_id;
        }
    }
    
    public function csrfToken() 
    {
        $token = $this->registry->session->get('csrf_token_value');
        if (!empty($token)) {
            return $token;
        } else {
            $token = hash('sha256', $this->generateRandomValue(500));
            $this->registry->session->set('csrf_token_value', $token);
            return $token;
        }
    }
    
    public function csrfVerifyRequest($method) 
    {
        if($method == 'post' || $method == 'get' || $method == 'request') {
            if ($method == 'post') {
                $method = $_POST;
            } elseif ($method == 'get') {
                $method = $_GET;
            } else {
                $method = $_REQUEST;
            }
            if(isset($method[$this->csrfTokenId()]) && ($method[$this->csrfTokenId()] == $this->csrfToken())) {
                return true;
            } else {
                return false;   
            }
        } else {
            return false;   
        }
    }
    
    /**
     * CSRF: Generate Random Form Names
     * 
     * @param array $names Names Key
     * @param bool $regenerate
     * @return array $values
     */
    public function csrfGenrateFormNames($names, $regenerate) 
    { 
        $values = array();
        foreach ($names as $n) {
                if($regenerate == true) {
                        $this->registry->session->delete($n);
                }
                $ss_val = $this->registry->session->get($n);
                $ss_val = !empty($ss_val) ? $ss_val : $this->generateRandomValue(10);
                $this->registry->session->set($n, $ss_val);
                $values[$n] = $ss_val;       
        }
        return $values;
    }
    
    /**
     * Generate Random Value
     * 
     * @param int $len Lenght
     * @return string
     */
    private function generateRandomValue($len) 
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
    
    
    public function csrfTokenFormInputTag()
    {
        $token_id = $this->csrfTokenId(); 
        $token = $this->csrfToken();//csrf_token
        return "<input type=\"hidden\" name=\"$token_id\" value=\"$token\" />";
    }
    
    public function csrfTokenUrlQueryString()
    {
        $token_id = $this->csrfTokenId(); 
        $token = $this->csrfToken();//csrf_token
        return "$token_id=$token";
    }
    
    /**
     * XSS filter
     * This simply removes "code" from any data, used to prevent Cross-Site Scripting Attacks.
     * 
     * @param mixed $data
     * @return mixed
     */
    public function xssFilter($data)
    {
        if (is_array($data)) {
			foreach ($data as $key => $value) {
				unset($data[$key]);
				$data[$this->xssFilter($key)] = $this->xssFilter($value);
			}
		} else {
			$data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
		}
		return $data;
    }
 } // end of class