<?php
include 'includes/header.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page
    header('Location: auth/login.php');
    exit;
}

// Initialize a message in case no product is found
$product_message = '';

// Check if `id` is passed in the URL
if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id']; // Sanitize product_id

    // Fetch the product from the database
    $query = "SELECT * FROM products WHERE product_id = $product_id LIMIT 1";
    $result = $conn->query($query);

    // Check if a product is found
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc(); // Fetch the product details

        // Handle Add to Cart action
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
            $user_id = $_SESSION['user_id']; // Get the logged-in user's ID
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Get quantity from the form

            // Check if the product is already in the cart for this user
            $check_query = "SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param("ii", $user_id, $product_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows === 0) {
                // Product is not in the cart, insert it with the quantity
                $insert_query = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);

                if ($insert_stmt->execute()) {
                    $product_message = "Product added to your cart! <a href='user/carts.php'>View Carts</a>";
                } else {
                    $product_message = "Failed to add product to your cart.";
                }
            } else {
                // Product already in the cart, no quantity update
                $product_message = "Product is already in your cart. <a href='user/carts.php'>View Carts</a>";
            }
        }
    } else {
        $product_message = "Product not found!";
    }
} else {
    $product_message = "No product selected!";
}
?>                    

<main>
    <section class="product-detail">
        <h2>Product Details</h2>

        <?php if (!empty($product_message)): ?>
            <p><?php echo $product_message; ?></p>
        <?php endif; ?>

        <?php if (isset($product)): ?>
            <div class="product-info">
                <img src="assets/img/<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>" class="product-image">
                <h3><?php echo $product['product_name']; ?></h3>
                <p><?php echo $product['product_description']; ?></p>
                <p><strong>Price:</strong> $<?php echo number_format($product['product_price'], 2); ?></p>

                <!-- Add to Cart Form -->
                <form action="?id=<?php echo $product['product_id']; ?>" method="post">
                    <div class="quantity-input">
                        <label for="quantity"><strong>Quantity:</strong></label>
                        <input type="number" id="quantity" name="quantity" min="1" value="1">
                    </div>
                    <button type="submit" name="add_to_cart" class="btn-primary">Add to Cart</button>
                </form>

                <!-- Other Actions -->
                <div class="actions">
                    <a href="index.php" class="btn-secondary">Continue Shopping</a>
                </div>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
