<?php
namespace CodeNimbly\Core; 
 /**
 * Router Class
 *
 * Load, write and render views
 *
 * @package		CodeNimbly
 * @subpackage  Libraries
 * @category	Core
 * @since       Version 1.0
 */
class Router {

	protected $routes = array();
	protected $namedRoutes = array();
	protected $basePath = '';
	protected $matchTypes = array(
		'num'  => '[0-9]+',
		'alpha'  => '[a-zA-Z]+',
		'alnum'  => '[a-zA-Z0-9]+',
		'hex'  => '[a-fA-F0-9]+',
		'any'  => '.+',
		''   => '[^/\.]+'
	);

	/**
	  * Create router in one call from config.
	  *
	  * @param array $routes
	  * @param string $basePath
	  * @param array $matchTypes
      * @return void
	  */
	public function __construct( $routes = array(), $basePath = '', $matchTypes = array() ) {
		$this->addRoutes($routes);
		$this->setBasePath($basePath);
		$this->addMatchTypes($matchTypes);
	}
	
	/**
	 * Retrieves all routes.
	 * Useful if you want to process or display routes.
     * 
	 * @return array All routes.
	 */
	public function getRoutes() {
		return $this->routes;
	}

	/**
	 * Add multiple routes at once from array in the following format:
     * 
	 *   $routes[] = array($method, $route, $target, $name);
	 *   $routes[] = array($method, $route, $target, $name);
     *      
     *   OR
     * 
	 *   $routes = array(
	 *      array($method, $route, $target, $name),
	 *      array($method, $route, $target, $name)
	 *   );
	 *
	 * @param array $routes
	 * @return void
	 */
	public function addRoutes($routes){
		if(!is_array($routes)) {
			exit('Routes should be an array');
		} 
		foreach($routes as $route) { 
		    //group routing mapping
            if (isset($route['prefix'])) {
                if (!isset($route['target_prefix']) || !isset($route['group_routes'])) {
                        exit("Group Routes should have a prefix, target_prefix and routes. E.g: <br>
                            \$routes[] = array('prefix'=>'admin', 'target_prefix'=>'backend', 'routes'=> function() {<br>
                                &nbsp;&nbsp;&nbsp;&nbsp; \$routes[] = array('GET', '/', 'Controller@Method');<br>
                                &nbsp;&nbsp;&nbsp;&nbsp; \$routes[] = array('GET', '/', 'Controller@Method');<br>
                            });
                        ");
                } else {
                    $prefix         = $route['prefix'];
                    $target_prefix  = $route['target_prefix'];
                    $group_routes   = $route['group_routes'];
                    //$group_routes is a Closure Obect representing a function, get the return value
                    $group_routes   = $group_routes();
                    //loop through and map each routes in the group routes
                    foreach($group_routes as $g_route) {
                        $method = $g_route[0];
                        $_route = $g_route[1];
                        $target = $g_route[2];
                        $name   = (isset($g_route[3])) ? $g_route[3] : null;
                        // add preceeding / to prefix if noon
                        $prefix = (substr($prefix, 0, 1) != '/')?  "/$prefix" : $prefix;
                        // remove proceeding and trailing / from prefix if any
                        $prefix = (substr($prefix, -1, 1) == '/')?  substr($prefix,0,strlen($prefix)-2) : $prefix;
                        // remove preceeding / from target prefix if any
                        $target_prefix = (substr($target_prefix, 0, 1) == '/')?  substr($target_prefix,1,strlen($target_prefix)-1) : $target_prefix;
                        // add proceeding and trailing / to target prefix if noon
                        $target_prefix = (substr($target_prefix, -1, 1) != '/')?  "$target_prefix/" : $target_prefix;
                        
                        //prepend the prefix to the group routes and target prefix to the targets
                        $_route = $prefix . $_route;
                        $target = $target_prefix . $target;
                        //map a route
                        $this->map($method, $_route, $target, $name);
            		}
                }
            } else {
                //single routes mapping
                $method = $route[0];
                $_route = $route[1];
                $target = $route[2];
                $name   = (isset($route[3])) ? $route[3] : null;
                //map a route
                $this->map($method, $_route, $target, $name);
            }      
		}
        /*
        foreach($routes as $route) {
            $method = $route[0];
            $_route = $route[1];
            $target = $route[2];
            $name   = (isset($route[3])) ? $route[3] : null;
            $this->map($method, $_route, $target, $name);
		}
        */
	}

	/**
	 * Set the base path.
	 * Useful if you are running your application from a subdirectory.
     * 
     * @param string $basePath
	 * @return void
	 */
	public function setBasePath($basePath) {
		$this->basePath = $basePath;
	}

	/**
	 * Add named match types. It uses array_merge so keys can be overwritten.
	 *
	 * @param array $matchTypes The key is the name and the value is the regex.
     * @return void
	 */
	public function addMatchTypes($matchTypes) {
		$this->matchTypes = array_merge($this->matchTypes, $matchTypes);
	}

	/**
	 * Map a route to a target
	 *
	 * @param string $method One of 5 HTTP Methods, or a pipe-separated list of multiple HTTP Methods (GET|POST|PATCH|PUT|DELETE)
	 * @param string $route The route regex, custom regex must start with an @. You can use multiple pre-set regex filters, like [i:id]
	 * @param mixed $target The target where this route should point to. Can be anything.
	 * @param string $name Optional name of this route. Supply if you want to reverse route this url in your application.
     * @return boolean
	 */
	public function map($method, $route, $target, $name = null) {

		$this->routes[] = array($method, $route, $target, $name);

		if($name) {
			if(isset($this->namedRoutes[$name])) {
				exit("Can not redeclare route '{$name}'");
			} else {
				$this->namedRoutes[$name] = $route;
			}
		}
		return;
	}
    
	/**
	 * Match a given Request Url against stored routes
     * 
	 * @param string $requestUrl
	 * @param string $requestMethod
	 * @return array|boolean Array with route information on success, false on failure (no match).
	 */
	public function match($requestUrl = null, $requestMethod = null) {

		$params = array();
		$match = false;

		// set Request Url if it isn't passed as parameter
		if($requestUrl === null) {
			$requestUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
		}

		// strip base path from request url
		$requestUrl = substr($requestUrl, strlen($this->basePath));

		// Strip query string (?a=b) from Request Url
		if (($strpos = strpos($requestUrl, '?')) !== false) {
			$requestUrl = substr($requestUrl, 0, $strpos);
		}

		// set Request Method if it isn't passed as a parameter
		if($requestMethod === null) {
			$requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
		}

		// Force request_order to be GP
		// http://www.mail-archive.com/internals@lists.php.net/msg33119.html
		$_REQUEST = array_merge($_GET, $_POST);
        
  
		foreach($this->routes as $handler) { 
			list($method, $_route, $target, $name) = $handler;

			$methods = explode('|', $method);
			$method_match = false;

			// Check if request method matches. If not, abandon early. (CHEAP)
			foreach($methods as $method) {
				if (strcasecmp($requestMethod, $method) === 0) {
					$method_match = true;
					break;
				}
			}

			// Method did not match, continue to next route.
			if(!$method_match) continue;

			// Check for a wildcard (matches all)
			if ($_route === '*') {
				$match = true;
			} else { 
               
                $_route = preg_replace_callback(
                    '~{([a-zA-Z0-9_]+)}|{(.+):([a-zA-Z0-9_]+)}~U',  //check in route for {name} | {matchTypeOrRegex:name}}
                    function ($matches) {                        
                        //for {name} and will match any string
                        if (preg_match("~{([a-zA-Z0-9_]+)}~", $matches[0])) { 
                            $args_pattern = $this->matchTypes['any'];
                            $args_name = $matches[1];                            
                        
                        //{name:matchTypeOrRegex}}  
                        } elseif (preg_match("~{(.+):([a-zA-Z0-9_]+)}~", $matches[0])) {                            
                            $args_pattern = $matches[2];
                            $args_name = $matches[3];
                            //check if the pattern is a matchtype shorthand and replace with shorthand's pattern
                            if (isset($this->matchTypes[$args_pattern])) { 
                                $args_pattern = $this->matchTypes[$args_pattern];                       
                            }  
                        }                
                        return "(?P<$args_name>$args_pattern)";
                    }, 
                    $_route
                );
				$pattern = '~^' . $_route . '$~uU';
				$match = preg_match($pattern, $requestUrl, $params);
			}

			if(! $match === false) {

				if($params) {
					foreach($params as $key => $value) {
						if(is_numeric($key)) unset($params[$key]); //remove numeric index and allow only named index
					}
				}
				$match = array(
					'target' => $target,
					'params' => $params,
					'name' => $name
				);
                return $match;
			}
		}
		return false;
	}
    
    /**
     * Run URI routing and dispatching to Controller and method
     * 
     * @param array $routes
     * @param string $controllers_path
     * @param string $error_404_path
     */
    public function dispatch($routes = array(), $controllers_path, $error_404_page_path='')
    {
        //Add the url url_route arrays from the url routes files
        $this->addRoutes($routes);
        /* Match the current request */
        $match = $this->match();
        
        if($match) {    
            list($match_class_path, $match_method) = explode('@', $match['target']);
            $segments = explode('/', $match_class_path);
            if (is_array($segments)) { 
                $seg_num = count($segments);
                $match_class = $segments[$seg_num - 1];   
            } else { 
                // Do Nothing and allow file_exists() to check for existence
            }       
            //include the controller class
            $controllers_path  = $controllers_path . '/' . strtolower($match_class_path) . '.php';    
            if (file_exists($controllers_path)) {   
                //add the data to $_GET too
                $_GET[] = $match['params'];
                
                require_once($controllers_path);                 
                $objController = new $match_class;
                $objController->$match_method($match['params']);           
            } else {
                trigger_error("Controller \"$match_class::$match_method\" doesn't exist.", E_USER_ERROR);
            }   
        } else {     
            /** ERORR 404: PAGE NOT FOUND */
            header("HTTP/1.0 404 Not Found");
            
            if (!empty($error_404_page_path)) {
                require_once($error_404_page_path);
                
            } elseif (file_exists('404.php')) {                
                require_once('404.php');
                
            } else {
                trigger_error("Error 404: PAGE NOT FOUND!", E_USER_ERROR);
            }
            exit;          
        }
    }  
 }
