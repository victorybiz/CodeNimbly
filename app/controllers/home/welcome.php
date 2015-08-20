<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");

class Welcome extends Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index($params = array())
    {                 
        $this->template->write('page_title', get_config('name') . ' - ' . get_config('slogan'));
        $this->template->write('meta_description', get_config('meta_description'));
        $this->template->write('meta_keywords', get_config('meta_keywords'));
        $this->template->write('meta_author', get_config('meta_author'));
        
        $this->template->write('my_data', rand(1000000, 9999999)); //data will be passed to variable $my_data in the view
        
        $this->template->writeView('page_header', 'shared/header.html.php');
        $this->template->writeView('page_footer', 'shared/footer.html.php');      
        $this->template->render('home/welcome.html.php');
    }
}