<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    $conn = Database::getInstance()->getConnection();

    // Prepare the SQL statement with placeholders
    $insertPurchaseSql = "INSERT INTO ims_purchases (product_id, quantity) VALUES (?, ?)";

    // Create a prepared statement for inserting the purchase
    if ($insertStmt = $conn->prepare($insertPurchaseSql)) {
        // Bind the parameters to the prepared statement
        $insertStmt->bind_param("ss", $product_id, $quantity);

        // Execute the prepared statement to insert the purchase
        if ($insertStmt->execute()) {
            // Update the quantity in the ims_products table
            $updateProductSql = "UPDATE ims_products SET quantity = quantity + ? WHERE product_id = ?";
            if ($updateStmt = $conn->prepare($updateProductSql)) {
                $updateStmt->bind_param("is", $quantity, $product_id);
                $updateStmt->execute();
                $updateStmt->close();
            }

            // Display the success message as an alert box
            echo '<script>alert("Purchase added successfully.");</script>';
        } else {
            echo "Error: " . $conn->error;
        }

        $insertStmt->close();
    }
}
?>

<?php
$pageStyles = ['purchases/purchases.css'];
require __DIR__ . '/../layout/header.php';
?>

<div class="purchase-container">
    <h2>Make a Purchase</h2>
    <form action="/IM-SYSTEM/purchases" method="POST" onsubmit="return confirm('Are you sure you want to add this purchase?');">
        <label for="product_id">Product ID:</label>
        <select id="product_id" name="product_id" required>
            <?php
            // Fetch products from the database
            $conn = Database::getInstance()->getConnection();

            $sql = "SELECT * FROM ims_products";
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

        <label for="quantity">Quantity:</label>
        <input type="text" id="quantity" name="quantity" required><br>

        <input type="submit" value="Add Purchase">
    </form>
</div>

</body>
</html>