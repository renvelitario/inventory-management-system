<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!verify_csrf($_POST['_csrf'] ?? '')) {
        set_flash('error', 'Invalid session token. Please try again.');
        redirect_to('cust_add');
    }

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
            set_flash('success', 'Customer added successfully.');
            redirect_to('cust_add');
        } else {
            set_flash('error', 'Failed to add customer.');
            redirect_to('cust_add');
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
    <form action="<?php echo htmlspecialchars(app_url('cust_add'), ENT_QUOTES, 'UTF-8'); ?>" method="POST">
        <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
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
