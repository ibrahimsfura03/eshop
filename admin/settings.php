<?php
include 'admin_inc/admin_header.php';
include 'admin_inc/admin_nav.php';


$admin_id = $_SESSION['admin_id'];
$error = '';  // Initialize error message variable
$success_message = '';  // Initialize success message variable

// Get the current admin details from the database
$query = "SELECT admin_id, admin_name, admin_email, admin_image FROM admins WHERE admin_id = '$admin_id'";
$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $admin = mysqli_fetch_assoc($result);
} else {
    die("Admin not found.");
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and sanitize input
    $admin_name = trim($_POST['admin_name']);
    $admin_email = trim($_POST['admin_email']);
    $admin_password = trim($_POST['admin_password']);
    $admin_image = $_FILES['admin_image']['name'];
    $image_tmp = $_FILES['admin_image']['tmp_name'];

    // Start a query to update admin details
    $update_query = "UPDATE admins SET admin_name = '$admin_name', admin_email = '$admin_email'";

    // If a new password is provided, hash it and add it to the update query
    if (!empty($admin_password)) {
        $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
        $update_query .= ", admin_password = '$hashed_password'";
    }

    // If a new image is uploaded, move the file and update the image path
    if (!empty($admin_image)) {
        $image_path = "../assets/img/" . basename($admin_image);
        move_uploaded_file($image_tmp, $image_path);
        $update_query .= ", admin_image = '$image_path'";
    }

    // Finalize the update query
    $update_query .= " WHERE admin_id = '$admin_id'";

    // Execute the update query
    if (mysqli_query($conn, $update_query)) {
        $success_message = "Admin details updated successfully!";
    } else {
        $error = "Error updating details: " . mysqli_error($conn);
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

/* Styling the submit button */
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

/* Styling the file input */
form input[type="file"] {
    padding: 8px;
    font-size: 14px;
    border: 1px solid #ccc;
    background-color: #f5f5f5;
    border-radius: 5px;
}

/* Additional spacing for the file input label */
form label[for="admin_image"] {
    margin-bottom: 5px;
}

/* Styling the error/success messages */
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

<!-- Admin Update Form Section -->

<head>
    <link rel="stylesheet" href="admin_inc/style.css">
</head>

<!-- Admin Update Form Section -->
<main>
    <section class="update-form">
        <h2>Update Admin Details</h2>

        <!-- Error and Success Messages -->
        <?php if (!empty($error)): ?>
            <h3 class="error-message" style="color: red;"><?php echo htmlspecialchars($error); ?></h3>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <h3 class="success-message" style="color: green;"><?php echo htmlspecialchars($success_message); ?></h3>
        <?php endif; ?>

        <!-- Form for Admin Details Update -->
        <form action="" method="post" enctype="multipart/form-data">
            <label for="admin_name">Admin Name:</label>
            <input type="text" id="admin_name" name="admin_name" value="<?php echo htmlspecialchars($admin['admin_name']); ?>" required>

            <label for="admin_email">Email:</label>
            <input type="email" id="admin_email" name="admin_email" value="<?php echo htmlspecialchars($admin['admin_email']); ?>" required>

            <label for="admin_password">Password:</label>
            <input type="password" id="admin_password" name="admin_password" placeholder="Enter new password (leave empty to keep current)">

            <label for="admin_image">Profile Image:</label>
            <input type="file" id="admin_image" name="admin_image" accept="">

            <button type="submit">Update Details</button>
        </form>
    </section>
</main>


</body>
</html>
