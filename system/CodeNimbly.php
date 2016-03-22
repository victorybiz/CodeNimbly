<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * CodeNimbly 1.0
 *
 * An extensible micro HMVC framework for PHP newbies and crafters.
 *
 * @package		CodeNimbly
 * @author		Victory Osayi Airuoyuwa (@victorybiz) <Lavictorybiz@gmail.com>
 * @copyright	Copyright (c) 2015-2016, Victory Osayi Airuoyuwa (@victorybiz) <Lavictorybiz@gmail.com>
 * @link        https://github.com/victorybiz/CodeNimbly
 * @license		MIT, https://github.com/victorybiz/CodeNimbly/blob/master/LICENSE.txt
 * @since		Version 1.0
 *

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
/**
 * System Initialization File
 *
 * Initialize all base classes, resources, and executes the request.
 *
 * @package		CodeNimbly
 * @subpackage  CodeNimbly
 */

/** Include the Regustry class file which is the core power of the framwork */
require_once(DIR_SYSTEM_PATH . DS."core".DS."registry.class.php");
// initialise the Registry 
$Registry = CodeNimbly\Core\Registry::init();

/** load core basic Configuration files needed for framework to function by default... Arranged based on dependency */
$Registry->loadConfig('app');
$Registry->loadConfig('email');
$Registry->loadConfig('autoload');
$Registry->loadConfig('database');
$Registry->loadConfig('constants');
$Registry->loadConfig('timezones');
$Registry->loadConfig('template');
$Registry->loadConfig('language');
// pass and autoload the other config files (specified in config)
$Registry->loadConfig($autoload['configs']);

/** Include other core files which is also the core power of the framwork */
require_once(DIR_BASE_PATH . DS."routes.php"); // Include the URL Routes file
require_once(DIR_SYSTEM_PATH . DS."core".DS."controller.class.php");
require_once(DIR_SYSTEM_PATH . DS."core".DS."model.class.php");
require_once(DIR_SYSTEM_PATH . DS."core".DS."router.class.php");

// register and load the core classes needed for framework to function by default
$Registry->registerCoreClass('Config', 'config', true);
$Registry->registerCoreClass('Language', 'language', true);
$Registry->registerCoreClass('Template', 'template', true);

//register and load basic library class needed for framework to function by default (Order by dependency)
$Registry->registerLibrary('Session', 'session', true); //session lib comes first
$Registry->registerLibrary('Security', 'security', true);  
$Registry->registerLibrary('Request', 'request', true);  

//load basic helper files to lib above needed for framework to function by default
$Registry->loadHelper(array('config_helper', 'security_helper', 'language_helper', 'url_helper'));

// pass the classes to be registered and autoloaded including the helpers (specified in config)
$Registry->registerClasses($registered_class);
$Registry->loadLibrary($autoload['libraries']);
$Registry->loadModel($autoload['models']);
$Registry->loadHelper($autoload['helpers']);
 


/**
 * Run URL Routing and Dispatching to Controller and method
 */
$Router = new CodeNimbly\Core\Router();
// set route base path
$Router->setBasePath(ROUTE_BASE_PATH);
// Run dispatch routes
$Router->dispatch($routes,  DIR_APP_PATH . DS.'controllers'.DS, '');


