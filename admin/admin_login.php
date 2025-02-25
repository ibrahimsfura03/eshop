<?php
session_start();
include '../includes/db.php'; // Ensure your database connection is correctly included

$error = ''; // Initialize error message variable

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $admin_email = trim($_POST['admin_email']);
    $admin_password = trim($_POST['admin_password']);

    // Validate the input
    if (empty($admin_email) || empty($admin_password)) {
        $error = "Please enter both email and password.";
    } else {
        // Query the database for admin with the given email
        $query = "SELECT admin_id, admin_name, admin_email, admin_password FROM admins WHERE admin_email = '$admin_email'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            if (mysqli_num_rows($result) === 1) {
                // Admin exists, fetch the admin data
                $admin = mysqli_fetch_assoc($result);

                // Verify the entered password with the stored hashed password
                if (password_verify($admin_password, $admin['admin_password'])) {
                    // Login successful, set session variables
                    $_SESSION['admin_id'] = $admin['admin_id'];
                    $_SESSION['admin_name'] = $admin['admin_name'];
                    $_SESSION['admin_email'] = $admin['admin_email'];

                    // Redirect to the admin dashboard
                    header('Location: admindb.php');
                    exit;
                } else {
                    // Password doesn't match
                    $error = "Invalid email or password.";
                }
            } else {
                // Admin with the email not found
                $error = "Invalid email or password.";
            }
        } else {
            // Query failed
            $error = "Database query failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin_inc/style.css">
</head>
<body>

<!-- Admin Login Form Section -->
<main>
    <section class="login-form">
        <h2>Admin Login</h2>

        <?php if (!empty($error)): ?>
            <h3 class="error-message" style="color: red;"><?php echo htmlspecialchars($error); ?></h3>
        <?php endif; ?>

        <form action="" method="post">
            <label for="admin_email">Email:</label>
            <input type="email" id="admin_email" name="admin_email" placeholder="Enter your admin email" required>

            <label for="admin_password">Password:</label>
            <input type="password" id="admin_password" name="admin_password" placeholder="Enter your admin password" required>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="https://prohsite.blogspot.com/?m=1" target="_blank">Ask your superiors</a>.</p>
    </section>
</main>

</body>
</html>
