<?php
// session_start() is handled by the initial router entry point

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: /IM-SYSTEM/login");
exit();
