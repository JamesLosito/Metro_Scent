<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "metro_essence");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = $conn->query($sql);

// Check if product exists
if ($result->num_rows == 1) {
    $product = $result->fetch_assoc();
} else {
    echo "Product not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Product</title>
    <style>
        .product-container {
            width: 60%;
            margin: 50px auto;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            font-family: Arial, sans-serif;
        }
        .product-container img {
            width: 100%;
            max-width: 300px;
            height: auto;
        }
        .product-details {
            margin-top: 20px;
        }
        .product-details p {
            margin: 5px 0;
        }
        .add-to-cart {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="product-container">
    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
    <img src="<?php echo $product['image']; ?>" alt="Product Image">
    
    <div class="product-details">
        <p><strong>Price:</strong> ₱<?php echo number_format($product['price'], 2); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
        <p><strong>Stock:</strong> <?php echo $product['stock']; ?></p>
    </div>

    <div class="add-to-cart">
        <form method="post" action="add_to_cart.php">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" min="1" max="<?php echo $product['stock']; ?>" value="1" required>
            <button type="submit">Add to Cart</button>
        </form>
    </div>
</div>

</body>
</html>
