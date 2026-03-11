<?php
session_start();

// Autoload core classes
require_once __DIR__ . '/../src/Database.php';

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
    header("Location: /IM-SYSTEM/login");
    exit();
}

// Map routes to files (Controllers or Views)
$routes = [
    'login' => '../views/auth/login.php',
    'logout' => '../views/auth/logout.php',
    'register' => '../views/auth/register.php',
    'dashboard' => '../views/dashboard.php',
    'products_list' => '../views/products/products_list.php',
    'products_add' => '../views/products/products_add.php',
    'products_update' => '../views/products/products_update.php',
    'products_update_process' => '../views/products/products_update_process.php',
    'cust_list' => '../views/customers/cust_list.php',
    'cust_add' => '../views/customers/cust_add.php',
    'cust_update' => '../views/customers/cust_update.php',
    'cust_update_process' => '../views/customers/cust_update_process.php',
    'purchases' => '../views/purchases/purchases.php',
    'purchases_list' => '../views/purchases/purchases_list.php',
    'orders' => '../views/orders/orders.php',
    'orders_list' => '../views/orders/orders_list.php',
    'settings' => '../views/auth/settings.php',
    'change_pass' => '../views/auth/change_pass.php',
    'edit_profile' => '../views/auth/edit_profile.php'
];

// Determine base path for HTML links
$basePath = '/IM-SYSTEM/';

if (array_key_exists($route, $routes)) {
    require $routes[$route];
} else {
    // 404
    http_response_code(404);
    echo "<h1>404 Not Found</h1>";
    echo "<p>Route: " . htmlspecialchars($route) . " not found.</p>";
}
