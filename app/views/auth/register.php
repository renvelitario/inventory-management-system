<?php
$pageStyles = ['auth/register.css'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!verify_csrf($_POST['_csrf'] ?? '')) {
        set_flash('error', 'Invalid session token. Please try again.');
        redirect_to('register');
    }

    // Retrieve form data
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $username = $_POST['username'] ?? '';
    $acc_type = $_POST['acc_type'] ?? '';
    $status = $_POST['status'] ?? '';

    // Check if the passwords match
    if ($password !== $confirmPassword) {
        set_flash('error', 'Passwords do not match.');
        redirect_to('register');
    } else {
        // Create a connection
        $conn = Database::getInstance()->getConnection();

        // Check if the email already exists
        $checkEmailSql = "SELECT COUNT(*) AS count FROM ims_users WHERE email = ?";
        if ($checkEmailStmt = $conn->prepare($checkEmailSql)) {
            $checkEmailStmt->bind_param("s", $email);
            $checkEmailStmt->execute();
            $checkEmailResult = $checkEmailStmt->get_result();
            $count = $checkEmailResult->fetch_assoc()["count"];

            if ($count > 0) {
                // Display an error message if the email already exists
                set_flash('error', 'Email already exists.');
                redirect_to('register');
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Prepare and execute the SQL statement to insert the new user
                $insertUserSql = "INSERT INTO ims_users (email, password, username, acc_type, status) VALUES (?, ?, ?, ?, ?)";
                if ($insertUserStmt = $conn->prepare($insertUserSql)) {
                    $insertUserStmt->bind_param("sssss", $email, $hashedPassword, $username, $acc_type, $status);

                    if ($insertUserStmt->execute()) {
                        // Display a success message if registration is successful
                        set_flash('success', 'Account added successfully.');
                        redirect_to('register');
                    } else {
                        // Display an error message if an error occurred during registration
                        set_flash('error', 'Error occurred during registration.');
                        redirect_to('register');
                    }
                    $insertUserStmt->close();
                }
            }
            $checkEmailStmt->close();
        }
    }
}

require __DIR__ . '/../layout/header.php';
?>

<div class="form-container">
    <h2>Add a New User</h2>
    <form method="POST" action="<?php echo htmlspecialchars(app_url('register'), ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>
        </div>
        <div class="form-group">
            <label>Account Type:</label>
            <select name="acc_type">
                <option value="Admin">Admin</option>
                <option value="User">User</option>
            </select>
        </div>
        <div class="form-group">
            <label>Status:</label>
            <select name="status">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" value="Register" onclick="return confirm('Are you sure you want to add this account?')">
        </div>
    </form>
</div>

</body>
</html>
