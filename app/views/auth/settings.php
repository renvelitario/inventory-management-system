<?php
$pageStyles = ['auth/settings.css'];
$conn = Database::getInstance()->getConnection();
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!verify_csrf($_POST['_csrf'] ?? '')) {
        set_flash('error', 'Invalid session token. Please try again.');
        redirect_to('settings');
    }

    $email = trim((string) ($_POST["email"] ?? ''));
    $username = trim((string) ($_POST["username"] ?? ''));
    $acc_type = (string) ($_POST["acc_type"] ?? '');
    $status = (string) ($_POST["status"] ?? '');
    $password = (string) ($_POST["password"] ?? '');

    $verifySql = "SELECT password FROM ims_users WHERE user_id = ?";
    if ($verifyStmt = $conn->prepare($verifySql)) {
        $verifyStmt->bind_param("i", $user_id);
        $verifyStmt->execute();
        $verifyResult = $verifyStmt->get_result();

        if (!$verifyResult || $verifyResult->num_rows === 0) {
            $verifyStmt->close();
            set_flash('error', 'User not found.');
            redirect_to('settings');
        }

        $stored = $verifyResult->fetch_assoc();
        $verifyStmt->close();

        if (!password_verify($password, $stored['password'])) {
            set_flash('error', 'Incorrect password. Account details not updated.');
            redirect_to('settings');
        }

        $updateSql = "UPDATE ims_users SET email=?, username=?, acc_type=?, status=? WHERE user_id=?";
        if ($updateStmt = $conn->prepare($updateSql)) {
            $updateStmt->bind_param("ssssi", $email, $username, $acc_type, $status, $user_id);
            $updateStmt->execute();

            if ($updateStmt->affected_rows > 0) {
                set_flash('success', 'Account details updated successfully.');
            } else {
                set_flash('info', 'No account changes were saved.');
            }

            $updateStmt->close();
            redirect_to('settings');
        }
    }

    set_flash('error', 'Unable to update account details.');
    redirect_to('settings');
}

$email = '';
$username = '';
$acc_type = 'User';
$status = 'Active';

$sql = "SELECT email, username, acc_type, status FROM ims_users WHERE user_id = ?";
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
    }

    $stmt->close();
}

require __DIR__ . '/../layout/header.php';
?>

<div class="settings-container">
    <h2>Account Settings</h2>

    <div class="edit-account">
        <h3>Edit Account Details</h3>
        <form id="updateForm" action="<?php echo htmlspecialchars(app_url('settings'), ENT_QUOTES, 'UTF-8'); ?>" method="POST">
            <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
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
    </div>
</div>

</body>
</html>
