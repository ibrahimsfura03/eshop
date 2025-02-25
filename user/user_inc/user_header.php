<?php include '../includes/db.php'; ?>
<?php session_start(); ?>

<?php
    // Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page
    header('Location: ../auth/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="user_inc/style.css">
</head>
<body>
<header class="header">
    <div class="header-left">
        <h1><a href="index.php">ESHOP</a></h1>
    </div>
    <div class="header-right">
        <a href="../index.php" class="home-btn">Home</a>
        <a href="../auth/logout.php" class="logout-btn">Logout</a>
    </div>
</header>

