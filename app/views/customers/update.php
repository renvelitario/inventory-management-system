<?php
if (!verify_csrf($_POST['_csrf'] ?? '')) {
    set_flash('error', 'Invalid session token. Please try again.');
    redirect_to('cust_list');
}

if (isset($_POST['cust_id'], $_POST['name'], $_POST['address'], $_POST['contact_no'])) {
    $customerID = $_POST['cust_id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $contactNo = $_POST['contact_no'];

    $conn = Database::getInstance()->getConnection();
    $sql = "UPDATE ims_customers SET name = ?, address = ?, contact_no = ? WHERE cust_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssi", $name, $address, $contactNo, $customerID);
        $stmt->execute();
        $stmt->close();
    }

    set_flash('success', 'Customer updated successfully.');
    redirect_to('cust_list');
}

set_flash('error', 'Invalid request.');
redirect_to('cust_list');
