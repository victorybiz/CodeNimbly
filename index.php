<?php 
/**
 * PHP App Powered By CodeNimbly
 *
 * An extensible micro HMVC framework for PHP newbies and crafters.
 *
 * @package		CodeNimbly
 * @author		Victory Osayi Airuoyuwa <Lavictorybiz@gmail.com>
 * @copyright	Copyright (c) 2015, Victory Osayi Airuoyuwa, Compudeluxe Solutions Ltd. <http://www.compudeluxe.com>
 * @license		http://codenimbly.compudeluxe.com/doc/license.html
 * @link		http://codenimbly.compudeluxe.com
 * @since		Version 1.0
 */ 
define("GO_OFFLINE", false); // [true | false]
define("SITE_ENV", 'development'); // [development | testing| production]
define("STATIC_CONTENT_VERSION", sha1('August 2015')); //token attached to urls to change cached

define("ROUTE_BASE_PATH", ''); //URI route based path. e.g '' for app in base dir. Or '/admin' for app in subdir 'admin'
define("DIR_BASE_PATH", dirname( __FILE__ )); // The applications root path, so we can easily get this path from files located in other folders
define("BASE_PATH", dirname( __FILE__ )); // The applications root path, so we can easily get this path from files located in other folders

define("DIR_APP_PATH", DIR_BASE_PATH . '/app');
define("DIR_SYSTEM_PATH", DIR_BASE_PATH . '/system'); //refer to the system dir in the base dir


// We will use this to ensure no script direct access and scripts are not called from outside of the framework 
define("PATH_ACCESS", true );
/** See (app/config/*.php) For more configurations and constants for App and paths */
  
// Check PHP Version
if (version_compare(phpversion(), '5.4.0', '<') == true) {
	exit('PHP5.4+ is required');
}

// start our sessions
session_start();


/**
 * --------------------------------------------------------------------
 * CHECK FOR OFFLINE MODE
 * --------------------------------------------------------------------
 */
if (defined("GO_OFFLINE") && GO_OFFLINE === true) {
    require_once('offline.php');
    exit;
}
/**
 * --------------------------------------------------------------------
 * INCLUDE CodeNimbly SYSTEM INITIALIZATION FILE 
 * --------------------------------------------------------------------
 */
require_once DIR_SYSTEM_PATH . '/CodeNimbly.php';

