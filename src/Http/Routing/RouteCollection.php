<?php

namespace App\Http\Routing;
use Exception;

class RouteCollection
{
    private array $routes = [];

    public function __construct(string $filePath){
        $this->loadFromFile($filePath);
    }
    
    public function add(string $method, string $path, callable|array $handler): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    private function loadFromFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new Exception("Routes file not found");
        }
        $routes = require $filePath;
        if (!is_array($routes)) {
            throw new Exception("File route must be an array");
        }
        foreach ($routes as $route){
            if (!isset($route['method'], $route['path'], $route['handler'])) {
                throw new Exception("Route not valid");
            }
            $this->add($route['method'], $route['path'], $route['handler']);
        }
    }
    
}