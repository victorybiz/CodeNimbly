<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Config Class
 *
 * Set and get Config data items
 *
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Core
 * @since       Version 1.0
 */
class Config {
    
    protected $config;
    
    public function __construct()
    {
        //get the values of the $config variable arrays in app/config/*.php files
        global $config;
        $this->config  =& $config;
    }
    
    public function set($index, $value)
    {
        $this->config[$index] = $value;
        return $this->config[$index];
    }
    
    public function get($index)
    {
        if (isset($this->config[$index])) {
            return $this->config[$index];
        } else {
            return null;
        }
    }
    
    public function get_all()
    {
        return $this->config;
    }
    
    
}

