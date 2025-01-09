<?php
require_once 'config.php';
session_start();

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    echo "<h1>Invalid product ID.</h1>";
    exit;
}

$query = "SELECT p.name, p.description, p.price, p.stock, c.name as category_name, i.image_url 
          FROM products p
          LEFT JOIN categories c ON p.category_id = c.id
          LEFT JOIN imgs i ON p.id = i.product_id
          WHERE p.id = ?
          LIMIT 1";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<h1>Product not found.</h1>";
    exit;
}

$images_query = "SELECT image_url FROM imgs WHERE product_id = ?";
$stmt = $con->prepare($images_query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$images_result = $stmt->get_result();
$images = [];
while ($image_row = $images_result->fetch_assoc()) {
    $images[] = $image_row['image_url'];
}

if (empty($images)) {
    $images[] = 'imgs/default-product.png';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Uneeds - <?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="icon" href="imgs/logo.png" type="image/png" sizes="16x16">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/product_detail.css" />
</head>

<body>
    <div id="header"></div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            fetch('header.php')
                .then(response => response.text())
                .then(data => document.getElementById('header').innerHTML = data)
                .catch(error => console.error('Error loading header:', error));
        });
    </script>

    <div id="containerD">
        <div id="imageSection">
            <img id="imgDetails" src="<?php echo htmlspecialchars($images[0]); ?>"
                alt="<?php echo htmlspecialchars($product['name']); ?>" />
        </div>
        <div id="productDetails">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <h4><?php echo htmlspecialchars($product['category_name']); ?></h4>
            <div id="details">
                <h3>Price: $<?php echo number_format($product['price'], 2); ?></h3>
                <h3>Description</h3>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
            </div>
            <div id="productPreview">
                <h3>Product Preview</h3>
                <?php foreach ($images as $image_url): ?>
                    <img src="<?php echo htmlspecialchars($image_url); ?>" alt="Preview"
                        onclick="changeImage('<?php echo htmlspecialchars($image_url); ?>')" />
                <?php endforeach; ?>
            </div>
            <div id="button">
                <button onclick="addToCart(<?php echo htmlspecialchars($product_id); ?>)">Add to Cart</button>
            </div>
            <script>
                function changeImage(src) {
                    document.getElementById("imgDetails").src = src;
                }
                function addToCart(productId) {
                    fetch('shopping_cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ product_id: productId, quantity: 1 })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Product added to cart!');
                            } else {
                                alert('Failed to add product to cart: ' + data.error);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while adding the product to the cart.');
                        });
                }
            </script>
        </div>
    </div>

    <div id="footer"></div>
    <script>
        fetch('footer.php')
            .then(response => response.text())
            .then(data => document.getElementById('footer').innerHTML = data)
            .catch(error => console.error('Error loading footer:', error));
    </script>
</body>

</html>