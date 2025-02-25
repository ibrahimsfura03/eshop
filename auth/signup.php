<?php include 'loginsheader.php'; ?>

<head>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input
    $user_name = $conn->real_escape_string(trim($_POST['user_name']));
    $user_email = $conn->real_escape_string(trim($_POST['user_email']));
    $user_password = password_hash(trim($_POST['user_password']), PASSWORD_DEFAULT); // Hash password securely

    // Check if the email is already registered
    $query = "SELECT * FROM users WHERE user_email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        $error = 'This email is already registered. Please use a different email.';
    } else {
        // Insert new user into the database
        $query = "INSERT INTO users (user_name, user_email, user_password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $user_name, $user_email, $user_password);

        if ($stmt->execute()) {
            // Registration successful
            $success = 'Account created successfully. You can now log in.';
        } else {
            // Database insertion error
            $error = 'An error occurred. Please try again later.';
        }
    }
}
?>

<main>
    <section class="signup-form">
        <h2>Create an Account</h2>

        <!-- Display success or error messages -->
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php elseif (isset($success)): ?>
            <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form action="signup.php" method="post">
            <label for="user_name">Full Name:</label>
            <input type="text" id="user_name" name="user_name" placeholder="Enter your name" required>

            <label for="user_email">User Email:</label>
            <input type="email" id="user_email" name="user_email" placeholder="Enter your email" required>

            <label for="user_password">Password:</label>
            <input type="password" id="user_password" name="user_password" placeholder="Enter your password" required>

            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </section>
</main>

<?php include 'loginsfooter.php'; ?>

</body>
</html>
