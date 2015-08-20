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
	protected $named_routes = array();
	protected $base_path = '';
	protected $match_types = array(
		'num'  => '[0-9]+',
		'alpha'  => '[a-zA-Z]+',
		'alnum'  => '[a-zA-Z0-9]+',
		'hex'  => '[a-fA-F0-9]+',
		'any'  => '.+',
		''   => '[^/\.]+'
	);

	/**
      * The constructor.
	  * Create router with configurations as params.
	  *
	  * @param array $routes
	  * @param string $base_path
	  * @param array $match_types
      * @return void
	  */
	public function __construct( $routes = array(), $base_path = '', $match_types = array() ) {
		$this->addRoutes($routes);
		$this->setBasePath($base_path);
		$this->addMatchTypes($match_types);
	}
    

	/**
	 * Add multiple routes at once from array in the following format:
     * 
	 *   $routes[] = array($method, $route, $target);
	 *   $routes[] = array($method, $route, $target);
     *      
     *   OR
     * 
	 *   $routes = array(
	 *      array($method, $route, $target),
	 *      array($method, $route, $target)
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
            $method = $route[0];
            $_route = $route[1];
            $target = $route[2];
            $this->map($method, $_route, $target);
		}
	}

	/**
	 * Set the base path.
	 * Needed if you are running your application from a subdirectory.
     * 
     * @param string $base_path
	 * @return void
	 */
	public function setBasePath($base_path) {
		$this->base_path = $base_path;
	}

	/**
	 * Add named match types. 
	 *
	 * @param array $match_types The key is the name and the value is the regex.
     * @return void
	 */
	public function addMatchTypes($match_types) {
		$this->match_types = array_merge($this->match_types, $match_types);
	}

	/**
	 * Map a route to a target
	 *
	 * @param string $method One of 5 HTTP Methods, or a pipe-separated list of multiple HTTP Methods (GET|POST|PATCH|PUT|DELETE)
	 * @param string $route The route regex like {id}, {[0-9]+:id}. You can use multiple pre-set regex filters, like {num:id},{alpha:fname} 
	 * @param mixed $target The target where this route should point to. (The route controller)
     * @return boolean
	 */
	public function map($method, $route, $target) {

		$this->routes[] = array($method, $route, $target);
		return;
	}
    
	/**
	 * Match a given Request Url against stored routes
     * 
	 * @param string $request_url
	 * @param string $request_method
	 * @return array|boolean Array with route information on success, false on failure (no match).
	 */
	public function match($request_url = null, $request_method = null) {

		$params = array();
		$match = false;

		// set Request Url if it isn't passed as parameter
		if($request_url === null) {
			$request_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
		}

		// strip base path from request url
		$request_url = substr($request_url, strlen($this->base_path));

		// Strip query string (?a=b) from Request Url
		if (($strpos = strpos($request_url, '?')) !== false) {
			$request_url = substr($request_url, 0, $strpos);
		}

		// set Request Method if it isn't passed as a parameter
		if($request_method === null) {
			$request_method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
		}

		// Force request_order to be GP
		// http://www.mail-archive.com/internals@lists.php.net/msg33119.html
		$_REQUEST = array_merge($_GET, $_POST);
        
  
		foreach($this->routes as $handler) { 
			list($method, $_route, $target) = $handler;

			$methods = explode('|', $method);
			$method_match = false;

			// Check if request method matches. If not, abandon early.
			foreach($methods as $method) {
				if (strcasecmp($request_method, $method) === 0) {
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
                            $args_pattern = $this->match_types['any'];
                            $args_name = $matches[1];                            
                        
                        //{name:matchTypeOrRegex}}  
                        } elseif (preg_match("~{(.+):([a-zA-Z0-9_]+)}~", $matches[0])) {                            
                            $args_pattern = $matches[2];
                            $args_name = $matches[3];
                            //check if the pattern is a matchtype shorthand and replace with shorthand's pattern
                            if (isset($this->match_types[$args_pattern])) { 
                                $args_pattern = $this->match_types[$args_pattern];                       
                            }  
                        }                
                        return "(?P<$args_name>$args_pattern)";
                    }, 
                    $_route
                );
				$pattern = '~^' . $_route . '$~uU';
				$match = preg_match($pattern, $request_url, $params);
			}

			if(! $match === false) {

				if($params) {
					foreach($params as $key => $value) {
						if(is_numeric($key)) unset($params[$key]); //remove numeric index and allow only named index
					}
				}
				$match = array(
					'target' => $target,
					'params' => $params
				);
                return $match;
			}
		}
		return false;
	}
    
 }
