<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
 /**
 * Hash Class
 *
 * Password hashing and encryption library
 * 
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @since       Version 1.0
 */
class Hash {

    /**
     * Algorithm
     * 
     * @var string
     */
    private $algorithm  = 'sha256';
     
     
	/**
     * Create salt
     * 
     * @return string salt
     */
	private function create_salt() {
		$string = str_shuffle(mt_rand());
		$salt 	= uniqid($string ,true);
		return $salt;
	}

	/**
     * Create hash
     * 
     * @param string $string string to hash
     * @return array ('salt', 'hash')
     */
	public function create($string) {
        $salt = $this->create_salt();
        $hash = hash($this->algorithm, $string . $salt);
        $hash_data['salt'] = $salt;
        $hash_data['hash'] = $hash;
		return $hash_data;
	}
	
	/**
     * Verify hash
     * 
     * @param string $salt
     * @param string $string
     * @param string $existing_hashed_string
     * @param bool $string_is_hash_too
     * @return bool
     */
	public function verify($salt, $string, $existing_hashed_string, $string_is_hash_too=false) {
	   
		if ($string_is_hash_too === false) {
            //re-create hash using the salt
            $hash = hash($this->algorithm, $string . $salt);	
    		if($hash == $existing_hashed_string) {
    			return true;
    		} else {
    			return false;
    		}
		} else {  		
			if($string == $existing_hashed_string) {
    			return true;
    		} else {
    			return false;
    		}	  
		}        
	}
}