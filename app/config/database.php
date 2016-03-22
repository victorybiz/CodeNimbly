<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Database config
 *
 * @package		CodeNimbly
 * @subpackage  CodeNimbly
 * @category    Config
 * @since       Version 1.0
 */

$active_mode = SITE_ENV;  //retrieves active SITE ENVIRONMENT mode for db settings group set in index.php filein root dir

//Database settings for PRODUCTION  VERSION
$db['production']['driver']       = 'mysql';
$db['production']['hostname']     = 'localhost';
$db['production']['username']     = 'db_user';
$db['production']['password']     = 'db_pwd';
$db['production']['database']     = 'dbname';
$db['production']['tbl_prefix']   = ''; //'tbl_';

//Database settings for TESTING  VERSION
$db['testing']['driver']       = 'mysql';
$db['testing']['hostname']     = 'localhost';
$db['testing']['username']     = 'db)user';
$db['testing']['password']     = 'db_pwd';
$db['testing']['database']     = 'dbname';
$db['testing']['tbl_prefix']   = ''; //'tbl_';


//Database settings for DEVELOPMENT VERSION
$db['development']['driver']        = 'mysql';
$db['development']['hostname']      = 'localhost';
$db['development']['username']      = 'root';
$db['development']['password']      = '';
$db['development']['database']      = 'dbname';
$db['development']['tbl_prefix']    = ''; //'tbl_';

// configuration
$db_type        = $db[$active_mode]['driver'];
$db_host        = $db[$active_mode]['hostname'];
$db_name        = $db[$active_mode]['database'];
$db_user        = $db[$active_mode]['username'];
$db_pass        = $db[$active_mode]['password'];
$db_tbl_prefix  = $db[$active_mode]['tbl_prefix'];

define("DB_DRIVER",     $db[$active_mode]['driver']);
define("DB_HOST",       $db[$active_mode]['hostname']);
define("DB_USER",       $db[$active_mode]['username']);
define("DB_PASS",       $db[$active_mode]['password']);
define("DB_NAME",       $db[$active_mode]['database']);
define("DB_TBL_PREFIX", $db[$active_mode]['tbl_prefix']);



/** --- Table Names CONSTANTS ---------- */
define("TBL_USERS", DB_TBL_PREFIX.'users');
