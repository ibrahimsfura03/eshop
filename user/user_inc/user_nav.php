<?php
// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Query to get the user details (name and image)
$query = "SELECT user_name, user_image FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);

// Check if the user exists
if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $user_name = $user['user_name'];

    // Make sure the user image path is correct
    $user_image = $user['user_image'] ? '../assets/img/' . $user['user_image'] : '../assets/img/admin-placeholder.png';

} else {
    // In case of an error or invalid user ID
    $user_name = 'Guest';
    $user_image = '..assets/img/'; // Default image for guests
}
?>
<div class="sidebar">
    <div class="profile">
        <!-- Display user's profile image -->
        <img src="<?php echo htmlspecialchars($user_image); ?>" alt="Profile Picture">
        <!-- Display user's name -->
        <h3><?php echo htmlspecialchars($user_name); ?></h3>
    </div>
    <ul>
        <li><a href="userdb.php">Dashboard</a></li>
        <li><a href="carts.php">Carts</a></li>
        <li><a href="orders.php">Orders</a></li>
        <li><a href="settings.php">Settings</a></li>
    </ul>
</div>
