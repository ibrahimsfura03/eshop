<?php 
// Include the admin header and navigation
include 'admin_inc/admin_header.php'; 
include 'admin_inc/admin_nav.php'; 


// Initialize an empty message variable
$message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_description = mysqli_real_escape_string($conn, $_POST['product_description']);
    $product_price = (float) $_POST['product_price'];

    // Handle file upload for the product image
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $image_name = $_FILES['product_image']['name'];
        $image_tmp_name = $_FILES['product_image']['tmp_name'];
        $upload_dir = '../assets/img/'; // Path to save uploaded images
        $image_path = $upload_dir . basename($image_name);

        // Validate and move the uploaded file
        if (move_uploaded_file($image_tmp_name, $image_path)) {
            // Insert into the database if all fields are valid
            if (!empty($product_name) && !empty($product_description) && $product_price > 0) {
                $sql = "INSERT INTO products (product_name, product_description, product_price, product_image) 
                        VALUES ('$product_name', '$product_description', $product_price, '$image_name')";

                if (mysqli_query($conn, $sql)) {
                    $message = "Product added successfully!";
                } else {
                    $message = "Database Error: " . mysqli_error($conn);
                }
            } else {
                $message = "Please fill in all fields correctly.";
            }
        } else {
            $message = "Failed to upload the image.";
        }
    } else {
        $message = "Please upload a valid image.";
    }
}
?>

<head>
    <style>
        /* Styling the form */
form {
    width: 100%;
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    border-radius: 8px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Styling for the form labels */
form label {
    display: block;
    font-size: 16px;
    margin-bottom: 8px;
    font-weight: bold;
}

/* Styling the input fields */
form input, form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
}

/* Add a focus effect to input fields */
form input:focus, form textarea:focus {
    border-color: #4CAF50;
    outline: none;
}

/* Styling for the submit button */
form button {
    padding: 10px 15px;
    background-color: #4CAF50;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

/* Button hover effect */
form button:hover {
    background-color: #45a049;
}

/* Styling the success/error messages */
form p {
    text-align: center;
    font-size: 16px;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
}

form p[color="green"] {
    color: green;
}

form p[color="red"] {
    color: red;
}

/* Styling for the textarea */
form textarea {
    resize: vertical;
    min-height: 150px;
}

    </style>
</head>

<main class="dashboard-main">
    <h2>Add Product</h2>

    <!-- Display Success or Error Message -->
    <?php if (!empty($message)): ?>
        <p style="color: <?= strpos($message, 'successfully') !== false ? 'green' : 'red'; ?>;">
            <?= $message; ?>
        </p>
    <?php endif; ?>

    <!-- Add Product Form -->
    <form action="add_product.php" method="post" enctype="multipart/form-data">
        <label for="product-name">Product Name</label>
        <input type="text" id="product-name" name="product_name" required>
        
        <label for="product-image">Product Image</label>
        <input type="file" id="product-image" name="product_image" accept="image/*" required>

        <label for="product-description">Description</label>
        <textarea id="product-description" name="product_description" required></textarea>

        <label for="product-price">Price</label>
        <input type="number" id="product-price" name="product_price" step="0.01" required>

        <button type="submit" class="btn">Add Product</button>
    </form>
</main>

<!-- Close the database connection -->
<?php 
mysqli_close($conn); 
?>
</body>
</html>
