<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_name = $_POST["product_name"] ?? '';
    $quantity = $_POST["quantity"] ?? 0;
    $price = $_POST["price"] ?? 0.0;
    $status = $_POST["status"] ?? 'active';

    $conn = Database::getInstance()->getConnection();

    // Prepare the SQL statement with placeholders
    $sql = "INSERT INTO ims_products (product_name, quantity, price, status) VALUES (?, ?, ?, ?)";
    
    // Create a prepared statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters to the prepared statement
        $stmt->bind_param("sids", $product_name, $quantity, $price, $status);
        
        // Validate input
        if ($quantity < 0 || $price < 0) {
            echo '<script>alert("Quantity and price cannot be negative.");</script>';
        } elseif ($stmt->execute()) {
            // Display the success message as an alert box
            echo '<script>alert("Product added successfully.");</script>';
        } else {
            echo "Error: " . $conn->error;
        }

        $stmt->close();
    }
}
?>

<?php
$pageStyles = ['products/products_add.css'];
require __DIR__ . '/../layout/header.php';
?>

<div class="product-container">
    <h2>Add a Product</h2>
    <form action="/IM-SYSTEM/products_add" method="POST">
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>

        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" required>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>

        <input type="submit" value="Add Product">
    </form>
</div>

</body>
</html>