<?php
// Check if the user is not logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page
    header('Location: ' . app_url('login'));
    exit();
}

// Fetch user data from the database based on the user_id
$conn = Database::getInstance()->getConnection();
$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM ims_users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Store user data in session variables
        $_SESSION["email"] = $user["email"];
        $_SESSION["acc_type"] = $user["acc_type"];
        $_SESSION["status"] = $user["status"];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo htmlspecialchars($appName ?? env('APP_NAME', 'Inventory Management System')); ?></title>
    <?php $styleVersion = @filemtime(__DIR__ . '/../../../public/assets/css/style.css') ?: time(); ?>
    <link rel="stylesheet" href="<?php echo htmlspecialchars(app_url('assets/css/style.css'), ENT_QUOTES, 'UTF-8'); ?>?v=<?php echo $styleVersion; ?>">
    <?php
    if (isset($pageStyles) && is_array($pageStyles)) {
        foreach ($pageStyles as $pageStyle) {
            $pageStylePath = __DIR__ . '/../../../public/assets/css/' . ltrim($pageStyle, '/');
            $pageStyleVersion = @filemtime($pageStylePath) ?: time();
            echo '<link rel="stylesheet" href="' . htmlspecialchars(app_url('assets/css/' . ltrim($pageStyle, '/')), ENT_QUOTES, 'UTF-8') . '?v=' . $pageStyleVersion . '">';
        }
    }
    ?>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <nav role="navigation" class="navbar navbar-top">
        <div class="container">
            <div class="navbar-header">
                <a href="<?php echo htmlspecialchars(app_url('dashboard'), ENT_QUOTES, 'UTF-8'); ?>" class="navbar-brand">
                    <img src="<?php echo htmlspecialchars(app_url('assets/img/logo.png'), ENT_QUOTES, 'UTF-8'); ?>" class="logo-image">
                    <span class="logo-text"><?php echo htmlspecialchars($appName ?? env('APP_NAME', 'Inventory Management System')); ?></span>
                </a>
            </div>
        </div>
    </nav>

    <nav role="navigation" class="navbar navbar-bottom">
        <div class="container">
            <ul class="nav navbar-nav">
                <li><a href="<?php echo htmlspecialchars(app_url('dashboard'), ENT_QUOTES, 'UTF-8'); ?>">Home</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Products<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo htmlspecialchars(app_url('products_list'), ENT_QUOTES, 'UTF-8'); ?>">Product List</a></li>
                        <li><a href="<?php echo htmlspecialchars(app_url('products_add'), ENT_QUOTES, 'UTF-8'); ?>">Add a New Product</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Customers<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo htmlspecialchars(app_url('cust_list'), ENT_QUOTES, 'UTF-8'); ?>">Customer List</a></li>
                        <li><a href="<?php echo htmlspecialchars(app_url('cust_add'), ENT_QUOTES, 'UTF-8'); ?>">Add a New Customer</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Purchases<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo htmlspecialchars(app_url('purchases_list'), ENT_QUOTES, 'UTF-8'); ?>">Purchase List</a></li>
                        <li><a href="<?php echo htmlspecialchars(app_url('purchases'), ENT_QUOTES, 'UTF-8'); ?>">Make a Purchase</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Orders<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo htmlspecialchars(app_url('orders_list'), ENT_QUOTES, 'UTF-8'); ?>">Order List</a></li>
                        <li><a href="<?php echo htmlspecialchars(app_url('orders'), ENT_QUOTES, 'UTF-8'); ?>">Make an Order</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo htmlspecialchars(app_url('register'), ENT_QUOTES, 'UTF-8'); ?>">Add User</a></li>
                        <li><a href="<?php echo htmlspecialchars(app_url('settings'), ENT_QUOTES, 'UTF-8'); ?>">Account Settings</a></li>
                        <li><a href="<?php echo htmlspecialchars(app_url('change_pass'), ENT_QUOTES, 'UTF-8'); ?>">Security</a></li>
                        <li><a href="<?php echo htmlspecialchars(app_url('logout'), ENT_QUOTES, 'UTF-8'); ?>"
                                onclick="return confirm('Are you sure you want to log out?')">Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <?php $flash = get_flash(); ?>
    <?php if ($flash && isset($flash['message'])): ?>
        <div class="container">
            <div class="flash-message flash-<?php echo htmlspecialchars((string) ($flash['type'] ?? 'info')); ?>">
                <?php echo htmlspecialchars((string) $flash['message']); ?>
            </div>
        </div>
    <?php endif; ?>
