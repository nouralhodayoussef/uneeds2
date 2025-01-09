<?php
include 'config.php';
session_start();

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to place an order.";
    exit;
}

$user_id = $_SESSION['user_id'];

$cart_sql = "SELECT c.product_id, c.quantity, p.price 
             FROM shopping_cart c 
             JOIN products p ON c.product_id = p.id 
             WHERE c.user_id = ?";
$stmt = $con->prepare($cart_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_result = $stmt->get_result();

if ($cart_result->num_rows > 0) {
    $total_price = 0;
    while ($cart_row = $cart_result->fetch_assoc()) {
        $total_price += $cart_row['price'] * $cart_row['quantity'];
    }

    $order_sql = "INSERT INTO orders (user_id, total_price) VALUES (?, ?)";
    $order_stmt = $con->prepare($order_sql);
    $order_stmt->bind_param("id", $user_id, $total_price);
    $order_stmt->execute();
    $order_id = $order_stmt->insert_id;
    $order_stmt->close();

    $cart_result->data_seek(0);
    $order_details_sql = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $order_details_stmt = $con->prepare($order_details_sql);

    while ($cart_row = $cart_result->fetch_assoc()) {
        $product_id = $cart_row['product_id'];
        $quantity = $cart_row['quantity'];
        $price = $cart_row['price'];
        $order_details_stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
        $order_details_stmt->execute();
    }
    $order_details_stmt->close();

    $clear_cart_sql = "DELETE FROM shopping_cart WHERE user_id = ?";
    $clear_cart_stmt = $con->prepare($clear_cart_sql);
    $clear_cart_stmt->bind_param("i", $user_id);
    $clear_cart_stmt->execute();
    $clear_cart_stmt->close();

    header("Location: orderPlaced.php");
    exit;
} else {
    echo "Your cart is empty.";
}

$stmt->close();
mysqli_close($con);
?>