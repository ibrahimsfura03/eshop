<?php
include 'admin_inc/admin_header.php';
include 'admin_inc/admin_nav.php';

// Initialize success and error message variables
$success_message = '';
$error_message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $admin_name = $_POST['admin_name'];
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password'];

    // Check if the email already exists in the database
    $email_check_query = "SELECT * FROM admins WHERE admin_email = '$admin_email'";
    $result = mysqli_query($conn, $email_check_query);

    if (mysqli_num_rows($result) > 0) {
        // If email already exists, display error message
        $error_message = "The email address is already in use. Please choose a different email.";
        $_SESSION['error_message'] = $error_message;
    } else {
        // Hash the password for security
        $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

        // Database query to insert new admin into the database
        $query = "INSERT INTO admins (admin_name, admin_email, admin_password) 
                  VALUES ('$admin_name', '$admin_email', '$hashed_password')";

        // Execute the query
        $insert_result = mysqli_query($conn, $query);

        // Check if the query was successful
        if ($insert_result) {
            $success_message = "Admin added successfully!";
            $_SESSION['success_message'] = $success_message;
        } else {
            $error_message = "Error adding admin: " . mysqli_error($conn);
            $_SESSION['error_message'] = $error_message;
        }
    }
}
?>

<head>
    <style>
        /* Styling the form */
form {
    width: 100%;
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    border-radius: 8px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Styling for the form labels */
form label {
    display: block;
    font-size: 16px;
    margin-bottom: 8px;
    font-weight: bold;
}

/* Styling the input fields */
form input {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
}

/* Add a focus effect to input fields */
form input:focus {
    border-color: #4CAF50;
    outline: none;
}

/* Styling for the submit button */
form button {
    padding: 10px 15px;
    background-color: #4CAF50;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

/* Button hover effect */
form button:hover {
    background-color: #45a049;
}

/* Styling the success/error messages */
form p {
    text-align: center;
    font-size: 16px;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
}

form p[style="color: green;"] {
    color: green;
}

form p[style="color: red;"] {
    color: red;
}

    </style>
</head>

<main class="dashboard-main">
    <h2>Add Admin</h2>
    
    <!-- Success message display -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green;"><?php echo htmlspecialchars($_SESSION['success_message']); ?></p>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <!-- Error message display -->
    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red;"><?php echo htmlspecialchars($_SESSION['error_message']); ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <form action="add_admin.php" method="post">
        <label for="admin-name">Admin Name</label>
        <input type="text" id="admin-name" name="admin_name" required>

        <label for="admin-email">Admin Email</label>
        <input type="email" id="admin-email" name="admin_email" required>

        <label for="admin-password">Admin Password</label>
        <input type="password" id="admin-password" name="admin_password" required>

        <button type="submit" class="btn">Add Admin</button>
    </form>
</main>

</body>
</html>
