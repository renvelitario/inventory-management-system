<?php
$pageStyles = ['auth/settings.css'];
require __DIR__ . '/../layout/header.php';
?>

<div class="settings-container">
    <h2>Account Settings</h2>

    <div class="edit-account">
        <h3>Edit Account Details</h3>
        <?php
        // Fetch the user details from the database
        $conn = Database::getInstance()->getConnection();

        // Assuming the user ID is stored in the session
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT * FROM ims_users WHERE user_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $email = $row["email"];
                $username = $row["username"];
                $acc_type = $row["acc_type"];
                $status = $row["status"];

                if ($_SERVER["REQUEST_METHOD"] === "POST") {
                    // Retrieve updated form data
                    $email = $_POST["email"];
                    $username = $_POST["username"];
                    $acc_type = $_POST["acc_type"];
                    $status = $_POST["status"];
                    $password = $_POST["password"];

                    // Check if the entered password is correct
                    $storedPassword = $row["password"];
                    if (password_verify($password, $storedPassword)) {
                        // Perform the update
                        $updateSql = "UPDATE ims_users SET email=?, username=?, acc_type=?, status=? WHERE user_id=?";
                        if ($updateStmt = $conn->prepare($updateSql)) {
                            $updateStmt->bind_param("ssssi", $email, $username, $acc_type, $status, $user_id);
                            $updateStmt->execute();

                            if ($updateStmt->affected_rows > 0) {
                                // Redirect to the same page with success parameter after successful update
                                header("Location: /IM-SYSTEM/settings?success=true");
                                exit();
                            } else {
                                // Display an error message
                                echo "<script>alert('Failed to update account details or no changes made.');</script>";
                            }
                            $updateStmt->close();
                        }
                    } else {
                        // Display an error message
                        echo "<script>alert('Incorrect password. Account details not updated.');</script>";
                    }
                }
                ?>

                <form id="updateForm" action="/IM-SYSTEM/settings" method="POST">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="acc_type">Account Type:</label>
                        <select id="acc_type" name="acc_type" required>
                            <option value="Admin" <?= $acc_type === 'Admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="User" <?= $acc_type === 'User' ? 'selected' : '' ?>>User</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select id="status" name="status" required>
                            <option value="Active" <?= $status === 'Active' ? 'selected' : '' ?>>Active</option>
                            <option value="Inactive" <?= $status === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Enter Password for Confirmation:</label>
                        <input type="password" id="confirm_password" name="password" required>
                    </div>

                    <div class="form-group">
                        <input type="submit" value="Save">
                    </div>
                </form>
            <?php
            } else {
                echo "User not found.";
            }

            $stmt->close();
        }
        ?>
    </div>
</div>

<script>
    // Check if the URL contains a query parameter indicating a successful change
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');
    if (success === 'true') {
        alert("Account details updated successfully.");
    }
</script>

</body>
</html>