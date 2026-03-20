<?php
session_start();

require_once __DIR__ . '/../config/env.php';
loadEnv(__DIR__ . '/../.env');

$debugEnabled = strtolower((string) env('APP_DEBUG', 'false')) === 'true';
if ($debugEnabled) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
}

$appName = env('APP_NAME', 'Inventory Management System');

// Autoload core classes
require_once __DIR__ . '/../src/Database.php';

// Get database connection
$conn = Database::getInstance()->getConnection();

// Parse route
$route = $_GET['route'] ?? 'index';
$route = rtrim($route, '/');
$route = str_replace('.php', '', $route);

// Normalize 'index' to 'dashboard' if logged in, otherwise 'login'
if ($route === 'index' || $route === '') {
    if (isset($_SESSION["user_id"])) {
        $route = 'dashboard';
    } else {
        $route = 'login';
    }
}

// Basic Authentication check
$publicRoutes = ['login', 'register'];
if (!isset($_SESSION['user_id']) && !in_array($route, $publicRoutes)) {
    header('Location: ' . app_url('login'));
    exit();
}

// Map routes to view files
$routes = [
    'login' => '../app/views/auth/login.php',
    'logout' => '../app/views/auth/logout.php',
    'register' => '../app/views/auth/register.php',
    'dashboard' => '../app/views/dashboard.php',
    'products_list' => '../app/views/products/index.php',
    'products_add' => '../app/views/products/create.php',
    'products_update' => '../app/views/products/edit.php',
    'products_update_process' => '../app/views/products/update.php',
    'cust_list' => '../app/views/customers/index.php',
    'cust_add' => '../app/views/customers/create.php',
    'cust_update' => '../app/views/customers/edit.php',
    'cust_update_process' => '../app/views/customers/update.php',
    'purchases' => '../app/views/purchases/create.php',
    'purchases_list' => '../app/views/purchases/index.php',
    'orders' => '../app/views/orders/create.php',
    'orders_list' => '../app/views/orders/index.php',
    'settings' => '../app/views/auth/settings.php',
    'change_pass' => '../app/views/auth/change-password.php'
];

if (array_key_exists($route, $routes)) {
    require $routes[$route];
} else {
    // 404
    http_response_code(404);
    echo "<h1>404 Not Found</h1>";
    echo "<p>Route: " . htmlspecialchars($route) . " not found.</p>";
}
