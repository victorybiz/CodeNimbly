<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");
/** 
 * Template config 
 * 
 * @package		CodeNimbly
 * @subpackage  CodeNimbly
 * @category    Config
 * @since       Version 1.0
 */
 
/**
 * Template Active theme by default
 */
$config['template_active_theme'] = 'default';

/**
 * Template Region Variables
 * This variables are initialize with empty string content in the view when
 * no data is assigned to them to avoid undefined variable error flashing
 */
$config['template_region_vars'] = array (
    'page_title',
    'page_header',
    'page_footer',
    'meta_description',
    'meta_keywords',
    'meta_author',
);