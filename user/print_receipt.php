<?php  include '../includes/db.php'; ?>
<?php  session_start(); ?>

<?php
    // Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page
    header('Location: ../auth/login.php');
    exit;
}
?>


<?php

// Include database connection
// Assuming $conn is your MySQL connection object

// Get the order ID from the query string
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if ($order_id > 0) {
    // Fetch the order details
    $query = "SELECT order_id, order_date, total_price FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
    } else {
        echo "<p>Order not found.</p>";
        exit;
    }
} else {
    echo "<p>Invalid order ID.</p>";
    exit;
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .main-content {
        text-align: center;
        margin-top: 20px;
    }

    .receipt-card {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        margin: auto;
    }

    .receipt-card h2 {
        margin-bottom: 20px;
        color: #333;
    }

    .receipt-card p {
        font-size: 16px;
        margin: 5px 0;
        color: #555;
    }

    .receipt-actions {
        margin-top: 20px;
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .print-btn, .back-btn {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .print-btn:hover, .back-btn:hover {
        background-color: #0056b3;
    }

    .back-btn {
        text-align: center;
        display: inline-block;
    }
</style>

<main class="main-content">
    <div class="receipt-card" id="receipt">
        <h2>Order Receipt</h2>
        <p><strong>Order ID:</strong> #<?php echo $order['order_id']; ?></p>
        <p><strong>Date:</strong> <?php echo date("Y-m-d", strtotime($order['order_date'])); ?></p>
        <p><strong>Status:</strong> Success</p>
        <p><strong>Total:</strong> $<?php echo number_format($order['total_price'], 2); ?></p>

        <hr>

        <p>Thank you for your purchase!</p>
        <p>If you have any questions, please contact our support team.</p>
    </div>

    <div class="receipt-actions">
        <button id="print-btn" class="print-btn">Print Receipt</button>
        <a href="orders.php" class="back-btn">Back to Orders</a>
    </div>
</main>



<script>
    // JavaScript to handle the "Print Receipt" functionality
    document.getElementById('print-btn').addEventListener('click', function () {
        const receiptContent = document.getElementById('receipt').innerHTML;
        const originalContent = document.body.innerHTML;

        // Replace the body content with the receipt content for printing
        document.body.innerHTML = receiptContent;
        window.print();

        // Restore the original content after printing
        document.body.innerHTML = originalContent;
        window.location.reload(); // Reload the page to restore functionality
    });
</script>

</body>
</html>
