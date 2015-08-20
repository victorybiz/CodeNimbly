<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 *  URI Routes
 *
 * This file lets you re-map URI requests to specific controller functions
 * 
 * $routes[] = array($method, $route, $target);
 *  
 * $routes[] = array('GET|POST', '/profile/{user_id}}/', 'ControllerClassName@methodToCall'); 
 * $routes[] = array('GET|POST', '/profile/{num:user_id}/', 'path/to/ControllerClassName@methodToCall'); 
 * $routes[] = array('GET|POST', '/profile/{alpha]:username}/', 'path/to/ControllerClassName@methodToCall'); 
 * $routes[] = array('GET|POST', '/profile/{[0-9]+:user_id}/', 'path/to/ControllerClassName@methodToCall'); 
 * 
 * MATCH TYPE AND SHORT-HAND
 * {name}                    // Catch all as 'name'
 * {num:id}                  // Match integer/numbers as 'id'
 * {alpha:action}            // Match alphabets characters as 'action'
 * {alnum:user}              // Match alphanumeric characters as 'user'
 * {hex:key}                 // Match hexadecimal characters as 'key'
 * {[male|female]+:gender}   // RegEx Pattern Match either 'male' or 'female' as 'gender'
 * {[A-Z]+:title}             // RegEx Pattern Match upper case alphabets characters  as 'title'
 *
 * @package		CodeNimbly
 * @subpackage  CodeNimbly
 * @category    Routes
 * @since       Version 1.0 
 */

// Routes to display Home Welome Page
$routes[] = array('GET', '/', 'home/Welcome@index');



