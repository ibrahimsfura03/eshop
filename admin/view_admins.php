<?php 
include 'admin_inc/admin_header.php'; 
include 'admin_inc/admin_nav.php';

// Query to fetch all admins
$query = "SELECT * FROM admins";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    die("Error fetching admins: " . mysqli_error($conn));
}

// Delete admin if delete button is pressed
if (isset($_GET['delete_id'])) {
    $admin_id_to_delete = $_GET['delete_id'];
    
    // Query to delete admin
    $delete_query = "DELETE FROM admins WHERE admin_id = '$admin_id_to_delete'";
    if (mysqli_query($conn, $delete_query)) {
        echo "<p style='color: green;'>Admin deleted successfully.</p>";
        header("Location: view_admins.php");
    } else {
        echo "<p style='color: red;'>Error deleting admin: " . mysqli_error($conn) . "</p>";
    }
}
?>

<main class="dashboard-main">
    <h2>Admins</h2>
    <a href="add_admin.php" class="btn">Add Admin</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th> <!-- New Column for Delete -->
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($admin = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($admin['admin_id']); ?></td>
                        <td><?php echo htmlspecialchars($admin['admin_name']); ?></td>
                        <td><?php echo htmlspecialchars($admin['admin_email']); ?></td>
                        <td>
                            <a href="view_admins.php?delete_id=<?php echo $admin['admin_id']; ?>" 
                               onclick="return confirm('Are you sure you want to delete this admin?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No admins found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

</body>
</html>
