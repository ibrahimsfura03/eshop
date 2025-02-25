<?php include 'includes/header.php'; ?>

<main>
    <!-- Welcome Section -->
    <section class="cta">
        <h2>Welcome to ESHOP!</h2>
        <p>Discover amazing products tailored just for you. Shop now and enjoy exclusive offers!</p>
        <a href="search.php" class="btn">Start Shopping</a>
    </section>

    <!-- Products Section -->
    <section class="products">
        <h2>Available Products</h2>
        <div class="product-list">
            <!-- Dynamic Product Listing Using PHP -->
            <?php

            // Fetch all products from the database
            $query = "SELECT * FROM products";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                // Loop through all products
                while ($product = $result->fetch_assoc()) {
                    ?>
                    <div class="product-card">
                        <img src="assets/img/<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>" class="product-image">
                        <h3><?php echo $product['product_name']; ?></h3>
                        <p><?php echo $product['product_description']; ?></p>
                        <p><strong>Price:</strong> $<?php echo number_format($product['product_price'], 2); ?></p>
                        <a href="product.php?id=<?php echo $product['product_id']; ?>" class="btn">Buy Now</a>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No products available at the moment. Please check back later!</p>";
            }
            ?>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-us">
        <div class="contact-left">
            <img src="images/pexels-sam-lion-5709235.jpg" alt="Our Shop">
        </div>
        <div class="contact-right">
            <h2>Contact Us</h2>
            <p>We'd love to hear from you! Fill out the form below to get in touch with us.</p>
            <form action="contact.php" method="post">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>

                <label for="email">Your Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <label for="message">Your Message</label>
                <textarea id="message" name="message" rows="5" placeholder="Write your message here..." required></textarea>

                <button type="submit" class="btn">Send Message</button>
            </form>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
