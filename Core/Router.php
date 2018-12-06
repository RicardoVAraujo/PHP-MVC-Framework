<?php

namespace Core;

class Router {
  /**
   * Array of different routes
   * @var array
   */
  protected $routes = [];

  /**
   * Parameters for each route
   * @var array
   */
  protected $params = [];

  /**
   * Add a new route
   * @param string $route  The route url
   * @param array $params Parameters (controller, action, etc.)
   *
   * @return void
   */
  public function add($route, $params = []) {
    $route = preg_replace('/\//', '\\/', $route);
    $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
    $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
    $route = '/^' . $route . '$/i';

    $this->routes[$route] = $params;
  }

  /**
   * Get all routes
   * @method getRoutes
   * @return array    Returns all routes
   */
  public function getRoutes() {
    return $this->routes;
  }

  /**
   * Get parameters of current route
   * @method getParams
   * @return array    List of parameters
   */
  public function getParams() {
    return $this->params;
  }

  /**
   * Matches the requested url with the available routes.
   * In case of a match it sets the $params property with the params of the request.
   * @method match
   * @param  string $url The route url
   * @return boolean      true if match, else false
   */
  public function match($url) {
    foreach($this->routes as $route => $params) {
	  // Regex matching
      if(preg_match($route, $url, $matches)) {
        foreach($matches as $key => $match) {
          if(is_string($key)) {
            $params[$key] = $match;
          }
        }

        $this->params = $params;
        return true;
      }
    }

    return false;
  }

 /**
 * Receives current query string and compares it with available routes
 *
 * @param url string   Current query string
 */
  public function dispatch($url) {
    // Strip query string values (such as ?page=6&start=5) to match
    // it with the routing table.
    $url = $this->removeQueryStringVariables($url);

	// Compare with routing table
    if($this->match($url)) {
	  // Get controller, convert it to studly caps, and gets the namespace of the controller
      $controller = $this->params['controller'];
      $controller = $this->convertToStudlyCaps($controller);
      $controller = $this->getNamespace($controller)."$controller";

	  // If controller exists, create an instance
      if(class_exists($controller)) {
        $controller_object = new $controller($this->params);

		// Get action from URL and convert to camelcase
        $action = $this->params['action'];
        $action = $this->convertToCamelCase($action);

		// Check if action in URL contains no 'action' (since all methods in the controllers are named with 'Action')
        if(preg_match('/action$/i', $action) == 0) {
            $controller_object->$action();
        } else {
            throw new \Exception("Method $action (in controller $controller) not allowed.");
        }
      }
      else {
        throw new \Exception("Controller class $controller not found");
      }
    }
    else {
      throw new \Exception("No route matched", 404);
    }
  }

 /**
 * Converts controller name to namespace
 *
 * @return string namespace name
 */ 
  private function getNamespace() {
    $namespace = 'App\Controllers\\';

    if(array_key_exists('namespace', $this->params)) {
      $namespace .= $this->params['namespace'] . '\\';
    }

    return $namespace;
  }

  private function convertToStudlyCaps($string) {
    // Convert all '-' to spaces, uppercase every word, replace spaces by ''
    return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
  }

  private function convertToCamelCase($string) {
    return lcfirst($this->convertToStudlyCaps($string));
  }

  
 /**
 * Removes query string variables so that the query string can be compared to the routing table
 *
 * @return string query string
 */ 
  private function removeQueryStringVariables($url) {
    if($url != '') {
      // Split the url at the first '&' occurence into two parts
      // Example:
      // [0] => admin/{action}/{controller}
      // [1] => test=1&page=2&wow=4&show=4
      $parts = explode('&', $url, 2);

      // If the first part contains no '=' -> return it
      // Else -> return empty string
      if(strpos($parts[0], '=') === false) {
        $url = $parts[0];
      }
      else {
        $url = '';
      }
    }

    return $url;
  }
}
