<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * CodeNimbly
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
 
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

/**
 * System Initialization File
 *
 * Initialize all base classes, resources, and executes the request.
 *
 * @package		CodeNimbly
 * @subpackage  CodeNimbly
 * @author		Victory Osayi Airuoyuwa
 * @link		http://codenimbly.com/doc/
 */

// get the config files
require_once(DIR_APP_PATH . "/config/config.php");
// get the URL Routes file
require_once(DIR_BASE_PATH . "/routes.php"); 
// get the registry. 
require_once(DIR_SYSTEM_PATH . "/core/registry.class.php");
require_once(DIR_SYSTEM_PATH . "/core/controller.class.php");
require_once(DIR_SYSTEM_PATH . "/core/model.class.php");
require_once(DIR_SYSTEM_PATH . "/core/router.class.php");

global $registered_class, $autoload;
// initialise the Registry
$Registry =& CodeNimbly\Core\Registry::init();
// register and load the core classes
$Registry->registerCoreClass('Config', 'config', true);
$Registry->registerCoreClass('Template', 'template', true);
// pass the classes to be registered and autoloaded
$Registry->registerClasses($registered_class);
$Registry->loadLibrary($autoload['libraries']);
$Registry->loadModel($autoload['models']);
$Registry->loadHelper($autoload['helpers']);


/**
 * Perform URL Routing to Controller and method
 */
$Router = new CodeNimbly\Core\Router();
$Router->setBasePath(ROUTE_BASE_PATH);
//Add the url url_route arrays from the url routes files
$Router->addRoutes($routes);
/* Match the current request */
$match = $Router->match();

if($match) {    
    list($match_class_path, $match_method) = explode('@', $match['target']);
    $segments = explode('/', $match_class_path);
    if (is_array($segments)) { 
        $seg_num = count($segments);
        $match_class = $segments[$seg_num - 1];   
    } else { 
        // Do Nothing and allow file_exists() to check for existence
    }       
    //include the controller class
    $controller_path  = DIR_APP_PATH . '/controllers/' . $match_class_path . '.php';    
    if (file_exists($controller_path)) {        
        require_once($controller_path);                 
        $objController = new $match_class;
        $objController->$match_method($match['params']);
    } else {
        exit("Controller \"$match_class\" doesn't exist.");
    }   
} else {     
    /** ERORR 404: PAGE NOT FOUND */
    header("HTTP/1.0 404 Not Found");
    require_once('404.php');
    exit;
}
