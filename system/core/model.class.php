<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Model Class
 *
 * Takes in all registered registry resources and serve as base class for application models
 *
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Core
 * @since       Version 1.0
 */
class Model {
    
    protected $registry;
    
    public function __construct()
    {
        global $Registry;
        $this->registry =& $Registry;
        /** Loop through the initialised $Registry object as assigned each member to this class as its memeber.*/
        foreach ($this->registry as $object_key => $member_object) {
            $this->{$object_key} = $member_object;
        }        
    }
    
    public function load($classkey)
    {
        return $this->registry->loadClass($classkey);      
    }
}