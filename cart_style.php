<?php
include 'config.php';
session_start();

if (!$con) {
  die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Your Shopping Cart</title>
  <link rel="icon" href="imgs/logo.png" type="image/png" sizes="16x16">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/shopping_cart.css" />
</head>

<body>
  <div id="header"></div>

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
        .catch(error => {
          console.error('Error loading header: ', error);
        });
    });
  </script>

  <div id="cartMainContainer">
    <h1>Your Shopping Cart</h1>
    <h3 id="totalItem">
      Total Items:
      <?php
      $cart_count_sql = "SELECT COUNT(*) AS total_items FROM shopping_cart WHERE user_id = $user_id";
      $cart_count_result = mysqli_query($con, $cart_count_sql);
      $cart_count_row = mysqli_fetch_assoc($cart_count_result);
      echo $cart_count_row['total_items'];
      ?>
    </h3>

    <div id="cartContainer">
      <?php
      $cart_sql = "SELECT product_id, quantity FROM shopping_cart WHERE user_id = $user_id";
      $cart_result = mysqli_query($con, $cart_sql);

      if (mysqli_num_rows($cart_result) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_result)) {
          $product_id = $cart_item['product_id'];
          $quantity = $cart_item['quantity'];

          $product_sql = "SELECT name, price FROM products WHERE id = $product_id";
          $product_result = mysqli_query($con, $product_sql);
          $product_row = mysqli_fetch_assoc($product_result);
          $product_name = $product_row['name'];
          $product_price = $product_row['price'];

          $image_sql = "SELECT image_url FROM imgs WHERE product_id = $product_id";
          $image_result = mysqli_query($con, $image_sql);
          $image_row = mysqli_fetch_assoc($image_result);
          $image_url = $image_row['image_url'];

          echo "<div id='boxContainer'>";
          echo "<div id='box'>";
          echo "<img src='" . $image_url . "' alt='" . $product_name . "' />";
          echo "<h3>" . $product_name . "</h3>";
          echo "<h4>Amount: <span> $" . $product_price . "</span></h4>";
          echo "<p>Quantity: <span>" . $quantity . "</span></p>";
          // Add a delete button
          echo "<form action='delete_from_cart.php' method='POST' style='margin-top: 10px;'>";
          echo "<input type='hidden' name='product_id' value='" . $product_id . "' />";
          echo "<button type='submit' class='delete-button'>Remove</button>";
          echo "</form>";
          echo "</div>";
          echo "</div>";
        }
      } else {
        echo "<p>Your cart is empty.</p>";
      }
      ?>
    </div>

    <!-- Total Section -->
    <div id="totalContainer">
      <div id="total">
        <h2>Total Amount</h2>
        <h4>Amount: <span>$
            <?php
            $total_sql = "SELECT SUM(p.price * c.quantity) AS total_price 
                          FROM shopping_cart c 
                          JOIN products p ON c.product_id = p.id 
                          WHERE c.user_id = $user_id";
            $total_result = mysqli_query($con, $total_sql);
            $total_row = mysqli_fetch_assoc($total_result);
            echo number_format($total_row['total_price'], 2);
            ?>
          </span></h4>
        <div id="button">
          <button><a href="checkout.php">Proceed to Checkout</a></button>
        </div>
      </div>
    </div>
  </div>

  <div id="footer"></div>

  <script>
    fetch('footer.php')
      .then(response => response.text())
      .then(data => document.getElementById('footer').innerHTML = data)
      .catch(error => console.error('Error loading footer: ', error));
  </script>
  <script src="script.js"></script>
</body>

</html>

<?php
mysqli_close($con);
?>