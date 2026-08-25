<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = trim($_GET['url'] ?? '', '/');
        $url = $url === '' ? '/' : '/' . $url;

        $handler = $this->routes[$method][$url] ?? null;

        if (!$handler) {
            http_response_code(404);
            echo '404 - Página não encontrada';
            return;
        }

        [$controllerClass, $action] = $handler;
        (new $controllerClass())->$action();
    }
}