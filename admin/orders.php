<?php 
include 'admin_inc/admin_header.php'; 
include 'admin_inc/admin_nav.php'; 

// Query to fetch all orders
$query = "SELECT * FROM orders";
$result = mysqli_query($conn, $query);

// Check if the query is successful
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<main class="dashboard-main">
    <h2>Orders</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Customer Email</th> <!-- Updated column header -->
                <th>Total Price</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($order = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>#<?php echo htmlspecialchars($order['order_id']); ?></td>

                        <?php
                        // Check if the 'user_id' exists in the result set
                        if (isset($order['user_id'])) {
                            $user_id = $order['user_id'];
                            $user_query = "SELECT user_name, user_email FROM users WHERE user_id = '$user_id' LIMIT 1";
                            $user_result = mysqli_query($conn, $user_query);
                            if ($user_result) {
                                $user = mysqli_fetch_assoc($user_result);
                                echo "<td>" . htmlspecialchars($user['user_name']) . "</td>"; // Display customer name
                                echo "<td>" . htmlspecialchars($user['user_email']) . "</td>"; // Display customer email
                            } else {
                                echo "<td>Unknown Customer</td><td>Unknown Email</td>";
                            }
                        } else {
                            echo "<td>Unknown Customer</td><td>Unknown Email</td>";
                        }
                        ?>

                        <td>$<?php echo number_format($order['total_price'], 2); ?></td>

                        <?php
                        // Check if the 'order_address' exists in the result set
                        if (isset($order['order_address'])) {
                            echo "<td>" . htmlspecialchars($order['order_address']) . "</td>";
                        } else {
                            echo "<td>Address Unknown</td>";
                        }
                        ?>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

</body>
</html>
