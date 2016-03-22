<?php  
/**
 * PHP App Powered By CodeNimbly
 *
 * An extensible micro HMVC framework for PHP newbies and crafters.
 *
 * @package		CodeNimbly
 * @author		Victory Osayi Airuoyuwa (@victorybiz) <Lavictorybiz@gmail.com>
 * @copyright	Copyright (c) 2015-2016, Victory Osayi Airuoyuwa (@victorybiz) <Lavictorybiz@gmail.com>
 * @link        https://github.com/victorybiz/CodeNimbly
 * @license		MIT, https://github.com/victorybiz/CodeNimbly/blob/master/LICENSE.txt
 * @since		Version 1.0
 */
define("GO_OFFLINE", false); // [true | false]
define("SITE_ENV", 'development'); // [development | testing| production]
define("STATIC_CONTENT_VERSION", sha1('march 2016')); //token attached to urls to change cached

define("ROUTE_BASE_PATH", ''); //URI route based path. e.g '' for 'app' in base dir. Or '/admin' for app in subdir 'admin'
define("DIR_BASE_PATH", dirname( __FILE__ )); // The applications root path, so we can easily get this path from files located in other folders
define("DS", DIRECTORY_SEPARATOR); // Director Separator
define("DIR_APP_PATH", DIR_BASE_PATH . DS . 'app');
define("DIR_SYSTEM_PATH", DIR_BASE_PATH . DS . 'system'); //refer to the system dir in the base dir


// We will use this to ensure no script direct access and scripts are not called from outside of the framework 
define("PATH_ACCESS", true );
/** See (app/config/*.php) For more configurations and constants for App and paths */
  
// Check PHP Version
if (version_compare(phpversion(), '5.4.0', '<') == true) {
	exit('PHP5.4+ is required');
}

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
require_once DIR_SYSTEM_PATH . DS . 'CodeNimbly.php';

