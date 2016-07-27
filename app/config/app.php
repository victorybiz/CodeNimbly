<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Custom App Config for this Project
 *
 * @package		CodeNimbly
 * @subpackage  CodeNimbly
 * @category    Config
 * @since       1.0
 */

$config['protocol']             = 'http'; // http | https
$config['domain_name']          = 'codenimbly.dev';
$config['www_domain_name']      = 'codenimbly.dev';
$config['site_url']             = "{$config['protocol']}://codenimbly.dev";
$config['base_url']             = "{$config['protocol']}://codenimbly.dev/";
$config['static_url']           = "{$config['protocol']}://codenimbly.dev/static/"; // Static content URL (cookieless sub domain RECOMMENDED)

$config['images_url']           = $config['static_url'] . "images/";
$config['css_url']              = $config['static_url'] . "css/";
$config['js_url']               = $config['static_url'] . "js/";
$config['third_party_url']      = $config['static_url'] . "third_party/";
$config['uploads_url']          = $config['static_url'] . "uploads/";
$config['uploads_dir']          = $_SERVER["DOCUMENT_ROOT"]."/static/uploads";

$config['name']                 = 'CodeNimbly';
$config['short_name']           = 'CodeNimbly';
$config['description']          = "An extensible micro HMVC framework for PHP newbies and crafters.";
$config['long_description']     = "An extensible micro HMVC framework for PHP newbies and crafters.";
$config['short_description']    = "An extensible micro HMVC framework for PHP newbies and crafters.";
$config['slogan']               = "Learn PHP to the core and code faster.";
$config['meta_description']     = $config['description'];
$config['meta_keywords']        = "codenimby, framework";
$config['meta_author']          = 'Victory Osayi Airuoyuwa';
$config['copyright_stamp']      = "&copy; " . date('Y') . " {$config['name']}. All rights reserved.";
$config['page_title_separator'] = " | ";
$config['breadcrumb_separator'] = " &raquo; ";


/**
|--------------------------------------------------------------------------
| App base Initial Settings.
|--------------------------------------------------------------------------
*/

/**  Session/Cookie settings */
$config['session_cookie_name']          = 'CodeNimblyID'; //string
$config['session_save_path']            = $_SERVER["DOCUMENT_ROOT"].'/../private-data/php_sess'; // string: custom directory path to save session data to. (Outside webroot)
$config['session_cookie_lifetime']      = 0; // integer: Lifetime of the cookie in seconds which is sent to the browser. The value 0 means "until the browser is closed." Defaults to 0.
$config['session_cookie_path']          = '/'; // string: Path on the domain where the cookie will work. Use a single slash ('/') for all paths on the domain. 
$config['session_cookie_domain']        = ".{$config['domain_name']}"; // Cookie domain, for example 'www.php.net'. To make cookies visible on all subdomains then the domain must be prefixed with a dot like '.php.net'
$config['session_cookie_secure']        = false; // boolean: If TRUE cookie will only be sent over secure connections.
$config['session_cookie_httponly']      = false; // string: If set to TRUE then PHP will attempt to send the httponly flag when setting the session cookie. 
$config['remember_me_cookie_name']      = 'remember_me'; //string
$config['remember_me_cookie_lifetime']  = (60*60*24*30); // integer: Lifetime of the cookie in seconds which is sent to the browser. The value 0 means "until the browser is closed." Defaults to 0.


/** Data Encyption settings */            
$config['encryption_algorithm']         = 'AES-128-CBC';  
$config['encryption_key']               = '6*i0gkjf@47cL1g$08&';  // Change to your own key
$config['encryption_salt']              = 'aoki567bg*dat28g'; // MAX: 16 chars (16 bytes) - Change to your own salt
$config['encryption_hmac_salt']         = '8ik9w@4L7d#11tM8g7m8%';  // Change to your own salt


/**  Other settings */
$config['default_timezone']             = 'Africa/Lagos';
$config['charset']                      = 'UTF-8';
$config['photo_sizes']                  = array('small'=>'50', 'medium'=>'100'); //in px

