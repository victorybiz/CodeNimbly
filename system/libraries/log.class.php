<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Log Class
 *
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Libraries
 * @since       Version 1.0
 */
class Log {
    
    private $handle;

	public function __construct($filename) {
		$this->handle = fopen(DIR_LOGS . $filename, 'a');
	}

	public function write($message) {
		fwrite($this->handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
	}
	
	public function __destruct() {
		fclose($this->handle);
	}
 } // end of class