<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Language Class
 *
 * Set and get Language $lang data items
 *
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Core
 * @since       Version 1.0
 */
class Language {
    
    protected $lang;
    protected $lang_meta;
    protected $registry;
    
    
    public function __construct()
    {             
        //get object of global $Registry
        global $Registry;              
        $this->registry =& $Registry;
        
        // load init language files
        $this->load('init');
        $this->load('init_lang'); 
        
        $this->lang_meta    = $this->registry->_lang_meta;
        $this->lang         = $this->registry->_lang;   
    } 
    
    public function load($language_file) 
    {  
        $this->registry->loadLang($language_file);                   
    }

    public function getLang()
    {
        return $this->registry->config->get('lang'); //get config item $config['lang']
    }   
    
    public function setLang($lang)
    {
        $this->registry->config->set('lang', $lang); //set config item $config['lang'
    } 
    
    public function setText($text_index, $value)
    {
        $this->lang[$text_index] = $value;
        return $this->lang[$text_index];
    }
    
    public function getText($text_index)
    {
        if (isset($this->lang[$text_index])) {
            return $this->lang[$text_index];
        } else {
            return null;
        }
    }
    
    public function getMeta($index)
    {
        if (isset($this->lang_meta['languages'][$this->getLang()][$index])) {
            return $this->lang_meta['languages'][$this->getLang()][$index];
        } else {
            return null;
        }
    }
    
    public function getCharset()
    {
        if (isset($this->lang_meta['languages'][$this->getLang()]['charset'])) {
            return $this->lang_meta['languages'][$this->getLang()]['charset'];
        } else {
            return null;
        }
    }
    
    public function getLanguage()
    {
        if (isset($this->lang_meta['languages'][$this->getLang()]['language'])) {
            return $this->lang_meta['languages'][$this->getLang()]['language'];
        } else {
            return null;
        }
    }
    
    public function getFlag()
    {
        if (isset($this->lang_meta['languages'][$this->getLang()]['flag'])) {
            return $this->lang_meta['languages'][$this->getLang()]['flag'];
        } else {
            return null;
        }
    }
    
    
}

