<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <h2>Update Customer</h2>

    <?php
    // Check if the customer ID and other fields are provided
    if (isset($_POST['cust_id'], $_POST['name'], $_POST['address'], $_POST['contact_no'])) {
        // Retrieve the form data
        $customerID = $_POST['cust_id'];
        $name = $_POST['name'];
        $address = $_POST['address'];
        $contactNo = $_POST['contact_no'];

        // Update the customer information in the database
        $conn = Database::getInstance()->getConnection();
        $sql = "UPDATE ims_customers SET name = ?, address = ?, contact_no = ? WHERE cust_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssi", $name, $address, $contactNo, $customerID);
            $stmt->execute();
            $stmt->close();
        }

        echo '<script>alert("Customer updated successfully."); window.location.href = "/IM-SYSTEM/cust_list";</script>';
    } else {
        echo "Invalid request.";
    }
    ?>

</div>

</body>
</html>