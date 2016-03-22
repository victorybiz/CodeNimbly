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
$config['meta_author']          = $config['name'] . ' Team';
$config['copyright_stamp']      = "&copy; " . date('Y') . " {$config['name']}. All rights reserved.";
$config['page_title_separator'] = " | ";
$config['breadcrumb_separator'] = " &raquo; ";


/**
 * App base Initial Settings.
 */
$config['default_timezone']             = 'Africa/Lagos';
$config['charset']                      = 'UTF-8';
$config['photo_sizes']                  = array('small'=>'50', 'medium'=>'100'); //in px

