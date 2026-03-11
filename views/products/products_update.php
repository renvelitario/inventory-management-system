<?php
$pageStyles = ['products/products_update.css'];
require __DIR__ . '/../layout/header.php';
?>

<div class="update-product">
    <h2>Update a Product</h2>

    <?php
    // Check if the product ID is provided
    if (isset($_GET['product_id'])) {
        // Retrieve the product ID from the query parameter
        $productID = $_GET['product_id'];

        // Fetch the product information from the database based on the product ID
        $conn = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM ims_products WHERE product_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $productID);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            $stmt->close();

            // Check if the product exists
            if ($product) {
                // Display the edit form with pre-filled data
                ?>
                <form method="POST" action="/IM-SYSTEM/products_update_process" class="update-form">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                    <label>Product Name:</label>
                    <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required><br><br>
                    <label>Quantity:</label>
                    <input type="number" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required><br><br>
                    <label>Price:</label>
                    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br><br>
                    <label>Status:</label>
                    <select name="status" required>
                        <option value="active" <?php echo $product['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $product['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select><br><br>
                    <input type="submit" value="Update">
                </form>
                <?php
            } else {
                echo "Product not found.";
            }
        }
    } else {
        echo "Invalid request.";
    }
    ?>

</div>

</body>
</html>