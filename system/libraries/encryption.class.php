<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
 /**
 * Encryption Class
 *
 * Encryption and password hashing library
 * 
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @since       Version 1.0
 */
class Encryption {

    /**
     * Password Algorithm
     * 
     * @var string
     */
    public $password_algorithm  = 'bcrypt'; //  bcrypt | default
    
    /**
     * Password Hashing Cost
     * 
     * @var int
     */
    public $password_cost  = 10; // 4 to 31
    
    /**
     * Password Hashing Options
     * 
     * @var int
     */
    private $password_options  = array(); 
    
    
    /**
     * Encryption algorithm e.g aes-128-cbc
     * 
     * @var string
     */
    private $encryption_algorithm  = ''; 
    
    /**
     * Encryption key 
     * 
     * @var string
     */
    private $encryption_key  = ''; 
    
    /**
     * Encryption salt 
     * 
     * @var string
     */
    private $encryption_salt  = ''; 
    
    
    public function __construct()
    {
        global $Registry; //use core global variable $Registry
        
        $this->encryption_key        = $Registry->config->get('encryption_key');
        $this->encryption_algorithm  = $Registry->config->get('encryption_algorithm');
        $this->encryption_salt       = $Registry->config->get('encryption_salt');
        
        // Check if need password_*() exist in the current PHP version else use compatibility password library 
        // for PHP >= 5.3.7 and PHP < 5.5
        if (!function_exists('password_hash')) {
            //include the password compatibility library file
            $lib_path =  dirname( __FILE__ ) . '/password_lib/password.php';
            if (file_exists($lib_path)) {
                require_once ($lib_path);
            } else {
                die ("To use Encription (Password) Library, please upgrade to PHP 5.5 or have PHP >= 5.3.7
                download and install the compatibility password library to <strong>$lib_path</strong>");
            }            
        }
        //change the $password_agorithm  string to its appropriate value
        if ($this->password_algorithm == 'bcrypt') {
            $this->password_algorithm = PASSWORD_BCRYPT;
        } else {
            $this->password_algorithm = PASSWORD_DEFAULT;
        }
        //set the hashing options
        $this->password_options =  array("cost" => $this->password_cost);
    }
     

	/**
     * Create Password Hashes
     * 
     * @param string $password
     * @return array hash
     */
	public function passwordHash($password) 
    {
        $hash = password_hash($password, $this->password_algorithm, $this->password_options);        
		return $hash;
	}
	
	/**
     * Verify Password Hashes
     * 
     * @param string $password
     * @param string $hash
     * @return bool
     */
	public function passwordVerify($password, $hash) {
	   
		if (password_verify($password, $hash)) {
            return true;
        } else {
            return false;
        }
 	}
    
    
    /**
     * Rehash Passwords
     * 
     * @param string $password
     * @param string $hash
     * @return bool
     */
	public function passwordNeedsRehash($password, $hash) {
	   
		if (password_verify($password, $hash)) { 
            if (password_needs_rehash($hash, $this->password_algorithm, $this->password_options)) {
                $hash = password_hash($password, $this->password_algorithm, $this->password_options);
            }
        }
        return $hash;
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
    
    
    
    public function encrypt($data) 
    {
        $method    = $this->encryption_algorithm;
        $password  = $this->strtohex($this->encryption_key);
        $iv    = $this->encryption_salt;
        $data = openssl_encrypt($data, $method, $password, true, $iv); //encyrpt the data
        $data = base64_encode($data); // encode the cyphertext with base64
        return $data;
    }
    
    public function decrypt($data) 
    {
        $method    = $this->encryption_algorithm;
        $password  = $this->strtohex($this->encryption_key);
        $iv    = $this->encryption_salt;
        $data = base64_decode($data); // decode the encoded base64 cyphertext
        $data = openssl_decrypt($data, $method, $password, true, $iv); // decrypty the cyphertext
        return $data;
    }
    
    private function strtohex($x) 
    {
        $s='';
        foreach (str_split($x) as $c) $s.=sprintf("%02X",ord($c));
        return($s);
    } 
    
}