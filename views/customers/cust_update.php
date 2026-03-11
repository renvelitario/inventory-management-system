<?php
$pageStyles = ['customers/cust_update.css'];
require __DIR__ . '/../layout/header.php';
?>

<div class="update-customer-container">
    <h2>Update Customer</h2>

    <?php
    // Check if the customer ID is provided
    if (isset($_GET['cust_id'])) {
        // Retrieve the customer ID from the query parameter
        $customerID = $_GET['cust_id'];

        // Fetch the customer information from the database based on the customer ID
        $conn = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM ims_customers WHERE cust_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $customerID);
            $stmt->execute();
            $result = $stmt->get_result();
            $customer = $result->fetch_assoc();
            $stmt->close();

            // Check if the customer exists
            if ($customer) {
                // Display the edit form with pre-filled data
                ?>
                <form method="POST" action="/IM-SYSTEM/cust_update_process" class="update-customer-form">
                    <input type="hidden" name="cust_id" value="<?php echo htmlspecialchars($customer['cust_id']); ?>">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($customer['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <textarea name="address" required><?php echo htmlspecialchars($customer['address']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="contact_no">Contact No:</label>
                        <input type="text" name="contact_no" value="<?php echo htmlspecialchars($customer['contact_no']); ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Update">
                    </div>
                </form>
                <?php
            } else {
                echo "Customer not found.";
            }
        }
    } else {
        echo "Invalid request.";
    }
    ?>

</div>

</body>
</html>