<?php
include 'user_inc/user_header.php';

$user_id = $_SESSION['user_id']; // Get logged-in user's ID

// Fetch cart items and calculate total price
$total_price = 0;

$query = "SELECT c.quantity, p.product_price 
          FROM cart_items c 
          INNER JOIN products p ON c.product_id = p.product_id 
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $total_price += $row['product_price'] * $row['quantity'];
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = htmlspecialchars(trim($_POST['address']));
    $payment_method = $_POST['payment_method'];

    if (!empty($address) && !empty($payment_method)) {
        // Save the order to the database
        $order_query = "INSERT INTO orders (user_id, total_price, payment_method, order_address, order_date) VALUES (?, ?, ?, ?, NOW())";
        $order_stmt = $conn->prepare($order_query);
        $order_stmt->bind_param("idss", $user_id, $total_price, $payment_method, $address);

        if ($order_stmt->execute()) {
            // Clear the user's cart
            $clear_cart_query = "DELETE FROM cart_items WHERE user_id = ?";
            $clear_cart_stmt = $conn->prepare($clear_cart_query);
            $clear_cart_stmt->bind_param("i", $user_id);
            $clear_cart_stmt->execute();

            // Redirect to order tracking page with success message
            $_SESSION['message'] = "Order placed successfully! <a style='color: blue' href='orders.php'>View Order</a>";
            header('Location: checkout.php');
            exit;
        } else {
            $error_message = "Failed to place the order. Please try again.";
        }
    } else {
        $error_message = "All fields are required.";
    }
}
?>


<!-- Main Content -->
<main class="checkout-container">
    <h2>Checkout</h2>

    <!-- Display error or success messages -->
    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['message'])): ?>
        <h3 style="color: greenyellow;" class="success-message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></h3>
    <?php endif; ?>

    <form action="checkout.php" method="post" class="checkout-form">
        <!-- Address Section -->
        <div class="form-group">
            <label for="address">Shipping Address</label>
            <textarea id="address" name="address" rows="3" placeholder="Enter your shipping address" required></textarea>
        </div>

        <!-- Payment Method -->
        <div class="form-group">
            <label for="payment-method">Payment Method</label>
            <select id="payment-method" name="payment_method" required>
                <option value="" disabled selected>Select a payment method</option>
                <option value="credit_card">Credit Card</option>
                <option value="paypal">PayPal</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="cash_on_delivery">Cash on Delivery</option>
            </select>
        </div>

        <!-- Total Price -->
        <div class="form-summary">
            <p><strong>Total Price:</strong> $<?php echo number_format($total_price, 2); ?></p>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="checkout-btn">Place Order</button>
    </form>
</main>
