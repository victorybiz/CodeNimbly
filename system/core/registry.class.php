<?php
namespace CodeNimbly\Core; 
 /**
 * Registry Class
 *
 * Register and load classes, helpers, libraries, models and all resources need.
 *
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Core
 * @since       Version 1.0
 */
class Registry { 
    
    private static $instance; 
     
    private static $class_dir_path; 
    
    public $_lang_meta  = array();
    public $_lang       = array();
          
     
    function __construct() 
    { 
    } 
     
    private function __clone() 
    {    
    } 
     
    public static function init() 
    { 
        if(!self::$instance instanceof self) { 
            self::$instance = new self(); 
        }  
        return self::$instance; 
    } 
    
    public static function loadConfig($config_file)
    {
        global $config, $autoload, $registered_class, $routes; // declare global usage of the variables in the config files        
        if (!is_array($config_file)) {
            //Load from the config in the application directory 
            if (file_exists(DIR_APP_PATH . DS . 'config' . DS . strtolower($config_file) . '.php')) {
                require_once (DIR_APP_PATH . DS . 'config' . DS . strtolower($config_file) . '.php');
                
            } else {
                exit("Config file '$config_file' does not exist in the app/config directory.");
            }
        } else {
            foreach($config_file as $config_f) {
                //Load from the config in the application directory 
                if (file_exists(DIR_APP_PATH . DS . 'config' . DS . strtolower($config_f) . '.php')) {
                    require_once (DIR_APP_PATH . DS . 'config' . DS . strtolower($config_f) . '.php');
                    
                } else {
                    exit("Config file '$config_f' does not exist in the app/config directories.");
                }
            }
        }
    }
        
    public static function registerCoreClass($classname, $classkey, $autoload = false) 
    {                  
        if(\class_exists($classname)) {  
            self::$instance->data[$classkey] = $classname;          
        } else {                          
            if (file_exists(DIR_SYSTEM_PATH . DS . 'core' . DS . strtolower($classname) . '.class.php')) {
                require_once (DIR_SYSTEM_PATH . DS . 'core' . DS . strtolower($classname) . '.class.php');

            } else {
                exit("Core Class file '$classname' does not exist in the system core directory.");
            }          
            self::$instance->data[$classkey] = $classname;            
        }    
        if ($autoload === true) { 
            return self::$instance->loadCore($classkey);
        } else { 
            return self::$instance->data[$classkey]; 
        }              
    }
    
    public static function registerLibrary($classname, $classkey, $autoload = false) 
    {                  
        if(\class_exists($classname)) { 
            self::$instance->data[$classkey] = $classname;         
        } else {                            
            //Try load from the library class in the application directory then try the one from system directory
            if (file_exists(DIR_APP_PATH . DS . 'libraries' . DS . strtolower($classname) . '.class.php')) {
                require_once (DIR_APP_PATH . DS . 'libraries' . DS . strtolower($classname) . '.class.php');
                
            } elseif (file_exists(DIR_SYSTEM_PATH . DS . 'libraries' . DS . strtolower($classname) . '.class.php')) {
                require_once (DIR_SYSTEM_PATH . DS . 'libraries' . DS . strtolower($classname) . '.class.php');
                
            } else {
                exit("Library class file '$classname' does not exist in the libraries directories.");
            }       
            self::$instance->data[$classkey] = $classname; 
            self::$instance->data[$classkey] = $classname;            
        }    
        if ($autoload === true) { 
            return self::$instance->loadCore($classkey);
        } else { 
            return self::$instance->data[$classkey]; 
        }                   
    }
    
    public static function registerModel($classname, $classkey, $autoload = false) 
    {          
        if(\class_exists($classname)) { 
            self::$instance->data[$classkey] = $classname;       
        } else {                            
            //Try load from the model class in the application directory then try the one from system directory
            if (file_exists(DIR_APP_PATH . DS . 'models' . DS . strtolower($classname) . '.php')) {
                require_once (DIR_APP_PATH . DS . 'models' . DS . strtolower($classname) . '.php');
                
            } elseif (file_exists(DIR_SYSTEM_PATH . DS . 'models' . DS . strtolower($classname) . '.php')) {
                require_once (DIR_SYSTEM_PATH . DS . 'models' . DS . strtolower($classname) . '.php');
                
            } else {
                exit("Model class file '$classname' does not exist in the models directories.");
            }                
            self::$instance->data[$classkey] = $classname;            
        }    
        if ($autoload === true) { 
            return self::$instance->loadCore($classkey);
        } else { 
            return self::$instance->data[$classkey]; 
        }                  
    }
        
    public static function registerClass($classname, $classkey, $class_dir_path = null, $autoload = false) 
    { 
        if($class_dir_path === null || $class_dir_path === 'default') { 
            $class_dir_path = DIR_SYSTEM_PATH . DS . 'libraries';
        }          
        if(\class_exists($classname)) { 
            self::$instance->data[$classkey] = $classname;
        } else {            
            //check if file exist
            if (file_exists($class_dir_path . DS . strtolower($classname) . '.class.php')) {
                require_once ($class_dir_path . DS . strtolower($classname) . '.class.php');
                
            } else {
                exit("Class file '$classname' does not exist in the path '$class_dir_path'.");
            }                               
            self::$instance->data[$classkey] = $classname;            
        }    
        if ($autoload === true) { 
            return self::$instance->loadCore($classkey);
        } else { 
            return self::$instance->data[$classkey]; 
        }                
    } 
    
    public static function registerClasses($register_classes)
    {
        foreach ($register_classes as $class_type => $register_class) {
             if ($class_type == 'libraries') {
                //Loop through libraries and register the Classes in the array $register_classes
                foreach ($register_class as $reg_classkey => $reg_classname) { 
                    self::$instance->registerLibrary($reg_classname, $reg_classkey);
                }
             } elseif ($class_type == 'models') {
                //Loop through libraries and register the Classes in the array $register_classes
                foreach ($register_class as $reg_classkey => $reg_classname) { 
                    self::$instance->registerModel($reg_classname, $reg_classkey);   
                }
             } elseif ($class_type == 'core') {
                //Loop through libraries and register the Classes in the array $register_classes
                foreach ($register_class as $reg_classkey => $reg_classname) {
                    self::$instance->registerCoreClass($reg_classname, $reg_classkey);   
                }
             }
        }       
    }
    
    
    private static function _loadClass($classkey) 
    { 
        if(isset(self::$instance->data[$classkey])) { 
                    
            $segments = explode('/', self::$instance->data[$classkey]);
            if (is_array($segments)) { 
                $seg_num = count($segments);
                $match_class = $segments[$seg_num - 1];   
            } else { 
                $match_class = $segments;
            }     
            self::$instance->{$classkey} = new $match_class;  
             
            return self::$instance->{$classkey};        
            
        } else { 
            exit('Fatal Error: Class with Key "' . $classkey . '" doesn\'t Exists Class Must Be A Registered Class' ); 
        }
    }
    
    public static function loadClass($classkey)
    { 
        if (!is_array($classkey) && !empty($classkey)) { 
            return self::$instance->_loadClass($classkey); 
                      
        } else {            
            foreach($classkey as $ckey) { 
                if (!empty($ckey)){
                    self::$instance->_loadClass($ckey); 
                }                                 
            }
            return self::$instance; 
        }
    } 
    
    public static function loadLibrary($classkey)
    { 
        return self::$instance->loadClass($classkey);
    }
    
    public static function loadModel($classkey)
    { 
        return self::$instance->loadClass($classkey);
    }    
    
    public static function loadCore($classkey)
    { 
        return self::$instance->loadClass($classkey);
    }
    
    public static function loadHelper($helper)
    { 
        if (!is_array($helper)) {
            //Try load from the helpers in the application directory then try the one from system directory
            if (file_exists(DIR_APP_PATH . DS . 'helpers' . DS . strtolower($helper) . '.php')) {
                require_once (DIR_APP_PATH . DS . 'helpers' . DS . strtolower($helper) . '.php');
                
            } elseif (file_exists(DIR_SYSTEM_PATH . DS . 'helpers' . DS . strtolower($helper) . '.php')) {
                require_once (DIR_SYSTEM_PATH . DS . 'helpers' . DS . strtolower($helper) . '.php');
                
            } else {
                exit("Helper file '$helper' does not exist in the helpers directories.");
            }
        } else {
            foreach($helper as $hp) {
                //Try load from the helpers in the application directory then try the one from system directory
                if (file_exists(DIR_APP_PATH . DS . 'helpers' . DS . strtolower($hp) . '.php')) {
                    require_once (DIR_APP_PATH . DS . 'helpers' . DS . strtolower($hp) . '.php');
                    
                } elseif (file_exists(DIR_SYSTEM_PATH . DS . 'helpers' . DS . strtolower($hp) . '.php')) {
                    require_once (DIR_SYSTEM_PATH . DS . 'helpers' . DS . strtolower($hp) . '.php');
                    
                } else {
                    exit("Helper file '$hp' does not exist in the helpers directories.");
                }
            }
        }
    } 
    
    public static function loadLang($language_file)
    { 
        $lang = self::$instance->config->get('lang');
        $languages = self::$instance->config->get('languages');
        $language = $languages[$lang];

        if (!is_array($language_file)) {
            //Try load from the languages in the application directory then try the one from system directory
            if (file_exists(DIR_APP_PATH . DS . 'languages' . DS .  $language . DS . strtolower($language_file) . '.php')) {
                require_once (DIR_APP_PATH . DS . 'languages' . DS . $language . DS . strtolower($language_file) . '.php');
                
            } elseif (file_exists(DIR_SYSTEM_PATH . DS . 'languages' . DS . $language . DS . strtolower($language_file) . '.php')) {
                require_once (DIR_SYSTEM_PATH . DS . 'languages' . DS .  $language . DS . strtolower($language_file) . '.php');
                
            } else {
                exit("Language file '$language_file' does not exist in the language directories.");
            }
        } else {
            foreach($language_file as $language_f) {
                //Try load from the languages in the application directory then try the one from system directory
                if (file_exists(DIR_APP_PATH . DS . 'languages' . DS . $language . DS . strtolower($language_f) . '.php')) {
                    require_once (DIR_APP_PATH . DS . 'languages' . DS . $language . DS . strtolower($language_f) . '.php');
                    
                } elseif (file_exists(DIR_SYSTEM_PATH . DS . 'languages' . DS . $language . DS . strtolower($language_f) . '.php')) {
                    require_once (DIR_SYSTEM_PATH . DS . 'languages' . DS . $language . DS . strtolower($language_f) . '.php');
                    
                } else {
                    exit("Language file '$language_f' does not exist in the language directories.");
                }
            }
        } 
        // Assign values of $_lang_meta array var as property of the Registry class if set in the language file
        if (isset($_lang_meta)) {
            self::$instance->_lang_meta =  $_lang_meta;
        }
        // Assign values of $_lang array var as property of the Registry class if set in the language file
        if (isset($_lang)) {
            self::$instance->_lang =  $_lang;
        }
    }
} 