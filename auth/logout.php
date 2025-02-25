<?php
// Start the session to access session variables
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the login page (or homepage)
header("Location: login.php"); // Change this URL if you want to redirect to a different page
exit();
?>
