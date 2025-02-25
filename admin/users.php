<?php 
include 'admin_inc/admin_header.php'; 
include 'admin_inc/admin_nav.php'; 

// Query to fetch all users
$query = "SELECT user_id, user_name, user_email FROM users";
$result = mysqli_query($conn, $query);

// Check if any users are found
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<main class="dashboard-main">
    <h2>Users</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($user = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($user['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['user_email']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

</body>
</html>
