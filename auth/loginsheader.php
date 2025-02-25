<?php include '../includes/db.php'; ?>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>ESHOP - Homepage</title>
</head>
<body>
<header>
    <div class="header-left">
        <h1><a href="../index.php">ESHOP</a></h1>
        <nav>
            <a href="#">Contact Us</a>
            <a href="#">About Us</a>
        </nav>
    </div>
    <div class="header-center">
        <form action="../search.php" method="get">
            <input type="search" name="query" placeholder="Search for products..." required>
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="header-right">
        <?php if (isset($_SESSION['user_name'])): ?>
        <!-- Show the logged-in user's name -->
        <a href="../user/userdb.php"><?php echo htmlspecialchars($_SESSION['user_name']); ?></a>
        <?php else: ?>
        <!-- Show Login and Signup buttons if no user is logged in -->
        <a href="login.php" class="btn" style="color: #eee;">Login</a>
        <a href="signup.php" class="btn" style="color: #eee;">Signup</a>
        <?php endif; ?>
    </div>
</header>