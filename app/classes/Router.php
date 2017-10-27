<?php

class Router
{
  protected $routes = [];
  protected $allowedMethods = ['GET', 'POST'];
  
  public function __construct()
  {
    
  }
  
  public function addRoute($method, $uri, $handler)
  {
    $method = strtoupper($method);
    if (!in_array($method, $this->allowedMethods)) {
      throw new Exception("Метод {$method} не найден");
    }
    
    $this->routes[] = [
      'method' => $method,
      'uri' => $uri,
      'handler' => $handler
    ];
  }
  
  public function get($uri, $handler)
  {
    $this->addRoute('GET', $uri, $handler);
  }
  
  public function post($uri, $handler)
  {
    $this->addRoute('POST', $uri, $handler);
  }
  
  public function handle()
  {
    $data = parse_url($_SERVER['REQUEST_URI']);
    $uri = $data['path'];
    $method = strtoupper($_SERVER['REQUEST_METHOD']);
    
    foreach ($this->routes as $route) {
      if ($route['method'] == $method && $route['uri'] == $uri) {
        return $this->execute($route['handler']);
      }
    }
    
    return Response::send('error', [], "Роут {$uri} с методом {$method} не был найден");
  }
  
  public function execute($handler)
  {
    return call_user_func($handler);
  }
}