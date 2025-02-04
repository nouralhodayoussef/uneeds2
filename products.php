<?php
require_once 'config.php';

$query = "SELECT p.id, p.name, p.description, p.price, p.stock, c.name as category_name, 
          (SELECT image_url FROM imgs WHERE product_id = p.id LIMIT 1) AS image_url
          FROM products p
          LEFT JOIN categories c ON p.category_id = c.id";
$result = mysqli_query($con, $query);

$products_by_category = [];
while ($product = mysqli_fetch_assoc($result)) {
    $products_by_category[$product['category_name']][] = $product;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uneeds</title>
    <link rel="icon" href="imgs/logo.png" type="image/png" sizes="16x16">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/products.css">
</head>

<body>
    <div id="header">Loading...</div>
    <script src="script.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            fetch('header.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('header').innerHTML = data;
                    const menuToggle = document.querySelector('.menu-icon');
                    const navbar = document.querySelector('.navbar');
                    if (menuToggle && navbar) {
                        menuToggle.addEventListener('click', () => {
                            navbar.classList.toggle('active');
                        });
                    }
                })
                .catch(error => console.error('Error loading header:', error));
        });
    </script>

    <div id="mainContainer">
        <h1>All The Products U-need!</h1>

        <?php foreach ($products_by_category as $category_name => $products): ?>
    <div class="section1">
        <h3 style="text-align: center; color: black; text-decoration: none;"><?php echo $category_name; ?></h3>
        <div id="containerProduct">
            <?php foreach ($products as $product): ?>
            <a href="product_detail.php?id=<?php echo $product['id']; ?>" style="text-decoration: none;">
                <div style="height: 120vh;" id="box">
                    <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                    <div id="details">
                        <h3 class="h3" style="color: black; text-decoration: none;"><?php echo $product['name']; ?></h3>
                        <h4 class="h4" style="color: black; text-decoration: none;"><?php echo $category_name; ?></h4>
                        <span>$<?php echo number_format($product['price'], 2); ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>

    </div>

    <div id="footer">Loading...</div>
    <script>
        fetch('footer.php')
            .then(response => response.text())
            .then(data => document.getElementById('footer').innerHTML = data)
            .catch(error => console.error('Error loading footer:', error));
    </script>
</body>

</html>
