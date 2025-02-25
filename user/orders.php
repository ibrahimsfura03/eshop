<?php 
include 'user_inc/user_header.php'; 
include 'user_inc/user_nav.php';

$user_id = $_SESSION['user_id'];

// Include database connection
// Assuming $conn is your MySQL connection object

// Fetch orders based on the current user ID
$orders = [];
$order_query = "SELECT order_id, order_date, total_price FROM orders WHERE user_id = $user_id ORDER BY order_date DESC";
$result = $conn->query($order_query);

if ($result && $result->num_rows > 0) {
    while ($order = $result->fetch_assoc()) {
        $orders[] = $order;
    }
}
?>

<main class="main-content">
    <h2>Your Orders</h2>

    <?php if (empty($orders)): ?>
        <p>No orders found. <a href="index.php">Start shopping</a>.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['order_id']; ?></td>
                        <td><?php echo date("Y-m-d", strtotime($order['order_date'])); ?></td>
                        <td>Success</td> <!-- Static status -->
                        <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                        <td><a href="print_receipt.php?order_id=<?php echo $order['order_id']; ?>" target="_blank">Print Receipt</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

</body>
</html>
