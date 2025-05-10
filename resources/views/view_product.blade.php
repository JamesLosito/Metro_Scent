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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
@include('components.navbar');
<body class="bg-light">

<div class="container my-5">
    <div class="card shadow p-4">
        <div class="row">
            <div class="col-md-5 text-center">
                <img src="<?php echo $product['image']; ?>" alt="Product Image" class="img-fluid rounded">
            </div>
            <div class="col-md-7">
                <h2 class="mb-3"><?php echo htmlspecialchars($product['name']); ?></h2>
                <p><strong>Price:</strong> ₱<?php echo number_format($product['price'], 2); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
                <p><strong>Stock:</strong> <?php echo $product['stock']; ?></p>

                <form method="post" action="add_to_cart.php" class="mt-4">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" class="form-control w-25" min="1" max="<?php echo $product['stock']; ?>" value="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
