<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
 /**
 * Database Class
 *
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @author		Victory Osayi Airuoyuwa
 * @link		http://codenimbly.com/doc/
 * @since       Version 1.0
 */
class Database {
    
    private $driver    = 'mysql'; //
    private $host      = 'localhost';
    private $user      = '';
    private $pass      = '';
    private $dbname    = '';
  
    private $dbh;       // DB Handler var
    private $stmt;      // Statement var
    private $error;     // Error var
 
    /**
     * Class constructor / initializer
     */
    public function __construct($driver = DB_DRIVER, $host = DB_HOST, $user = DB_USER, $pass = DB_PASS, $dbname = DB_NAME) 
    {
        //If any use database configuration passed as parameters via __construct(...) else use values from DB_* constants
        if (!is_null($driver)) {
            $this->driver = $driver;
        }
        if (!is_null($host)) {
            $this->host = $host;
        }
        if (!is_null($user)) {
            $this->user = $user;
        }
        if (!is_null($pass)) {
            $this->pass = $pass;
        }
        if (!is_null($dbname)) {
            $this->dbname = $dbname;
        }
        
        // Set DSN
        $dsn = $this->driver . ':host=' . $this->host . ';dbname=' . $this->dbname; //"mysql:host=;dbname="
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION, //Use EXCEPTION to handle errors           
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'" // an SQL command to execute when connecting
        );
        try {
            // Create a new PDO instanace
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        }
        catch(PDOException $e){ // Catch any errors
            
            $this->_errorHandler($e->getMessage());
        }
    }
    
    public function connect($driver = null, $host = null, $user = null, $pass = null, $dbname = null)
    {
        self::__construct($driver = null, $host = null, $user = null, $pass = null, $dbname = null);            
    }            
    
    /**
     * Database Error Handler
     * @param string $error_message : Error Message
     */
    private function _errorHandler($error_message='') 
    {
        $this->error = $error_message;
        echo $error_message;
        exit;
    }
    
    /**
     * Prepare SQL Query Statement 
     * @param string $query : SQL query statment
     */
    public function prepare($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }
    
    /**
     * Bind value to a Parameter
     * @param string $param : is the placeholder value that we will be using in our SQL statement, example :name.
     * @param string $value : is the actual value that we want to bind to the placeholder, example “Vic Bob”.
     * @param string $type  : is the datatype of the parameter, example string.
     */
    public function bindValue($param, $value, $type = null)
    {
        if (!is_null($type)) {
            switch ($type) {
                case 'int':
                    $type = PDO::PARAM_INT;
                    break;
                case 'bool':
                    $type = PDO::PARAM_BOOL;
                    break;
                case 'null':
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        } else {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }
    
    /**
     * Execute a Query
     */
    public function execute($error_msg_prefix_str='', $rollback_transaction=false)
    {
        try {
            return $this->stmt->execute();
            
        } catch (PDOException $e){
            
            $error_message = "$error_msg_prefix_str() ==> " . $e->getMessage();
            
            if ($rollback_transaction === true) {
                $this->rollbackTransaction();
            }            
            $this->_errorHandler($error_message);
        }
    }
    
    /**
     * Fetch All Result 
     */
    public function fetchAll()
    {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }    
    
    /**
     * Fetch Single Results 
     */
    public function fetch()
    {
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Count Result Row
     */
    public function countRow()
    {
        return $this->stmt->rowCount();
    }
    
    /**
     * Last Insert Id
     */
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }
    
    /**
     * Begin a transaction:
     */
    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }
    
    /**
     * End a transaction and commit your changes:
     */
    public function commitTransaction()
    {
        return $this->dbh->commit();
    }
    
    /**
     *  Cancel a transaction and roll back your changes:
     */
    public function rollBackTransaction()
    {
        return $this->dbh->rollBack();
    }

    /**
     * Debug Dump Parameters 
     */
    public function closeCursor()
    {
        return $this->stmt->closeCursor();
    }
    
    /**
     * Closes the cursor, enabling the statement to be executed again.
     */
    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }
    
    
    public function sanitize($string)
    {
        $string = trim($string);
        return $string;
    }
}
?>