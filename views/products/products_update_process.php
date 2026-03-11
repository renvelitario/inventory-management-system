<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <h2>Update a Product</h2>

    <?php
    // Check if the product ID and other fields are provided
    if (isset($_POST['product_id'], $_POST['product_name'], $_POST['quantity'], $_POST['price'], $_POST['status'])) {
        // Retrieve the form data
        $productID = $_POST['product_id'];
        $productName = $_POST['product_name'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $status = $_POST['status'];

        // Update the product information in the database
        $conn = Database::getInstance()->getConnection();
        $sql = "UPDATE ims_products SET product_name = ?, quantity = ?, price = ?, status = ? WHERE product_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sidsi", $productName, $quantity, $price, $status, $productID);
            $stmt->execute();
            $stmt->close();
        }

        echo '<script>alert("Product updated successfully."); window.location.href = "/IM-SYSTEM/products_list";</script>';
    } else {
        echo "Invalid request.";
    }
    ?>

</div>

</body>
</html>