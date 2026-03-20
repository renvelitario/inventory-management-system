<?php
$pageStyles = ['dashboard.css'];
require __DIR__ . '/layout/header.php';
?>

<div class="container">
    <h2>Welcome,
        <?php echo isset($_SESSION["username"]) ? htmlspecialchars($_SESSION["username"]) : ""; ?>!
    </h2>
    <p>Email:
        <?php echo isset($_SESSION["email"]) ? htmlspecialchars($_SESSION["email"]) : ""; ?>
    </p>
    <p>Account Type:
        <?php echo isset($_SESSION["acc_type"]) ? htmlspecialchars($_SESSION["acc_type"]) : ""; ?>
    </p>
    <p>Status:
        <?php echo isset($_SESSION["status"]) ? htmlspecialchars($_SESSION["status"]) : ""; ?>
    </p>
    <hr>
    <h2>Dashboard</h2>
    <?php
    // Fetch user details from the database
    $conn = Database::getInstance()->getConnection();

    // Assuming the user ID is stored in the session
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM ims_users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row["username"];

        // Update the session variable with the updated username
        $_SESSION["username"] = $username;
    }
    $stmt->close();

    // Fetch dashboard information from the database
    // Total Products
    $sql = "SELECT COUNT(*) AS total_products FROM ims_products";
    $result = $conn->query($sql);
    $totalProducts = $result->fetch_assoc()["total_products"];

    // Low Stock Products
    $lowStockThreshold = 10; // Define your low stock threshold
    $sql = "SELECT COUNT(*) AS low_stock_products FROM ims_products WHERE quantity <= $lowStockThreshold";
    $result = $conn->query($sql);
    $lowStockProducts = $result->fetch_assoc()["low_stock_products"];

    // Out of Stock Products
    $sql = "SELECT COUNT(*) AS out_of_stock_products FROM ims_products WHERE quantity = 0";
    $result = $conn->query($sql);
    $outOfStockProducts = $result->fetch_assoc()["out_of_stock_products"];

    // Total Customers
    $sql = "SELECT COUNT(*) AS total_customers FROM ims_customers";
    $result = $conn->query($sql);
    $totalCustomers = $result->fetch_assoc()["total_customers"];

    // Total Orders
    $sql = "SELECT COUNT(*) AS total_orders FROM ims_orders";
    $result = $conn->query($sql);
    $totalOrders = $result->fetch_assoc()["total_orders"];

    // Total Purchases
    $sql = "SELECT COUNT(*) AS total_purch FROM ims_purchases";
    $result = $conn->query($sql);
    $totalPurchases = $result->fetch_assoc()["total_purch"];

    // User list
    $sqlUsers = "SELECT user_id, email, username, acc_type, status FROM ims_users ORDER BY user_id ASC";
    $resultUsers = $conn->query($sqlUsers);
    ?>

    <div class="row-container">
        <div class="statistic-box">
            <h3>Total Products</h3>
            <p><?php echo htmlspecialchars($totalProducts); ?></p>
        </div>
        <div class="statistic-box">
            <h3>Low Stock Products</h3>
            <p><?php echo htmlspecialchars($lowStockProducts); ?></p>
        </div>
        <div class="statistic-box">
            <h3>Out of Stock Products</h3>
            <p><?php echo htmlspecialchars($outOfStockProducts); ?></p>
        </div>
    </div>

    <div class="row-container">
        <div class="statistic-box">
            <h3>Total Customers</h3>
            <p><?php echo htmlspecialchars($totalCustomers); ?></p>
        </div>
        <div class="statistic-box">
            <h3>Total Orders</h3>
            <p><?php echo htmlspecialchars($totalOrders); ?></p>
        </div>
        <div class="statistic-box">
            <h3>Total Purchases</h3>
            <p><?php echo htmlspecialchars($totalPurchases); ?></p>
        </div>
    </div>

    <hr>

    <h3>User List</h3>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Email</th>
                <th>Username</th>
                <th>Account Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($resultUsers && $resultUsers->num_rows > 0) {
                while ($row = $resultUsers->fetch_assoc()) {
                    $isInactive = strtolower(trim((string) $row["status"])) === "inactive";
                    $rowClass = $isInactive ? " class='inactive-row'" : "";
                    echo "<tr" . $rowClass . ">";
                    echo "<td>" . htmlspecialchars($row["user_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["acc_type"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No users found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
