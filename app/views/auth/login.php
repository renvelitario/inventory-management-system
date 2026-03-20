<?php
// Define the error variable
$error = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!verify_csrf($_POST['_csrf'] ?? '')) {
        set_flash('error', 'Invalid session token. Please try again.');
        redirect_to('login');
    }

    // Retrieve form data
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Create a connection
    $conn = Database::getInstance()->getConnection();

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT * FROM ims_users WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the user exists
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user["password"])) {
                // Set session variables
                session_regenerate_id(true);
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["username"] = $user["username"];
                set_flash('success', 'Welcome back.');

                // Redirect to the index page
                redirect_to('dashboard');
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
        $stmt->close();
    } else {
        $error = "Database query failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - <?php echo htmlspecialchars($appName ?? env('APP_NAME', 'Inventory Management System')); ?></title>
    <?php $formStyleVersion = @filemtime(__DIR__ . '/../../../public/assets/css/form.css') ?: time(); ?>
    <link rel="stylesheet" href="<?php echo htmlspecialchars(app_url('assets/css/form.css'), ENT_QUOTES, 'UTF-8'); ?>?v=<?php echo $formStyleVersion; ?>">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="<?php echo htmlspecialchars(app_url('assets/img/logo.png'), ENT_QUOTES, 'UTF-8'); ?>" alt="Logo">
        </div>
        <h2><?php echo htmlspecialchars($appName ?? env('APP_NAME', 'Inventory Management System')); ?></h2>
        <?php $flash = get_flash(); ?>
        <?php if ($flash && isset($flash['message'])): ?>
            <div class="flash-message flash-<?php echo htmlspecialchars((string) ($flash['type'] ?? 'info')); ?>">
                <?php echo htmlspecialchars((string) $flash['message']); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars(app_url('login'), ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
            <br>
            <label>Email:</label>
            <input type="email" name="email" required><br>
            <label>Password:</label>
            <input type="password" name="password" required><br>
            <br>
            <input type="submit" value="Log In">
            <?php if (!empty($error)) { ?>
                <p class="error">
                    <?php echo htmlspecialchars($error); ?>
                </p>
            <?php } ?>
        </form>
        <br>
    </div>
</body>
</html>
