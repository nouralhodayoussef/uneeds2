<?php
// Include database connection
require_once 'config.php'; // Assuming the connection is in this file and uses $con

// Fetch all products and their categories
$query = "SELECT p.id, p.name, p.description, p.price, p.stock, c.name as category_name, i.image_url 
          FROM products p
          LEFT JOIN categories c ON p.category_id = c.id
          LEFT JOIN imgs i ON p.id = i.product_id
          GROUP BY p.id";
$result = mysqli_query($con, $query);
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
    <!-- Header -->
    <div id="header"></div>
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
                .catch(error => console.error('Error loading header: ', error));
        });
    </script>

    <div id="mainContainer">
        <h1>All The Products U-need!</h1>

        <!-- Container for Products -->
        <div id="containerProduct">
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div id="box">
                        <a href="productDetails.php?id=<?php echo $row['id']; ?>">
                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <div id="details">
                                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                                <h4><?php echo htmlspecialchars($row['category_name']); ?></h4>
                                <span>$ <?php echo number_format($row['price'], 2); ?></span>
                            </div>
                        </a>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No products available.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Footer -->
    <div id="footer"></div>
    <script>
        fetch('footer.php')
            .then(response => response.text())
            .then(data => document.getElementById('footer').innerHTML = data)
            .catch(error => console.error('Error loading footer: ', error));
    </script>
</body>
</html>
