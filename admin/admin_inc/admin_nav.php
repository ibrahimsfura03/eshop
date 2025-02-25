<?php
include '../includes/db.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$admin_id = $_SESSION['admin_id'];

// Query to get the admin details (name and image)
$query = "SELECT admin_name, admin_image FROM admins WHERE admin_id = '$admin_id'";
$result = mysqli_query($conn, $query);

// Check if the admin exists
if ($result && mysqli_num_rows($result) > 0) {
    $admin = mysqli_fetch_assoc($result);
    $admin_name = $admin['admin_name'];
    $admin_image = $admin['admin_image'] ? $admin['admin_image'] : 'assets/images/admin-placeholder.png'; // Fallback to placeholder if no image
} else {
    // In case of an error or invalid admin ID
    $admin_name = 'Admin';
    $admin_image = 'assets/images/admin-placeholder.png';
}
?>

<aside class="dashboard-sidenav">
    <div class="profile">
        <!-- Display the admin's profile image -->
        <img src="<?php echo htmlspecialchars($admin_image); ?>" alt="Admin Profile">
        <!-- Display the admin's name -->
        <p><?php echo htmlspecialchars($admin_name); ?></p>
    </div>
    <nav class="sidenav-links">
        <a href="admindb.php">Dashboard</a>
        <a href="orders.php">Orders</a>
        <a href="products.php">Products</a>
        <a href="users.php">Users</a>
        <a href="view_admins.php">Admins</a>
        <a href="settings.php">Settings</a>
    </nav>
</aside>
