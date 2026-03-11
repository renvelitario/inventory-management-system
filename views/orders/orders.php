<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = $_POST["product_id"];
    $customer_id = $_POST["customer_id"];
    $quantity = $_POST["quantity"];

    $conn = Database::getInstance()->getConnection();

    // Validate product is active and has enough stock.
    $checkQuantitySql = "SELECT quantity, status FROM ims_products WHERE product_id = ?";
    if ($checkStmt = $conn->prepare($checkQuantitySql)) {
        $checkStmt->bind_param("s", $product_id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult && $checkResult->num_rows > 0) {
            $row = $checkResult->fetch_assoc();
            $availableQuantity = $row["quantity"];
            $productStatus = strtolower(trim((string) $row["status"]));

            if ($productStatus !== "active") {
                echo '<script>alert("Cannot order inactive product.");</script>';
            } elseif (!is_numeric($quantity) || (int) $quantity <= 0) {
                echo '<script>alert("Please enter a valid quantity greater than 0.");</script>';
            } elseif ((int) $quantity <= (int) $availableQuantity) {
                // Prepare the SQL statement with placeholders
                $insertSql = "INSERT INTO ims_orders (product_id, customer_id, quantity) VALUES (?, ?, ?)";

                // Create a prepared statement
                if ($stmt = $conn->prepare($insertSql)) {
                    // Bind the parameters to the prepared statement
                    $stmt->bind_param("sss", $product_id, $customer_id, $quantity);

                    // Execute the prepared statement
                    if ($stmt->execute()) {
                        // Update the quantity in the ims_products table
                        $updateSql = "UPDATE ims_products SET quantity = quantity - ? WHERE product_id = ?";
                        if ($updateStmt = $conn->prepare($updateSql)) {
                            $updateStmt->bind_param("is", $quantity, $product_id);
                            $updateStmt->execute();
                            $updateStmt->close();
                        }

                        // Display the success message as an alert box
                        echo '<script>alert("Order added successfully.");</script>';
                    } else {
                        echo "Error: " . $conn->error;
                    }

                    $stmt->close();
                }
            } else {
                // Display an error message as an alert box
                echo '<script>alert("Insufficient quantity. Available: ' . htmlspecialchars($availableQuantity) . '");</script>';
            }
        } else {
            echo "Error: Product not found.";
        }

        $checkStmt->close();
    }
}
?>

<?php
$pageStyles = ['orders/orders.css'];
require __DIR__ . '/../layout/header.php';
?>

<div class="order-container">
    <h2>Make an Order</h2>
    <form action="/IM-SYSTEM/orders" method="POST" onsubmit="return confirm('Are you sure you want to add this order?');">
        <label for="product_id">Product ID:</label>
        <select id="product_id" name="product_id" required>
            <?php
            // Fetch products from the database
            $conn = Database::getInstance()->getConnection();

            $sql = "SELECT product_id FROM ims_products WHERE LOWER(status) = 'active'";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row["product_id"]) . "'>" . htmlspecialchars($row["product_id"]) . "</option>";
                }
            } else {
                echo "<option value=''>No products found</option>";
            }
            ?>
        </select><br>

        <label for="customer_id">Customer ID:</label>
        <select id="customer_id" name="customer_id" required>
            <?php
            // Fetch customers from the database
            $sql = "SELECT * FROM ims_customers";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row["cust_id"]) . "'>" . htmlspecialchars($row["cust_id"]) . "</option>";
                }
            } else {
                echo "<option value=''>No customers found</option>";
            }
            ?>
        </select><br>

        <label for="quantity">Quantity:</label>
        <input type="text" id="quantity" name="quantity" required><br>

        <input type="submit" value="Add Order">
    </form>
</div>

</body>
</html>