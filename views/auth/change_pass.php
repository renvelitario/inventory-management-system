<?php
$pageStyles = ['auth/change_pass.css'];
require __DIR__ . '/../layout/header.php';
?>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $currentPassword = $_POST["current_password"];
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    // Create a connection
    $conn = Database::getInstance()->getConnection();

    // Retrieve the user's current password from the database
    $userId = $_SESSION["user_id"];
    $stmt = $conn->prepare("SELECT * FROM ims_users WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $currentHashedPassword = $user["password"];

        // Verify the current password
        if (password_verify($currentPassword, $currentHashedPassword)) {
            // Check if the new password and confirm password match
            if ($newPassword === $confirmPassword) {
                // Hash the new password
                $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update the password in the database
                $updateStmt = $conn->prepare("UPDATE ims_users SET password = ? WHERE user_id = ?");
                if ($updateStmt) {
                    $updateStmt->bind_param("si", $newHashedPassword, $userId);
                    if ($updateStmt->execute()) {
                        // Display success message
                        echo '<script>alert("Password changed successfully.");</script>';
                    } else {
                        echo "Error updating password: " . $conn->error;
                    }
                    $updateStmt->close();
                }
            } else {
                echo '<script>alert("New password and confirm password do not match.");</script>';
            }
        } else {
            echo '<script>alert("Invalid current password.");</script>';
        }

        $stmt->close();
    }
}
?>

<div class="settings-container">
    <h2>Security</h2>
    <div class="change-password">
        <h3>Change Password</h3>
        <form action="/IM-SYSTEM/change_pass" method="POST">
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" minlength="4" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" minlength="4" required>
            </div>

            <div class="form-group">
                <input type="submit" value="Save"
                    onclick="return confirm('Are you sure you want to change your password? This action cannot be undone.');">
            </div>
        </form>
    </div>
</div>

</body>

</html>