<?php
// session_start occurs in router, no need to start it here.
// Same for DB connection setup.

// Check if the user is not logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page
    header("Location: /IM-SYSTEM/login");
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
    <title>FEU Alabang Book Store Inventory Management System</title>
    <?php $styleVersion = @filemtime(__DIR__ . '/../../public/assets/css/style.css') ?: time(); ?>
    <link rel="stylesheet" href="/IM-SYSTEM/assets/css/style.css?v=<?php echo $styleVersion; ?>">
    <?php
    if (isset($pageStyles) && is_array($pageStyles)) {
        foreach ($pageStyles as $pageStyle) {
            $pageStylePath = __DIR__ . '/../../public/assets/css/' . ltrim($pageStyle, '/');
            $pageStyleVersion = @filemtime($pageStylePath) ?: time();
            echo '<link rel="stylesheet" href="/IM-SYSTEM/assets/css/' . htmlspecialchars(ltrim($pageStyle, '/')) . '?v=' . $pageStyleVersion . '">';
        }
    }
    ?>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <nav role="navigation" class="navbar navbar-top">
        <div class="container">
            <div class="navbar-header">
                <a href="/IM-SYSTEM/dashboard" class="navbar-brand">
                    <img src="/IM-SYSTEM/assets/img/logo.png" class="logo-image">
                    <span class="logo-text">FEU Alabang Book Store Inventory Management System</span>
                </a>
            </div>
        </div>
    </nav>

    <nav role="navigation" class="navbar navbar-bottom">
        <div class="container">
            <ul class="nav navbar-nav">
                <li><a href="/IM-SYSTEM/dashboard">Home</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Products<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/IM-SYSTEM/products_list">Product List</a></li>
                        <li><a href="/IM-SYSTEM/products_add">Add a New Product</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Customers<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/IM-SYSTEM/cust_list">Customer List</a></li>
                        <li><a href="/IM-SYSTEM/cust_add">Add a New Customer</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Purchases<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/IM-SYSTEM/purchases_list">Purchase List</a></li>
                        <li><a href="/IM-SYSTEM/purchases">Make a Purchase</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Orders<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/IM-SYSTEM/orders_list">Order List</a></li>
                        <li><a href="/IM-SYSTEM/orders">Make an Order</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/IM-SYSTEM/register">Add User</a></li>
                        <li><a href="/IM-SYSTEM/settings">Account Settings</a></li>
                        <li><a href="/IM-SYSTEM/change_pass">Security</a></li>
                        <li><a href="/IM-SYSTEM/logout"
                                onclick="return confirm('Are you sure you want to log out?')">Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>