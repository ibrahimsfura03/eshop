<?php
include 'user_inc/user_header.php';
include 'user_inc/user_nav.php';

$user_id = $_SESSION['user_id']; // Get logged-in user's ID


// Fetch cart items for the logged-in user
$cart_items = [];
$total_price = 0;

$query = "SELECT c.quantity, p.product_id, p.product_name, p.product_description, p.product_price, p.product_image 
          FROM cart_items c 
          INNER JOIN products p ON c.product_id = p.product_id 
          WHERE c.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_price += $row['product_price'] * $row['quantity'];
    }
}
?>

<main class="cart-container">
    <h2>Your Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <p>Your cart is empty. <a href="index.php">Start shopping</a>.</p>
    <?php else: ?>
        <!-- Display cart items -->
        <?php foreach ($cart_items as $item): ?>
            <div class="cart-item">
                <div class="cart-image">
                    <img src="../assets/img/<?php echo $item['product_image']; ?>" alt="<?php echo $item['product_name']; ?>">
                </div>
                <div class="cart-details">
                    <h3><?php echo $item['product_name']; ?></h3>
                    <p><?php echo $item['product_description']; ?></p>
                    <p>Quantity: <?php echo $item['quantity']; ?></p>
                    <p>Price: $<?php echo number_format($item['product_price'], 2); ?></p>
                    <p><strong>Subtotal:</strong> $<?php echo number_format($item['product_price'] * $item['quantity'], 2); ?></p>
                </div>
                <div class="cart-actions">
                <a href="carts.php?delete=<?php echo isset($item['product_id']) ? $item['product_id'] : ''; ?>" class='delete-btn'>Delete</a>

                </div>
            </div>
        <?php endforeach; ?>

        <!-- Total Price -->
        <div class="cart-total">
            <h3>Total: $<?php echo number_format($total_price, 2); ?></h3>
        </div>

        <!-- Action Buttons -->
        <div class="cart-buttons">
            <a href="checkout.php" class="checkout-btn">Continue to Checkout</a>
            <a href="../index.php" class="shop-btn">Back to Shop</a>
        </div>
    <?php endif; ?>
</main>

<?php
    // Check if the delete action is triggered
if (isset($_GET['delete'])) {
    $product_id = (int)$_GET['delete']; // Sanitize product ID

    // Ensure product ID is valid
    if ($product_id > 0) {
        // Prepare delete query
        $delete_query = "DELETE FROM cart_items WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("ii", $user_id, $product_id);

        // Execute deletion
        if ($stmt->execute()) {
            $_SESSION['message'] = "Item removed from your cart.";
        } else {
            $_SESSION['message'] = "Failed to remove item from your cart.";
        }

        
        header('Location: carts.php');
        exit;
    } else {
        $_SESSION['message'] = "Invalid product ID.";
    }
}
?>

</body>
</html>
