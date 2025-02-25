<?php include 'loginsheader.php'; ?>

<head>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input
    $email = $conn->real_escape_string(trim($_POST['user_email']));
    $password = trim($_POST['user_password']);

    // Query the database for user with the given email
    $query = "SELECT user_id, user_name, user_email, user_password FROM users WHERE user_email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the entered password with the stored hashed password
        if (password_verify($password, $user['user_password'])) {
            // Login successful, set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['user_email'] = $user['user_email'];

            // Redirect to the user's dashboard
            header('Location: ../index.php');
            exit;
        } else {
            // Password does not match
            $error = 'Invalid email or password.';
        }
    } else {
        // Email not found
        $error = 'Invalid email or password.';
    }

    // Redirect back to the login page to clear form inputs
    header('Location: login.php');
    exit;
}
?>

<!-- Login Form Section -->
<main>
    <section class="login-form">
        <h2>Login to Your Account</h2>
        <?php if (isset($error)): ?>
            <h3 class="error-message" style="color: red;"><?php echo htmlspecialchars($error); ?></h3>
        <?php endif; ?>
        <form action="" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="user_email" placeholder="Enter your email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="user_password" placeholder="Enter your password" required>
            
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Register here</a>.</p>
    </section>
</main>

<?php include 'loginsfooter.php'; ?>
