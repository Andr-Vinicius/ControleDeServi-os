<?php
session_start();
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));

spl_autoload_register(function ($class) {
    $path = BASE_PATH . '/app/' . str_replace(['App\\', '\\'], ['', '/'], $class) . '.php';
    if (file_exists($path)) {
        require $path;
    }
});

use App\Core\Router;

$router = new Router();
require BASE_PATH . '/routes.php';
$router->dispatch();