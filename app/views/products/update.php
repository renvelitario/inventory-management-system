<?php
if (!verify_csrf($_POST['_csrf'] ?? '')) {
    set_flash('error', 'Invalid session token. Please try again.');
    redirect_to('products_list');
}

if (isset($_POST['product_id'], $_POST['product_name'], $_POST['quantity'], $_POST['price'], $_POST['status'])) {
    $productID = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $conn = Database::getInstance()->getConnection();
    $sql = "UPDATE ims_products SET product_name = ?, quantity = ?, price = ?, status = ? WHERE product_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sidsi", $productName, $quantity, $price, $status, $productID);
        $stmt->execute();
        $stmt->close();
    }

    set_flash('success', 'Product updated successfully.');
    redirect_to('products_list');
}

set_flash('error', 'Invalid request.');
redirect_to('products_list');
