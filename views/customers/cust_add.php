<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"] ?? '';
    $address = $_POST["address"] ?? '';
    $contact_no = $_POST["contact_no"] ?? '';

    $conn = Database::getInstance()->getConnection();

    // Prepare the SQL statement with placeholders
    $sql = "INSERT INTO ims_customers (name, address, contact_no) VALUES (?, ?, ?)";
    
    // Create a prepared statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters to the prepared statement
        $stmt->bind_param("sss", $name, $address, $contact_no);
        
        // Execute the prepared statement
        if ($stmt->execute()) {
            // Display the success message as an alert box
            echo '<script>alert("Customer added successfully.");</script>';
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<?php
$pageStyles = ['customers/cust_add.css'];
require __DIR__ . '/../layout/header.php';
?>

<div class="add-customer">
    <h2>Add a Customer</h2>
    <form action="/IM-SYSTEM/cust_add" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="address">Address:</label>
        <textarea id="address" name="address" rows="4" required></textarea><br>

        <label for="contact_no">Contact No:</label>
        <input type="text" id="contact_no" name="contact_no" required><br>

        <input type="submit" value="Add Customer">
    </form>
</div>

</body>
</html>