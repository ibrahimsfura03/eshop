<?php session_start();; ?>
<?php include '../includes/db.php'; ?>

<?php
    // Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to the login page
    header('Location: logout.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_inc/style.css">
    <title>Admin Dashboard - ESHOP</title>
</head>
<body>
    <!-- Header -->
    <header class="dashboard-header">
        <div class="header-left">
            <h1><a href="admin-dashboard.php">ESHOP Admin</a></h1>
        </div>
        <div class="header-right">
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </header>
