<?php

namespace EasyRoute;

use EasyRoute\Exceptions\RouteNotFoundException;
use EasyRoute\Exceptions\MethodNotAllowedException;

class Router
{
    protected array $routes;

    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    public function addRoute(string $method, string $path, mixed $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    /**
     * @throws RouteNotFoundException
     */
    public function match(string $method, string $url): mixed
    {
        $url = htmlspecialchars($url);
        $method = strtoupper($method);
        $routeHandlers = $this->routes[$method] ?? [];

        foreach ($routeHandlers as $route => $handler) {
            $pattern = preg_replace_callback('/{(\w+)(\/[dt])?}/', function ($matches) {
                if (isset($matches[2])) {
                    $marker = $matches[2];
                    if ($marker === '/d') {
                        return '(?<' . $matches[1] . '>\d+)';
                    } elseif ($marker === '/t') {
                        return '(?<' . $matches[1] . '>[a-zA-Z]+)';
                    }
                }
                return '(?<' . $matches[1] . '>\w+)';
            }, $route);

            $pattern = str_replace('/', '\/', $pattern);
            $pattern = '/^' . $pattern . '$/';

            if (preg_match($pattern, $url, $matches)) {
                $parameters = [];

                foreach ($matches as $key => $value) {
                    if (!is_numeric($key) && !empty($value)) {
                        $parameters[$key] = $value;
                    }
                }

                return [$handler, $parameters];
            } elseif ($route === $url) {
                return [$handler, []];
            }
        }

        throw new RouteNotFoundException('Route not found!');
    }

}