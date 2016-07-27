<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/**
 * Template Class
 *
 * Load, write and render views
 *
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Core
 * @since       Version 1.0
 */
class Template {

	protected $config;
	protected $page_vars = array();
    protected $active_theme;
    protected $active_theme_path;
    protected $output_page;

    public function __construct()
    {
        //get the values of the $config variable arrays in Congfig_*.php files
        global $config;
        $this->config  =& $config;
        
        $this->setActiveTheme($this->config['template_active_theme']);
               
	}
    
    public function setActiveTheme($theme) 
    {        
        $this->active_theme        = $theme;
        $this->active_theme_path    = DIR_APP_PATH . '/views/' . $this->active_theme;          
        $this->setRegionVars();
    }    
    
    private function setRegionVars() 
    {
        if (isset($this->config['template_region_vars'][$this->active_theme]) && is_array($this->config['template_region_vars'][$this->active_theme])) {
            foreach ($this->config['template_region_vars'][$this->active_theme] as $var) {
                $this->page_vars[$var] = '';
            }
        } 
    }
    
    private function startBuffering()
    {
        if (extension_loaded('zlib') && isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
            return ob_start('ob_gzhandler');
        } else {
            return ob_start();
		}
    }   
    
    private function endBuffering()
    {
        return ob_get_clean();
    }  
    

	public function write($var, $value, $append_data = false)
	{
        if ($append_data === true && isset($this->page_vars[$var])) {
            $this->page_vars[$var] .= $value;
        } else {
            $this->page_vars[$var] = $value;
        }
	}
    
    public function writeData($data)
	{
	   if (is_array($data)) {
	       $this->page_vars = array_merge($this->page_vars, $data);
	   }		
	}
    
    public function setOutputPage($path)
	{
        $this->output_page =  $this->active_theme_path . '/' . $path;
	}    
    
    public function writeView($var, $path, $append_data = true)
	{        
	    extract($this->page_vars);
        
        $this->startBuffering();
        
		require($this->active_theme_path . '/' . $path);
        
        $file_content =  $this->endBuffering();
        
        $this->write($var, $file_content, $append_data);
	}


	public function render($output_page_path = null)
	{
	   if (!is_null($output_page_path)) {
	       $this->setOutputPage($output_page_path);
	   }	   
       
		extract($this->page_vars);
        
        $this->startBuffering();
        
		require($this->output_page);
        
        echo  $this->endBuffering();
        
		
	}
    
}