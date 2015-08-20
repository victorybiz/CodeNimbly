<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Home Model
 */
class Home_Model extends Model {
    
    public function __construct() {
        parent::__construct();
    }       
    
    public function getAllUsers() 
    {
        //use of $this->db-> is allowed provided into has been registered and autoloaded in (app/config/autoload.php)
        $this->db->prepare("SELECT * FROM `".TBL_USERS."` ORDER BY `id` ASC");
        $this->db->execute(__METHOD__);
        if ($this->db->countRow() > 0) {
            $rows = $this->db->fetchAll();     
            return $rows;
        } else {
            return false;
        } 
    }
} // end of class