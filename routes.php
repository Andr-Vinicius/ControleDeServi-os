<?php
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ServiceController;

// User
$router->get('/', [AuthController::class, 'loginStatus']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/register', [AuthController::class, 'registerStatus']);
$router->post('/register', [AuthController::class, 'register']);

// Dashboard
$router->get('/dashboard', [DashboardController::class, 'index']);

// Services
$router->get('/service/create', [ServiceController::class, 'create']);
$router->post('/service/store', [ServiceController::class, 'store']);
$router->get('/service/edit', [ServiceController::class, 'edit']);
$router->post('/service/update', [ServiceController::class, 'update']);
$router->post('/service/delete', [ServiceController::class, 'delete']);
$router->post('/service/finish', [ServiceController::class, 'finish']);
