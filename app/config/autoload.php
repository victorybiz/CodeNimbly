<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");

/**
 * Autoload config files, helper files and classes (Libraries and Model) with keys below. 
 * class key specified in $autoload doesn't need to be loaded again in the Controller.
 * But all libraries and model classes needed to work must all be registered in $registered_class;
 * NOTE: libraries and model class key must be a registered class in $registered_class array
 */
 
 
/** Autoload config files **/
$autoload['configs'] = array(
    //'config_filename',
);

/** Autoload libraries which keys here must be registered in $registered_class['libraries'] Below */
$autoload['libraries'] = array(
    //'class_key',
    'date', 'encryption',
);

/** Autoload models which keys here must be registered in $registered_class['models'] Below */
$autoload['models'] = array(
    //'class_key_model',
);

/** Autoload helper files */
$autoload['helpers'] = array(
    //'filename_helper',
     'my_custom_helper',
);



/** 
 * Registered Library Classes of autoloaded libraries class key Above
 */
$registered_class['libraries'] = array(
    // 'class_key' => 'ClassName',
    'date' => 'Date',
    'encryption' => 'Encryption',
);

/** 
 * Registered Model Classes of autoloaded model class key Above
 */
$registered_class['models'] = array(
    // 'class_key_model' => 'ClassName',
);

