<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Autoload config
 * 
 * Autoload classes (Libraries and Model) with keys below. 
 * class key specified in $autoload doesn't need to be loaded again in the Controller.
 * But all libraries and model classes needed to work must must be registered in $registered_class;
 * NOTE: libraries and model class key must be a registered class in $registered_class array

 * @package		CodeNimbly
 * @subpackage  CodeNimbly
 * @category    Config
 * @since       Version 1.0
 */
 
$autoload['libraries'] = array(
    'date', 'csrf', 'hash', // 'db', 
);

$autoload['models'] = array(
    //'classname_model',
);

$autoload['helpers'] = array(
    'config_helper', 'url_helper', 'csrf_helper', 'html_helper', 'date_helper',
);


/** 
 * Registered Library Classes of autoloaded libraries class key above
 */
$registered_class['libraries'] = array(
    // 'class_key' => 'ClassName',
    'db' => 'Database',
    'date' => 'Date',
    'csrf' => 'CSRF',
    'hash' => 'Hash',
);

/** 
 * Registered Model Classes of autoloaded libraries class key above
 */
$registered_class['models'] = array(
    // 'class_key_model' => 'ClassName',
);

