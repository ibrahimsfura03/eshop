<?php 
include 'admin_inc/admin_header.php'; 
include 'admin_inc/admin_nav.php'; 

// Handle product deletion
if (isset($_GET['delete'])) {
    $product_id = intval($_GET['delete']);
    $delete_query = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        header("Location: products.php");
        exit;
    } else {
        echo "<p>Error deleting product: " . $conn->error . "</p>";
    }
}

// Query to fetch all products from the database
$query = "SELECT product_id, product_name, product_description, product_price, product_image FROM products";
$result = mysqli_query($conn, $query);

// Check if the query is successful
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<main class="dashboard-main">
    <h2>Products</h2>
    <a href="add_product.php" class="btn">Add Product</a>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Action</th> <!-- New column for delete action -->
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($product = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>
                            <?php if (!empty($product['product_image'])): ?>
                                <img src="../assets/img/<?php echo htmlspecialchars($product['product_image']); ?>" alt="Product Image" width="50">
                            <?php else: ?>
                                <img src="../assets/img/product-placeholder.png" alt="Product Image" width="50">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['product_description']); ?></td>
                        <td>$<?php echo number_format($product['product_price'], 2); ?></td>
                        <td>
                            <a href="products.php?delete=<?php echo $product['product_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No products found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

</body>
</html>
