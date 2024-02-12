<?php

namespace Framework;

use App\Controllers\ErrorController;

class Router
{
  protected $routes = [];
  /**
   * Registers a new route.
   *
   * @param string $method The HTTP method for the route.
   * @param string $uri The URI pattern for the route.
   * @param string $action The action for the route.
   * @return void
   */
  public function registerRoute($method, $uri, $action)
  {
    list($controller, $controllerMethod) = explode('@', $action);

    $this->routes[] = [
      'method' => $method,
      'uri' => $uri,
      'controller' => $controller,
      'controllerMethod' => $controllerMethod
    ];
  }


  /**
   * Add a Get route
   * 
   * @param string $uri
   * @param string $controller
   * @return void
   */

  public function get($uri, $controller)
  {
    $this->registerRoute('GET', $uri, $controller);
  }

  /**
   * Add a POST route
   * 
   * @param string $uri
   * @param string $controller
   * @return void
   */

  public function post($uri, $controller)
  {
    $this->registerRoute('POST', $uri, $controller);
  }

  /**
   * Add a Put route
   * 
   * @param string $uri
   * @param string $controller
   * @return void
   */

  public function put($uri, $controller)
  {
    $this->registerRoute('PUT', $uri, $controller);
  }

  /**
   * Add a Delete route
   * 
   * @param string $uri
   * @param string $controller
   * @return void
   */

  public function delete($uri, $controller)
  {
    $this->registerRoute('DELETE', $uri, $controller);
  }

  /**
   * Route the request
   * 
   * @param string $uri
   * @param string $method
   * @return void
   */

  public function route($uri)
  {
    $request_method = $_SERVER['REQUEST_METHOD'];

    foreach ($this->routes as $route) {

      //Split the current uri into segments
      $uriSegments = explode('/', trim($uri, '/'));

      //Split the route uri into segments
      $routeSegments = explode('/', trim($route['uri'], '/'));

      $match = true;

      // Check if hte number of segments matches
      if (count($uriSegments) === count($routeSegments) && strtoupper($route['method'] === $request_method)) {
        $params = [];

        $match = true;

        for ($i = 0; $i < count($uriSegments); $i++) {
          // If the URIs do not match and there is no parameter in the current segment
          if ($routeSegments[$i] !== $uriSegments[$i] && !preg_match('/\{(.+?)\}/', $routeSegments[$i])) {
            $match = false;
            break;
          }
          // Check for the param and add to $params array
          if (preg_match('/\{(.+?)\}/', $routeSegments[$i], $matches)) {
            $params[$matches[1]] = $uriSegments[$i];
          }
        }

        if ($match) {
          // Extract controller and controller method
          $controller = "App\\Controllers\\" . $route['controller'];
          $controllerMethod = $route['controllerMethod'];

          // Instantiate the controller and call the method
          $controllerInstance = new $controller;
          $controllerInstance->$controllerMethod($params);
          return;
        }

      } else {

      }
    }
    ErrorController::notFound();
  }

}