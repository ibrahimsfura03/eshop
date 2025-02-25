<?php include 'includes/header.php'; ?>

<?php
$search_results = [];
if (isset($_GET['query'])) {
    $search_query = $conn->real_escape_string($_GET['query']); // Sanitize user input

    // Search for words specifically in the product_description column
    $query = "
        SELECT * 
        FROM products 
        WHERE product_description LIKE '%$search_query%'
    ";

    $result = $conn->query($query);

    // Fetch results if the query executes successfully
    if ($result) {
        $search_results = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<main>
    <section class="search-results">
        <?php if (!empty($search_results)) : ?>
            <h2>Search Results</h2>
            <div class="product-list">
                <?php foreach ($search_results as $product) : ?>
                    <div class="product-card">
                        <img src="assets/img/<?php echo $product['product_image']; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                        <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['product_description']); ?></p>
                        <p><strong>Price:</strong> $<?php echo number_format($product['product_price'], 2); ?></p>
                        <a href="product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary">View Product</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif (isset($_GET['query'])) : ?>
            <p>No results found for "<?php echo htmlspecialchars($_GET['query']); ?>".</p>
        <?php endif; ?>
    </section>
</main>
<?php include 'includes/footer.php'; ?>
