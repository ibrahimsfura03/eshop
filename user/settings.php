<?php
include 'user_inc/user_header.php';
include 'user_inc/user_nav.php';

// Fetch current user's details
$user_id = $_SESSION['user_id'];
$query = "SELECT user_name, user_email, user_image, LENGTH(user_password) AS password_length FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    $user_image = $_FILES['user_image'];

    // Handle image upload
    $image_name = $user['user_image']; // Retain existing image by default
    if (!empty($user_image['name'])) {
        $target_dir = "../assets/img/";
        $image_name = time() . "_" . basename($user_image['name']);
        $target_file = $target_dir . $image_name;


        if (!move_uploaded_file($user_image['tmp_name'], $target_file)) {
            die("Error uploading image.");
        }
    }

    // Update user details in the database
    $query = "UPDATE users SET user_name = ?, user_email = ?, user_image = ?";
    $params = [$user_name, $user_email, $image_name];

    if (!empty($user_password)) {
        $query .= ", user_password = ?";
        $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);
        $params[] = $hashed_password;
    }

    $query .= " WHERE user_id = ?";
    $params[] = $user_id;

    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat("s", count($params) - 1) . "i", ...$params);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Profile updated successfully.";
        header('Location: settings.php');
        exit;
    } else {
        $_SESSION['message'] = "Failed to update profile.";
    }
}
?>

<main class="main-content">
    <h2>Account Settings</h2>

    <!-- Check and display success or error message -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <?php unset($_SESSION['message']); ?> <!-- Unset the message after display -->
        </div>
    <?php endif; ?>

    <form action="settings.php" method="post" enctype="multipart/form-data">
        <!-- Profile Image Update -->
        <div class="profile-image-section">
            <label for="profile-image">Profile Image:</label>
            <div class="profile-image-preview">
                <?php if (!empty($user['user_image'])): ?>
                    <img src="../assets/img/<?php echo htmlspecialchars($user['user_image']); ?>" alt="Profile Picture">
                <?php else: ?>
                    <img src="../assets/img/" alt="Profile Picture">
                <?php endif; ?>
            </div>
            <input type="file" id="profile-image" name="user_image">
        </div>

        <!-- User Info Update -->
        <label for="name">Name:</label>
        <input type="text" id="name" name="user_name" placeholder="Enter your name" 
            value="<?php echo htmlspecialchars($user['user_name']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="user_email" placeholder="Enter your email" 
            value="<?php echo htmlspecialchars($user['user_email']); ?>" required>

        <!-- Password Field -->
        <label for="password">Password:</label>
        <input type="password" id="password" name="user_password" placeholder="<?php echo str_repeat('•', $user['password_length']); ?>" minlength="6">

        <!-- Submit Button -->
        <button type="submit">Update</button>
    </form>
</main>

</body>
</html>
