<?php

namespace App\Http\Routing;

use App\Http\Request;
use App\Http\Responsejson;
use Doctrine\ORM\EntityManagerInterface;

class Router {
    private RouteCollection $routeCollection;

    public function __construct(RouteCollection $routeCollection) {
        $this->routeCollection = $routeCollection;
    }

    public function dispatch(Request $request, EntityManagerInterface $em): void {
        $routes = $this->routeCollection->getRoutes();
        $found = false;

        foreach ($routes as $route) {
            if (
                $route['method'] === strtoupper($request->getMethod())
                && $this->matchUri($route['path'], $request->getUri(), $params)
            ) {
                $found = true;

                if (!empty($route['middleware']) && in_array('auth', $route['middleware'])) {
                    $middleware = new \App\Http\Middleware\AuthMiddleware();
                    if (!$middleware->handle()) {
                        return;
                    }
                }

                [$controllerClass, $action] = $route['handler'];
                $controller = new $controllerClass($request, $em);
                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        if (!$found) {
            (new Responsejson(404, ['msg' => 'Route not found']))->send();
        }
    }

    private function matchUri(string $routePath, string $requestUri, &$params): bool {
        $pattern = preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $routePath);
        $pattern = "#^" . $pattern . "$#";
        if (preg_match($pattern, $requestUri, $matches)) {
            $params = array_filter($matches, fn($key) => is_string($key), ARRAY_FILTER_USE_KEY);
            return true;
        }
        return false;
    }
}
